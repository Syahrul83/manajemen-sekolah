<?php

namespace App\Filament\Resources\StudentResource\Pages;

use Filament\Actions;
use Pages\EditStudent;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\StudentResource;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;

    public function getRedirectUrl(): string
    {
        // return $this->previousUrl ?? $this->getResource()::getUrl('index'); kembali ke list
        return $this->getResource()::getUrl('edit', ['record' => $this->record]);  // redirect route dengan record edit
    }
}
