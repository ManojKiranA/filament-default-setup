<?php

namespace Manojkiran\FilamentDefaultSetup\Classes\Resources;

use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FilamentBaseResource extends Resource
{
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
}