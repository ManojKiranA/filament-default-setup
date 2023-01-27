<?php

namespace Manojkiran\FilamentDefaultSetup\Classes\Resources\Pages;

use Illuminate\Support\Str;
use Filament\Pages\Actions\Action;
use Filament\Pages\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class FilamentBaseViewRecord extends ViewRecord
{
    public function getActions(): array
    {
        return [
            EditAction::make(),
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
