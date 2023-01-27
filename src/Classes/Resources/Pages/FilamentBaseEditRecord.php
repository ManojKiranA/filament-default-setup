<?php

namespace Manojkiran\FilamentDefaultSetup\Classes\Resources\Pages;

use Filament\Notifications\Notification;
use Filament\Pages\Actions\Action;
use Filament\Pages\Actions\DeleteAction;
use Filament\Pages\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class FilamentBaseEditRecord extends EditRecord
{
    public function getActions(): array
    {
        return [
            ViewAction::make('edit_page_view_action'),
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
            $this->getSaveFormAction()
                ->label('Update')
                ->icon('heroicon-o-save')
                ->color('primary'),
            $this->getCancelFormAction()
                ->label('Cancel')
                ->color('danger')
                ->icon('heroicon-o-x-circle'),
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
