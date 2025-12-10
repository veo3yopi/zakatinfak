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
                'about_summary' => 'Kami berkhidmat dalam pemberdayaan masyarakat melalui pengelolaan dana zakat, infak, dan sedekah secara amanah, profesional, dan transparan.',
                'about_hero_url' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1600&q=80',
                'hero_cta_label' => 'Donasi Sekarang',
                'hero_cta_url' => url('/programs'),
                'about_title' => 'Tentang Zakat Impact',
                'about_subtitle' => 'Mengelola zakat, infak, dan sedekah dengan amanah dan laporan berkala.',
                'about_mission' => 'Memberdayakan umat melalui pengelolaan zakat yang profesional dan transparan.',
                'about_vision' => 'Menjadi lembaga zakat terpercaya dengan dampak keberlanjutan.',
                'about_values' => 'Amanah, Transparan, Profesional, Kolaboratif.',
                'about_principles' => 'Syariah, amanah, integritas, kemanfaatan, keadilan, kepastian hukum.',
                'about_goals' => 'Meningkatkan kesejahteraan, pendidikan, kesehatan, dan pemberdayaan ekonomi umat.',
                'impact_beneficiaries' => 12000,
                'impact_programs' => 180,
                'impact_regions' => 42,
                'impact_volunteers' => 320,
            ]
        );
    }
}
