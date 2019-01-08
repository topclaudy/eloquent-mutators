<?php

namespace Awobaz\Mutator;

use Awobaz\Mutator\Console\InstallCommand;
use Awobaz\Mutator\Console\PublishCommand;
use Awobaz\Mutator\Facades\Mutator as MutatorFacade;
use Illuminate\Support\ServiceProvider;

class MutatorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPublishing();
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    private function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/mutators.php' => config_path('mutators.php'),
            ], 'mutators-config');

            $this->publishes([
                __DIR__.'/../stubs/MutatorServiceProvider.stub' => app_path('Providers/MutatorServiceProvider.php'),
            ], 'mutators-provider');
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBindings();

        $this->registerCommands();

        $this->registerDefaultExtensions();

        $this->mergeConfig();
    }

    /**
     * Register the application bindings.
     *
     * @return void
     */
    private function registerBindings()
    {
        $this->app->singleton('mutator', function ($app) {
            return new Mutator();
        });

        $this->app->alias('mutator', 'Awobaz\Mutator\Mutator');
    }

    /**
     * Register the application commands.
     *
     * @return void
     */
    private function registerCommands()
    {
        $this->commands([
            InstallCommand::class,
            PublishCommand::class,
        ]);
    }

    private function registerDefaultExtensions()
    {
        $extensions = [
            //PHP functions
            'strtolower'   => 'lower_case',
            'strtoupper'   => 'upper_case',
            'ucfirst'      => 'capitalize',
            'ucwords'      => 'capitalize_all',
            'trim'         => 'trim_whitespace',
            //Framework functions
            'camel_case'   => 'camel_case',
            'snake_case'   => 'snake_case',
            'kebab_case'   => 'kebab_case',
            'studly_case'  => 'studly_case',
            'title_case'   => 'title_case',
            'str_plural'   => 'plural',
            'str_singular' => 'singular',
            'str_slug'     => 'slug',
        ];

        foreach ($extensions as $function => $extension) {
            if (function_exists($function)) {
                MutatorFacade::extend($extension, function ($model, $value, $key) use ($function) {
                    return $function($value);
                });
            }
        }

        MutatorFacade::extend('remove_extra_whitespace', function ($model, $value, $key) {
            return preg_replace('/\s+/', ' ', $value);
        });

        MutatorFacade::extend('preg_replace', function ($model, $value, $key, $pattern, $replacement, $limit = -1) {
            return preg_replace($pattern, $replacement, $value, $limit);
        });
    }

    private function mergeConfig()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/mutators.php', 'mutators');
    }
}
