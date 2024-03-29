<?php

namespace Manojkiran\FilamentDefaultSetup;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Actions\ViewAction as PageViewAction;
use Filament\Pages\Actions\EditAction as PageEditAction;
use Filament\Pages\Actions\DeleteAction as PageDeleteAction;
use Filament\Tables\Actions\CreateAction as TableCreateAction;
use Filament\Tables\Actions\DeleteAction as TableDeleteAction;
use Filament\Tables\Actions\EditAction as TableEditAction;
use Filament\Tables\Actions\ViewAction as TableViewAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class FilamentDefaultSetupServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->bootTableActions();

        PageViewAction::configureUsing(function (PageViewAction $pageViewAction) {
            return $pageViewAction
                ->iconButton()
                ->color('primary')
                ->icon('heroicon-o-eye')
                ->label('View')
                ->tooltip('View');

        }, null, true);

        PageEditAction::configureUsing(function (PageEditAction $pageEditAction) {
            return $pageEditAction
               ->iconButton()
                ->icon('heroicon-o-pencil-alt')
                ->label('Edit')
                ->color('primary')
                ->tooltip('Edit');

        }, null, true);

        //

        PageDeleteAction::configureUsing(function (PageDeleteAction $pageDeleteAction) {
            return $pageDeleteAction
                ->iconButton()
                ->icon('heroicon-o-trash')
                ->label('Delete')
                ->tooltip('Delete')
                ->successNotificationMessage(function (PageDeleteAction $action) {
                    return Str::of($action->getModelLabel())
                        ->append(' ')
                        ->append('Deleted');
                });
        }, null, true);

        Select::configureUsing(function (Select $select) {
            return $select
                ->validationAttribute(fn(Component $component) => $component->getLabel())
                ->placeholder(fn(Component $component) => Str::of('Select')->append(' ')->append('a')->append(' ')->append($component->getLabel()))
                ->loadingMessage(fn(Component $component) => Str::of('Loading')->append(' ')->append(Str::plural($component->getLabel()))->append('...'))
            ;
        }, null, true);

        TextInput::configureUsing(function (TextInput $textInput) {
            return $textInput
                ->validationAttribute(fn(Component $component) => $component->getLabel())
                ->placeholder(fn(Component $component) => Str::of('Enter')->append(' ')->append($component->getLabel()));
        }, null, true);

        TextColumn::configureUsing(function (TextColumn $textColumn) {
            return $textColumn
                ->copyMessage(fn(TextColumn $column) => Str::of($column->getLabel())->finish(' ')->append('Copied to clipboard'))
                ->copyMessageDuration(800);
        }, null, true);

        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'filament-default-setup');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'filament-default-setup');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/filament-default-setup.php' => config_path('filament-default-setup.php'),
            ], 'config');

            // Publishing the views.
            /*$this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/filament-default-setup'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/filament-default-setup'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/filament-default-setup'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/filament-default-setup.php', 'filament-default-setup');
    }

    public function bootTableActions()
    {
        TableCreateAction::configureUsing(function (TableCreateAction $tableCreateAction) {
            return $tableCreateAction
                ->disableCreateAnother()
                ->modalWidth('screen')
                ->slideOver()
                ->icon('heroicon-o-plus-circle')
                ->label(function (TableCreateAction $action) {
                    return Str::of('Create')
                        ->append(' ')
                        ->append($action->getModelLabel());
                })
                ->successNotificationMessage(function (TableCreateAction $action) {
                    return Str::of($action->getModelLabel())
                        ->append(' ')
                        ->append('Created');
                });
        }, null, true);

        TableEditAction::configureUsing(function (TableEditAction $tableEditAction) {
            return $tableEditAction
                ->iconButton()
                ->icon('heroicon-o-pencil-alt')
                ->label('Edit')
                ->color('primary')
                ->successNotificationMessage(function (TableEditAction $action) {
                    return Str::of($action->getModelLabel())
                        ->append(' ')
                        ->append('Updated');
                })
                ->tooltip('Edit');

        }, null, true);

        TableViewAction::configureUsing(function (TableViewAction $tableViewAction) {
            return $tableViewAction
                ->iconButton()
                ->color('primary')
                ->icon('heroicon-o-eye')
                ->label('View')
                ->tooltip('View');

        }, null, true);

        TableDeleteAction::configureUsing(function (TableDeleteAction $tableDeleteAction) {
            return $tableDeleteAction
                ->iconButton()
                ->icon('heroicon-o-trash')
                ->label('Delete')
                ->tooltip('Delete')
                ->successNotificationMessage(function (TableDeleteAction $action) {
                    return Str::of($action->getModelLabel())
                        ->append(' ')
                        ->append('Deleted');
                });
        }, null, true);

    }

}
