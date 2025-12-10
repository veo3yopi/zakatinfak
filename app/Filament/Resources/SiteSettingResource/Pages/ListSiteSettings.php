<?php

namespace App\Filament\Resources\SiteSettingResource\Pages;

use App\Filament\Resources\SiteSettingResource;
use App\Models\SiteSetting;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSiteSettings extends ListRecords
{
    protected static string $resource = SiteSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function mount(): void
    {
        $setting = SiteSetting::first();
        if (! $setting) {
            $setting = SiteSetting::create([
                'site_name' => 'Zakat Impact',
            ]);
        }

        if ($setting) {
            $this->redirect(SiteSettingResource::getUrl('edit', ['record' => $setting]));
            return;
        }
    }
}
