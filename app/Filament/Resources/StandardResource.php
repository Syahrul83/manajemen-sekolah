<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StandardResource\Pages;
use App\Filament\Resources\StandardResource\RelationManagers;
use App\Models\Standard;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Contracts\HasTable;

class StandardResource extends Resource
{
    protected static ?string $model = Standard::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required()->maxLength(10),
                TextInput::make('class_number')->numeric()->required()->maxLength(10)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                  TextColumn::make('no')->rowIndex(),
                  TextColumn::make('id'),
                TextColumn::make('name')->label("Nama"),
                TextColumn::make('class_number'),

                Tables\Columns\TextColumn::make('nilai'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStandards::route('/'),
            // 'create' => Pages\CreateStandard::route('/create'),
            // 'edit' => Pages\EditStandard::route('/{record}/edit'),
        ];
    }
}
