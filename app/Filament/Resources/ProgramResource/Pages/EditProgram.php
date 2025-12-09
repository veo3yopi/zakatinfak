<?php

namespace App\Filament\Resources\ProgramResource\Pages;

use App\Filament\Resources\ProgramResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Carbon;

class EditProgram extends EditRecord
{
    protected static string $resource = ProgramResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (($data['status'] ?? null) === 'published' && empty($data['published_at'])) {
            $data['published_at'] = Carbon::now();
        }

        if (($data['status'] ?? null) !== 'published') {
            $data['published_at'] = null;
        }

        return $data;
    }
}
