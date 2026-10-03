<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Demo accounts. In a real deployment you would remove these.
 *  - admin@travelora.test / password123  (role: admin - can run maintenance sync)
 *  - demo@travelora.test  / password123  (role: user)
 */
class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@travelora.test'],
            ['name' => 'Travelora Admin', 'password' => Hash::make('password123'), 'role' => 'admin']
        );

        $demo = User::updateOrCreate(
            ['email' => 'demo@travelora.test'],
            ['name' => 'Demo Traveller', 'password' => Hash::make('password123'), 'role' => 'user']
        );

        Booking::updateOrCreate(
            ['user_id' => $demo->id, 'item_name' => 'Skyline Grand Kuala Lumpur'],
            [
                'booking_type' => 'hotel',
                'item_id'      => 1,
                'travel_date'  => '2026-11-20',
                'end_date'     => '2026-11-23',
                'guests'       => 2,
                'total_price'  => 435.00,
                'status'       => 'pending',
                'is_demo'      => true,
            ]
        );
    }
}
