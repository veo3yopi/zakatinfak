<?php

namespace Database\Seeders;

use App\Models\ProgramCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProgramCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Zakat',
            'Infak',
            'Sedekah',
            'Kemanusiaan',
        ];

        foreach ($categories as $index => $name) {
            ProgramCategory::firstOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'sort_order' => $index + 1,
                ]
            );
        }
    }
}
