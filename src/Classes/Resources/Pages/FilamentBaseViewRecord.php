<?php

namespace Manojkiran\FilamentDefaultSetup\Classes\Resources\Pages;

use Illuminate\Support\Str;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class FilamentBaseViewRecord extends ViewRecord
{
    public function getActions(): array
    {
        $hasEditPage = static::getResource()::hasPage('edit');
        $hasEditPagePermission = static::getResource()::canEdit($this->record);

        $hasIndexPage = static::getResource()::hasPage('index');
        $hasIndexPagePermission = static::getResource()::canViewAny();

        
        return [
            Action::make('view_page_edit_action')
                ->iconButton()
                ->tooltip('Edit')
                ->label('Edit')
                ->iconPosition('before')
                ->color('success')
                ->icon('heroicon-o-pencil')
                ->visible($hasEditPagePermission && $hasEditPage)
                ->url($hasEditPage ? static::getResource()::getUrl('edit', ['record' => $this->record]) : null),

            Action::make('view_page_back_action')
                ->iconButton()
                ->tooltip('Back')
                ->label('Back')
                ->iconPosition('before')
                ->color('danger')
                ->icon('heroicon-o-arrow-circle-left')
                ->visible($hasIndexPagePermission && $hasIndexPage)
                ->url($hasIndexPage ? static::getResource()::getUrl('index') : null),
        ];
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

    protected function getTitle(): string
    {       
        return Str::of($this->getRecordTitle())
        ->append(' ')
        ->append('-')
        ->append(' ')
        ->append('View');
    }

    protected function getHeading(): string
    {
        return Str::of('View')
            ->append(' ')
            ->append('-')
            ->append(' ')
            ->append($this->getRecordTitle());
    }
}
