<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserModel;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = UserModel::with('providerProfile')->latest()->get();
        return view('admin.users.index', compact('users'));
    }

    public function show(UserModel $user)
    {
        $user->load('providerProfile', 'providerCategories', 'bookingsAsCustomer', 'bookingsAsProvider');
        return view('admin.users.show', compact('user'));
    }

    public function destroy(UserModel $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus!');
    }

    public function verifyProvider(UserModel $user)
    {
        abort_if($user->role !== 'provider', 400);

        $status = $user->providerProfile->status === 'active' ? 'inactive' : 'active';
        $user->providerProfile->update(['status' => $status]);

        $msg = $status === 'active' ? 'Provider berhasil diaktifkan!' : 'Provider berhasil dinonaktifkan!';
        return redirect()->route('admin.users.index')->with('success', $msg);
    }
}
