<?php

namespace App\Filament\Resources;

use Filament\Tables;
use App\Models\Location;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\LocationResource\Pages;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class LocationResource extends Resource
{
    protected static ?string $model = Location::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Name')
                    ->required(),
                TextInput::make('Complement'),
                TextInput::make('address'),
                TextInput::make('address2')
                    ->label('Address 2'),
                TextInput::make('postal_code')
                    ->required(),
                TextInput::make('city')
                    ->required(),
                TextInput::make('canton'),
                Select::make('country_id')
                    ->relationship('country', 'name', fn (Builder $query) => $query->orderBy('sort_order')),
                Textarea::make('description'),
                Textarea::make('notes')
                    ->label('Internal notes'),
                SpatieMediaLibraryFileUpload::make('cover')
                    ->label('Cover image')
                    ->collection('locations-cover-image')
                    ->responsiveImages()
                    ->conversion('thumb'),
                SpatieMediaLibraryFileUpload::make('photos')
                    ->multiple()
                    ->collection('locations-images')
                    ->responsiveImages()
                    ->conversion('thumb')
                    ->reorderable(),
                TextInput::make('capacity')
                    ->label('Max capacity')
                    ->numeric(),
                Toggle::make('is_published')
                    ->label('Is Published')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('address'),
                TextColumn::make('city'),
                TextColumn::make('canton'),
                TextColumn::make('country.code'),
                IconColumn::make('is_published')
                    ->boolean(),
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
            'index' => Pages\ListLocations::route('/'),
            'create' => Pages\CreateLocation::route('/create'),
            'edit' => Pages\EditLocation::route('/{record}/edit'),
        ];
    }
}
