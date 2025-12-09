<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SiteSetting::firstOrCreate(
            ['id' => 1],
            [
                'site_name' => 'Zakat Impact',
                'site_tagline' => 'Transparan • Amanah • Cepat',
                'site_description' => 'Lembaga zakat, infak, dan sedekah yang fokus pada transparansi dan dampak nyata.',
                'hero_cta_label' => 'Donasi Sekarang',
                'hero_cta_url' => url('/programs'),
                'about_title' => 'Tentang Zakat Impact',
                'about_subtitle' => 'Mengelola zakat, infak, dan sedekah dengan amanah dan laporan berkala.',
                'about_mission' => 'Memberdayakan umat melalui pengelolaan zakat yang profesional dan transparan.',
                'about_vision' => 'Menjadi lembaga zakat terpercaya dengan dampak keberlanjutan.',
                'about_values' => 'Amanah, Transparan, Profesional, Kolaboratif.',
                'impact_beneficiaries' => 12000,
                'impact_programs' => 180,
                'impact_regions' => 42,
                'impact_volunteers' => 320,
            ]
        );
    }
}
