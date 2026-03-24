<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        // Ejemplo para la paleta Luxury (Negro y Dorado)
        \App\Models\Setting::updateOrCreate(['key' => 'primary_color'], ['value' => '#D4AF37']);
        \App\Models\Setting::updateOrCreate(['key' => 'sidebar_color'], ['value' => '#000000']);
        \App\Models\Setting::updateOrCreate(['key' => 'site_name'], ['value' => 'Premium Beauty Salon']);
    }
}
