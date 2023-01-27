<?php

namespace Manojkiran\FilamentDefaultSetup\Classes\Resources\Pages;

use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class FilamentBaseCreateRecord extends CreateRecord
{
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
            $this->getCreateFormAction()
                ->label('Save')
                ->icon('heroicon-o-save')
                ->color('primary'),
            $this->getCreateAnotherFormAction()
                ->label('Save & Create another')
                ->icon('heroicon-o-save-as')
                ->color('primary'),
            $this->getCancelFormAction()
                ->label('Cancel')
                ->color('danger')
                ->icon('heroicon-o-x-circle'),
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
