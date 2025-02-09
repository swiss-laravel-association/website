<?php

namespace App\Filament\Resources\EventsResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\SpatieTagsColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TalksRelationManager extends RelationManager
{
    protected static string $relationship = 'talks';
    // protected static ?string $title = 'Talks';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name', fn (Builder $query) => $query->where('is_speaker', true))
                    ->searchable()
                    ->preload()
                    ->label('Speaker'),
                Forms\Components\RichEditor::make('description')
                    ->columnSpan('full')
                    ->required(),
                Forms\Components\FileUpload::make(name: 'banner_image')
                    ->label('Banner Image')
                    ->image(),
                // Forms\Components\TagsInput::make('tags'),
                Forms\Components\TextInput::make('sort_order')
                    ->numeric(),
                Forms\Components\TextInput::make('video_url')
                    ->url(),
                SpatieTagsInput::make('tags'),
                Forms\Components\Checkbox::make('is_published')
                    ->label('Is Published')
                    ->default(false),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Speaker'),
                SpatieTagsColumn::make('tags'),
                Tables\Columns\IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
