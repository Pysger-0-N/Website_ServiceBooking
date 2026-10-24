<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\CategoryModel;
use App\Models\ProviderProfileModel;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        $profile = auth()->user()->providerProfile;
        $categories = CategoryModel::all();
        $selectedCategories = [];

        if ($profile && method_exists($profile, 'categories')) {
            $selectedCategories = $profile->categories->pluck('id')->toArray();
        }

        return view('provider.profile', compact('profile', 'categories', 'selectedCategories'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'bio'               => 'nullable|string|max:1000',
            'experience_years'  => 'required|integer|min:0|max:50',
            'certifications'    => 'nullable|string|max:500',
            'service_radius_km' => 'required|integer|min:1|max:100',
            'available_days'    => 'required|array',
            'available_from'    => 'required',
            'available_until'   => 'required',
            'categories'        => 'required|array|min:1',
            'categories.*'      => 'exists:categories,id',
        ]);

        $profile = ProviderProfileModel::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'bio'               => $request->bio,
                'experience_years'  => $request->experience_years,
                'certifications'    => $request->certifications,
                'service_radius_km' => $request->service_radius_km,
                'available_days'    => $request->available_days,
                'available_from'    => $request->available_from,
                'available_until'   => $request->available_until,
                'location_address'  => $request->location_address,
                'location_lat'      => $request->location_lat,
                'location_lng'      => $request->location_lng,
                'status'            => 'active',
            ]
        );

        auth()->user()->providerCategories()->sync($request->categories);

        return redirect()->route('provider.profile')->with('success', 'Profil berhasil diupdate!');
    }

    public function updateServices(Request $request)
    {
        return redirect()->route('provider.profile')->with('success', 'Layanan berhasil diupdate!');
    }
}

