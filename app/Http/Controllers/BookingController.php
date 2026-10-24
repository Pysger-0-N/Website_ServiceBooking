<?php

namespace App\Http\Controllers;

use App\Models\BookingModel;
use App\Models\CategoryModel;
use App\Models\ServiceModel;
use App\Models\UserModel;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function selectCategory()
    {
        $categories = CategoryModel::withCount('services')->get();
        return view('booking.select-category', compact('categories'));
    }

    public function selectService(CategoryModel $category)
    {
        $services = $category->services;
        return view('booking.select-service', compact('category', 'services'));
    }

    public function selectProvider(ServiceModel $service)
    {
        $providers = UserModel::where('role', 'provider')
            ->whereHas('providerProfile', function ($q) {
                $q->where('status', 'active');
            })
            ->whereHas('providerCategories', function ($q) use ($service) {
                $q->where('category_id', $service->category_id);
            })
            ->with('providerProfile')
            ->get();

        $providersMapData = $providers
            ->filter(fn($p) => $p->providerProfile && $p->providerProfile->location_lat && $p->providerProfile->location_lng)
            ->map(fn($p) => [
                'id'         => $p->id,
                'name'       => $p->name,
                'bio'        => $p->providerProfile->bio ?? '-',
                'experience' => $p->providerProfile->experience_years ?? 0,
                'radius'     => $p->providerProfile->service_radius_km ?? 0,
                'available'  => ($p->providerProfile->available_from ?? '-') . ' - ' . ($p->providerProfile->available_until ?? '-'),
                'lat'        => $p->providerProfile->location_lat,
                'lng'        => $p->providerProfile->location_lng,
                'url'        => route('booking.form', [$service, $p]),
            ])->values();


        return view('booking.select-provider', compact('service', 'providers', 'providersMapData'));
    }

    public function form(ServiceModel $service, UserModel $provider)
    {
        $profile = $provider->providerProfile;

        if (!$profile) {
            return redirect()->route('booking.select-category')
                ->withErrors(['provider' => 'Provider ini tidak memiliki profile aktif.']);
        }

        $availableDays  = $profile->available_days ?? [];
        $availableFrom  = $profile->available_from ?? '00:00';
        $availableUntil = $profile->available_until ?? '23:59';

        return view('booking.form', compact('service', 'provider', 'availableDays', 'availableFrom', 'availableUntil'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'service_id'       => 'required|exists:services,id',
            'provider_id'      => 'required|exists:users,id',
            'booking_date'     => 'required|date|after_or_equal:today',
            'booking_time'     => 'required',
            'location_address' => 'required|string',
            'notes'            => 'nullable|string',
        ]);

        $service  = ServiceModel::findOrFail($request->service_id);
        $provider = UserModel::with('providerProfile')->findOrFail($request->provider_id);
        $profile  = $provider->providerProfile;

        if (!$profile || $profile->status !== 'active') {
            return back()->withErrors([
                'provider_id' => 'Provider ini tidak memiliki profile aktif.'
            ])->withInput();
        }


        // VALIDASI Hari tersedia
        $availableDays = $profile->available_days ?? [];
        $bookingDay    = strtolower(date('l', strtotime($request->booking_date)));

        if (!empty($availableDays) && !in_array($bookingDay, $availableDays)) {
            return back()->withErrors([
                'booking_date' => 'Provider tidak tersedia di hari ' . ucfirst($bookingDay) . '. Hari tersedia: ' . implode(', ', $availableDays),
            ])->withInput();
        }

        // VALIDASI Jam tersedia
        $availableFrom  = $profile->available_from ?? '00:00';
        $availableUntil = $profile->available_until ?? '23:59';
        $bookingTime    = $request->booking_time;

        if ($bookingTime < $availableFrom || $bookingTime > $availableUntil) {
            return back()->withErrors([
                'booking_time' => "Provider hanya tersedia pukul {$availableFrom} - {$availableUntil}.",
            ])->withInput();
        }

        // VALIDASI Cek booking bentrok
        $bentrok = BookingModel::where('provider_id', $provider->id)
            ->where('booking_date', $request->booking_date)
            ->where('booking_time', $request->booking_time)
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($bentrok) {
            return back()->withErrors([
                'booking_time' => 'Provider sudah ada booking di tanggal dan jam tersebut. Pilih waktu lain.',
            ])->withInput();
        }

        // VALIDASI Radius lokasi
        if ($request->location_lat && $request->location_lng && $profile->location_lat && $profile->location_lng) {
            $distance = $this->calculateDistance(
                $profile->location_lat, $profile->location_lng,
                $request->location_lat, $request->location_lng
            );

            $radiusKm = $profile->service_radius_km ?? 10;

            if ($distance > $radiusKm) {
                return back()->withErrors([
                    'location_address' => "Lokasi kamu ({$distance} km) diluar radius layanan provider ({$radiusKm} km).",
                ])->withInput();
            }
        }

        // SIMPAN BOOKING
        $booking = BookingModel::create([
            'customer_id'      => auth()->id(),
            'provider_id'      => $request->provider_id,
            'service_id'       => $request->service_id,
            'booking_date'     => $request->booking_date,
            'booking_time'     => $request->booking_time,
            'location_address' => $request->location_address,
            'location_lat'     => $request->location_lat,
            'location_lng'     => $request->location_lng,
            'notes'            => $request->notes,
            'total_price'      => $service->base_price,
            'status'           => 'pending',
        ]);

        return redirect()->route('booking.success', $booking);
    }

    public function success(BookingModel $booking)
    {
        abort_if($booking->customer_id !== auth()->id(), 403);
        $booking->load('service', 'provider');
        return view('booking.success', compact('booking'));
    }

    private function calculateDistance($lat1, $lng1, $lat2, $lng2): float
    {
        $earthRadius = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng/2) * sin($dLng/2);
        return round($earthRadius * 2 * atan2(sqrt($a), sqrt(1-$a)), 2);
    }
}
