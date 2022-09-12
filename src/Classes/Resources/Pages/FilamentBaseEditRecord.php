<?php

namespace Manojkiran\FilamentDefaultSetup\Classes\Resources\Pages;


use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class FilamentBaseEditRecord extends EditRecord
{
    public function getActions(): array
    {
        $hasIndexPage = static::getResource()::hasPage('index');
        $hasIndexPagePermission = static::getResource()::canViewAny();

        $hasViewPage = static::getResource()::hasPage('view');
        $hasViewPagePermission = static::getResource()::canView($this->record);

        return [
            
            Action::make('edit_page_show_action')
                ->iconButton()
                ->tooltip('Back')
                ->label('Back')
                ->color('primary')
                ->icon('heroicon-o-eye')
                ->visible($hasViewPagePermission && $hasViewPage)
                ->url($hasViewPage ? static::getResource()::getUrl('view', ['record' => $this->record]) : null),

            Action::make('edit_page_back_action')
                ->iconButton()
                ->tooltip('Back')
                ->label('Back')
                ->iconPosition('before')
                ->color('danger')
                ->icon('heroicon-o-arrow-circle-left')
                ->visible($hasIndexPage && $hasIndexPagePermission)
                ->url($hasIndexPage ? static::getResource()::getUrl('index') : null),
        ];
    }

    public function getBreadcrumb(): string
    {
        return 'Edit';
    }

    protected function getBreadcrumbs(): array
    {
        $resource = static::getResource();

        $breadcrumb = $this->getBreadcrumb();

        return array_merge(
            [$resource::getUrl() => $resource::getBreadcrumb()],
            (filled($breadcrumb) ? [$breadcrumb] : []),
        );
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('edit_page_form_save_action')
                ->button()
                ->iconPosition('before')
                ->label('Update')
                ->submit('save'),

                 Action::make('cancel')
                ->label('Cancel')
                ->url(static::getResource()::getUrl())
                ->color('secondary'),
        ];
    }

    protected function getRedirectUrl(): ?string
    {
        return static::getResource()::getUrl('index');
    }

    protected function getSavedNotificationMessage(): ?string
    {
        return Str::of(static::getResource()::getModelLabel())->append(' ')->append('Updated')->__toString();
    }

    protected function getTitle(): string
    {       
        return Str::of($this->getRecordTitle())
        ->append(' ')
        ->append('-')
        ->append(' ')
        ->append('Edit');
    }

    protected function getHeading(): string
    {
        return Str::of('Edit')
            ->append(' ')
            ->append('-')
            ->append(' ')
            ->append($this->getRecordTitle());
    }
}
