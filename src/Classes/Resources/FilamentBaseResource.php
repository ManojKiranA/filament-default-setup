<?php

namespace Manojkiran\FilamentDefaultSetup\Classes\Resources;

use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FilamentBaseResource extends Resource
{
    protected static function getActiveNavigationIcon(): string
    {
        return Str::of(static::getNavigationIcon())->replace(['heroicon-o-'],['heroicon-s-']);
    }

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return static::getUrl('view', ['record' => $record]);
    }

    public static function getSlug(): string
    {
        return collect([
            static::getNavigationGroup(),
            ...Str::of(parent::getSlug())->explode('/'),
            ])
            ->map(fn ($each) => Str::of($each)->lower()->slug('-')->toString())
            ->implode('/');
    }

    public static function getBreadcrumb(): string
    {
        return static::getPluralModelLabel();
    }
}