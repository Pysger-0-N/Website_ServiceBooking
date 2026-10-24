<?php

use App\Models\UserModel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        UserModel::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'phone' => null,
                'address' => null,
                'role' => 'admin',
                'email_verified_at' => null,
                'password' => Hash::make('12345678'),
                'remember_token' => null,
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        UserModel::where('email', 'admin@gmail.com')->delete();
    }
};

