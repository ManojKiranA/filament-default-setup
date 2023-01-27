<?php

namespace Manojkiran\FilamentDefaultSetup\Classes\Resources\Pages;

use Closure;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

class FilamentBaseListRecords extends ListRecords
{
    public function getActions(): array
    {
        return [];
    }

    public function getTableActions(): array
    {
        return [
                ActionGroup::make([
                    EditAction::make('list_page_edit_action'),
                    ViewAction::make('list_page_view_action'),
                    DeleteAction::make('list_page_delete_action'),
                ])
                ->icon('heroicon-o-dots-vertical')
                ->tooltip('Actions'),
        ];
    }

    public function getTableHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->url(Arr::has(static::getResource()::getPages(), 'create') ? static::getResource()::getUrl('create') : null),
        ];
    }

    protected function getHeading(): string
    {
        return false;
    }

    protected function getTableBulkActions(): array
    {
        return [];
    }

    protected function getTableEmptyStateActions(): array
    {
        return [
            CreateAction::make()
                ->url(Arr::has(static::getResource()::getPages(), 'create') ? static::getResource()::getUrl('create') : null),
        ];
    }

    protected function getTableEmptyStateDescription(): ?string
    {
        return Str::of('You may create a')
            ->append(' ')
            ->append(static::getResource()::getModelLabel())
            ->append(' ')
            ->append('using the button below.')
            ->unless(static::getResource()::canCreate(), function (Stringable $stringable) {
                return $stringable->limit(0, null);
            });
    }

    protected function getTableEmptyStateHeading(): ?string
    {
        if ($this->getTableSearchQuery()) {
            return Str::of('There are no results that match your search term')
                ->append(' ')
                ->append('[ ')
                ->append($this->getTableSearchQuery())
                ->append(' ]');
        }
        return Str::of('No ')
            ->append(static::getResource()::getPluralModelLabel())
            ->append(' yet');
    }

    protected function getTableEmptyStateIcon(): ?string
    {
        return 'heroicon-o-inbox';
    }

    protected function getTableHeading(): string | Closure | null
    {
        return static::getResource()::getPluralModelLabel();
    }

    protected function getTableQueryStringIdentifier(): string
    {
        return (string) Str::of(static::getResource()::getModel())
            ->remove(['App\\Models\\', 'App/Models/'])
            ->remove(['//', '\\', DIRECTORY_SEPARATOR])
            ->pluralStudly()
            ->snake()
            ->finish('_');

    }

    protected function getIdentifiedTableQueryStringPropertyNameFor(string $property): string
    {
        return $this->getTableQueryStringIdentifier() . strtolower($property);
    }

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return config('filament-default-setup.filament.tables.per_page_select_options');
    }

    protected function getTableRecordUrlUsing(): Closure
    {
        return fn() => null;
    }

    protected function getTitle(): string
    {
        return Str::of(static::getResource()::getModelLabel())
            ->append(' ')
            ->append('-')
            ->append(' ')
            ->append('List');
    }
}
