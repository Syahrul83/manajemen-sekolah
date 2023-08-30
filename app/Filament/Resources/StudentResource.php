<?php

namespace App\Filament\Resources;

use App\Events\PromoteStudent;
use Filament\Forms;
use Filament\Tables;
use App\Models\Student;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\GlobalSearch\Actions\Action;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\StudentResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\StudentResource\RelationManagers;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
                Forms\Components\TextInput::make('student_id')
                 ->required()
                ->maxLength(10),
                Forms\Components\TextInput::make('address_1'),
                Forms\Components\TextInput::make('address_2'),
                Select::make('standard_id')
                ->required()

                ->relationship('standard', 'name')
                ->searchable()
                ->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('standard.name')->searchable(),


            ])
            ->filters([
                Filter::make('is_featured')
                  ->query(fn (Builder $query): Builder => $query->where('standard_id', 1)),
                  SelectFilter::make('standard_id')
                    ->options([
                        '1' => 'stdr 1',
                        '2' => 'stdr 2',
                        '3' => 'stdr 3',
                    ])->label('select standard'),

                    SelectFilter::make('All Standard')->relationship('standard', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->successNotificationTitle('User deleted'),
                Tables\Actions\ActionGroup::make([
                Tables\Actions\Action::make('Promote')
                ->action(function (Student $record) {
                    $record->standard_id =  $record->standard_id + 1 ;
                    $record->save();
                })->color('success')
                ->requiresConfirmation(),


                Tables\Actions\Action::make('Demoted')
                ->action(function (Student $record) {
                    if($record->standard_id > 1) {
                        $record->standard_id =  $record->standard_id - 1 ;
                        $record->save();
                    }
                })->color('danger')
                ->requiresConfirmation(),
            ]),



            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    // cara promete dengan mengunakan collection jika di list laravel
                        Tables\Actions\BulkAction::make('Promote All')
                ->action(function (Collection $record) {
                    $record->each(
                        function ($record) {
                            // $record->standard_id =  $record->standard_id + 1 ;
                            // $record->save();
                            event(new PromoteStudent($record));

                        }
                    );

                })->color('success')
                ->requiresConfirmation()
                ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ])->defaultSort('id', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\GuardiansRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Name' => $record->name,
            'Standard' => $record->standard->name,
        ];
    }


    public static function getGlobalSearchResultActions(Model $record): array
    {
        return [
            Action::make('edit')
            ->iconButton()
            ->icon('heroicon-s-pencil')
            ->url(static::getUrl('edit', ['record' => $record])),
        ];
    }

}
