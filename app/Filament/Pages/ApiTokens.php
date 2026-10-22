<?php

namespace App\Filament\Pages;

use App\Enums\Ability;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;
use Livewire\Attributes\Locked;
use UnitEnum;

class ApiTokens extends Page implements HasActions, HasTable
{
    use InteractsWithActions;
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Key;

    protected static ?string $navigationLabel = 'API tokens';

    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    protected static ?string $title = 'API tokens';

    protected static ?string $slug = 'api-tokens';

    protected string $view = 'filament.pages.api-tokens';

    /**
     * Plain-text token of the token created in this request cycle. It is
     * cleared on dismissal and never persisted.
     */
    #[Locked]
    public ?string $newToken = null;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => PersonalAccessToken::query()
                ->where('tokenable_type', auth()->user()->getMorphClass())
                ->where('tokenable_id', auth()->id()))
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('abilities')
                    ->badge()
                    ->listWithLineBreaks()
                    ->limitList(3)
                    ->expandableLimitedList()
                    ->formatStateUsing(fn (string $state): string => Ability::tryFrom($state)?->getLabel() ?? $state),
                TextColumn::make('last_used_at')
                    ->dateTime()
                    ->placeholder('Never'),
                TextColumn::make('created_at')
                    ->dateTime(),
                TextColumn::make('expires_at')
                    ->dateTime()
                    ->placeholder('Never'),
            ])
            ->recordActions([
                Action::make('revoke')
                    ->label('Revoke')
                    ->icon(Heroicon::Trash)
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (PersonalAccessToken $record): void {
                        $record->delete();
                    }),
            ]);
    }

    public function createTokenAction(): Action
    {
        $groups = Ability::groupedByResource();
        $fieldNames = collect(array_keys($groups))
            ->map(fn (string $resource): string => $this->abilityFieldName($resource))
            ->all();

        return Action::make('createToken')
            ->label('Create token')
            ->icon(Heroicon::Plus)
            ->modalHeading('Create API token')
            ->modalSubmitActionLabel('Create')
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                DatePicker::make('expires_at')
                    ->label('Expires on')
                    ->helperText('Leave empty for a token that does not expire.')
                    ->minDate(now()->addDay()->startOfDay())
                    ->rule('after:today'),
                Section::make('Abilities')
                    ->description('Select at least one ability.')
                    ->schema(collect($groups)->map(
                        fn (array $options, string $resource): CheckboxList => CheckboxList::make($this->abilityFieldName($resource))
                            ->label($resource)
                            ->options($options)
                            ->bulkToggleable()
                            ->columns(2)
                            ->required(fn (Get $get): bool => collect($fieldNames)
                                ->every(fn (string $name): bool => blank($get($name))))
                            ->validationMessages(['required' => 'Select at least one ability.']),
                    )->values()->all()),
            ])
            ->action(function (array $data) use ($fieldNames): void {
                $abilities = collect($fieldNames)
                    ->flatMap(fn (string $name): array => $data[$name] ?? [])
                    ->unique()
                    ->values()
                    ->all();

                $expiresAt = filled($data['expires_at'] ?? null)
                    ? Carbon::parse($data['expires_at'])->endOfDay()
                    : null;

                $this->newToken = auth()->user()
                    ->createToken($data['name'], $abilities, $expiresAt)
                    ->plainTextToken;
            });
    }

    /**
     * @return array<Action>
     */
    protected function getHeaderActions(): array
    {
        return [$this->createTokenAction()];
    }

    public function dismissNewToken(): void
    {
        $this->newToken = null;
    }

    public function getMcpUrl(): string
    {
        return url('/mcp');
    }

    private function abilityFieldName(string $resource): string
    {
        return 'abilities_'.Str::snake($resource);
    }
}
