<?php

namespace Manojkiran\FilamentDefaultSetup;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\CreateAction as TableCreateAction;
use Filament\Tables\Actions\DeleteAction as TableDeleteAction;
use Filament\Tables\Actions\EditAction as TableEditAction;
use Filament\Tables\Actions\ViewAction as TableViewAction;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class FilamentDefaultSetupServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        TableCreateAction::configureUsing(function (TableCreateAction $tableCreateAction) {
            return $tableCreateAction
                ->disableCreateAnother()
                // ->modalWidth('screen')
                // ->slideOver()
                //@todo move to config
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
                ->tooltip('Delete');
        }, null, true);


        Select::configureUsing(function(Select $select){
            return $select
            ->validationAttribute(fn (Component $component) => $component->getLabel())
            ->placeholder(fn (Component $component) => Str::of('Select')->append(' ')->append('a')->append(' ')->append($component->getLabel()));
        },null,true);

        TextInput::configureUsing(function(TextInput $textInput){
            return $textInput
            ->validationAttribute(fn (Component $component) => $component->getLabel())
            ->placeholder(fn (Component $component) => Str::of('Enter')->append(' ')->append($component->getLabel()));
        },null,true);

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
}
