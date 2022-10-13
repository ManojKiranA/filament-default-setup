<?php

namespace Manojkiran\FilamentDefaultSetup\Classes\Resources\Pages;

use Closure;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class FilamentBaseListRecords extends ListRecords
{

    public function getActions(): array
    {
        return [];
    }

    public function getTableActions(): array
    {
        return [

            EditAction::make('list_page_edit_action'),

            // Action::make('list_page_edit_action')
            //     ->iconButton()
            //     ->tooltip('Edit')
            //     ->label('Edit')
            //     ->icon('heroicon-o-pencil-alt')
            //     ->visible(fn($record): bool => static::getResource()::canEdit($record) && static::getResource()::hasPage('edit'))
            //     ->url(fn($record): string => static::getResource()::hasPage('edit') ? static::getResource()::getUrl('edit', ['record' => $record]) : null),

            Action::make('list_page_view_action')
                ->iconButton()
                ->tooltip('View')
                ->label('View')
                ->icon('heroicon-o-eye')
                ->visible(fn($record): bool => static::getResource()::canView($record) && static::getResource()::hasPage('view'))
                ->url(fn($record): string => static::getResource()::hasPage('view') ? static::getResource()::getUrl('view', ['record' => $record]) : null),

            Action::make('list_page_delete_action')
                ->iconButton()
                ->tooltip('Delete')
                ->label('Delete')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation(true)
                ->modalHeading(Str::of('Delete')->append(' ')->append(static::getResource()::getModelLabel()))
                ->centerModal(true)
                ->modalSubheading('Are you sure you\'d like to delete ? This cannot be undone.')
                ->modalButton('Delete')
                ->action(function ($record) {
                    $record->delete();
                    Filament::notify('success', Str::of(static::getResource()::getModelLabel())->append(' ')->append('Deleted')->toString());
                })
                ->visible(fn($record): bool => static::getResource()::canDelete($record)),
        ];
    }

    public function getTableHeaderActions(): array
    {
        return [
            Action::make('list_page_table_header_add_action')
                ->button()
                ->label('Add')
                ->iconPosition('before')
                ->icon('heroicon-o-plus-circle')
                ->color('primary')
                ->url(Arr::has(static::getResource()::getPages(), 'create') ? static::getResource()::getUrl('create') : null)
                ->visible(fn($record): bool => static::getResource()::canCreate() && Arr::has(static::getResource()::getPages(), 'create')),
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
        $hasCreatePage = static::getResource()::hasPage('create');
        $hasCreatePagePermission = static::getResource()::canCreate();

        return [
            Action::make('create')
                ->button()
                ->label('Add')
                ->iconPosition('before')
                ->icon('heroicon-o-plus-circle')
                ->visible($hasCreatePagePermission && $hasCreatePage)
                ->url($hasCreatePage ? static::getResource()::getUrl('create') : null),
        ];
    }

    protected function getTableEmptyStateDescription(): ?string
    {
        if (Arr::has(static::getResource()::getPages(), 'create') && static::getResource()::canCreate()) {
            return Str::of('You may create a')
                ->append(' ')
                ->append(static::getResource()::getModelLabel())
                ->append(' ')
                ->append('using the button below.');
        }

        return '';
    }

    protected function getTableEmptyStateHeading(): ?string
    {
        return Str::of('No ')
            ->append(static::getResource()::getPluralModelLabel())
            ->append(' yet');
    }

    protected function getTableEmptyStateIcon(): ?string
    {
        return 'heroicon-o-bookmark';
    }

    protected function getTableHeading(): string | Closure | null
    {
        return static::getResource()::getPluralModelLabel();
    }

    protected function getTableQueryStringIdentifier(): string
    {
        return (string) Str::of(static::getResource()::getModel())
            ->basename()
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
