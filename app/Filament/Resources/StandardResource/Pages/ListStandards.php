<?php

namespace App\Filament\Resources\StandardResource\Pages;

use Filament\Actions;

use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\StandardResource;

class ListStandards extends ListRecords
{
    protected static string $resource = StandardResource::class;

    protected function getHeaderActions(): array
    {
    return [
        Actions\CreateAction::make(),

    ];

}


}
