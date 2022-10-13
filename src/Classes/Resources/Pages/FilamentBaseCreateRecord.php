<?php

namespace Manojkiran\FilamentDefaultSetup\Classes\Resources\Pages;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Filament\Pages\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;

class FilamentBaseCreateRecord extends CreateRecord
{
    public function getActions(): array
    {
        $hasIndexPage = static::getResource()::hasPage('index');
        $hasIndexPagePermission = static::getResource()::canViewAny();

        return [
            Action::make('create_page_back_action')
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
        return 'Create';
    }

    protected function getCreatedNotificationMessage(): ?string
    {
        return (string) Str::of(static::getResource()::getModelLabel())
            ->append(' ')
            ->append('Created');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('create_page_form_save_action')
                ->iconPosition('before')
                ->label('Save')
                ->submit('create'),

            Action::make('create_page_form_cancel_action')
                ->label('Cancel')
                ->url(static::getResource()::getUrl('index'))
                ->color('secondary'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    protected function getTitle(): string
    {
        return Str::of(static::getResource()::getModelLabel())
            ->append(' ')
            ->append('-')
            ->append(' ')
            ->append('Create');
    }

    protected function getHeading(): string
    {
        return Str::of('Create')
            ->append(' ')
            ->append('-')
            ->append(' ')
            ->append(static::getResource()::getModelLabel());
    }

    protected function onValidationError(ValidationException $exception): void
    {
        collect($exception->validator->getMessageBag()->getMessages())
            ->map(function ($messagesArray) {
                Notification::make()
                    ->title('Validation Error')
                    ->body(Arr::first($messagesArray))
                    ->danger()
                    ->icon('heroicon-o-exclamation')
                    ->send();
            });
    }
}
