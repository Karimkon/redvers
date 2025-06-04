<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Motorcycle;

class MotorcycleSeeder extends Seeder
{
    public function run(): void
    {
        Motorcycle::create([
            'type' => 'brand_new',
            'cash_price' => 5500000,
            'hire_price_total' => 9685714.29,
            'daily_payment' => 15000,
            'weekly_payment' => 90000,
            'duration_days' => 730,
        ]);

        Motorcycle::create([
            'type' => 'retrofitted',
            'cash_price' => 4500000,
            'hire_price_total' => 7708571.43,
            'daily_payment' => 0,
            'weekly_payment' => 72000,
            'duration_days' => 730,
        ]);
    }
}

