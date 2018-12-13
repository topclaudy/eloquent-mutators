<?php

use Awobaz\Mutator\Mutator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Awobaz\Mutator\Facades\Mutator as MutatorFacade;

abstract class TestCase extends BaseTestCase
{
    /**
     * Boots the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../vendor/laravel/laravel/bootstrap/app.php';

        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

        return $app;
    }

    /**
     * Setup DB before each test.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->app['config']->set('database.default','sqlite');
        $this->app['config']->set('database.connections.sqlite.database', ':memory:');

        $this->app['config']->set('mutators.accessors_property','accessors');
        $this->app['config']->set('mutators.mutators_property','mutators');

        $this->app->singleton('mutator', function ($app) {
            return new Mutator();
        });

        $this->app->alias('mutator', 'Awobaz\Mutator\Mutator');

        MutatorFacade::extend('trim_whitespace', function($model, $value, $key){
            return trim($value);
        })->extend('remove_extra_whitespace', function($model, $value, $key){
            return preg_replace('/\s+/', ' ', $value);
        })->extend('nice', function($model, $value, $key){
            return str_replace('awesome', 'nice', $value);
        })->extend('prepend_star', function($model, $value, $key){
            return '*'.$value;
        });

        $this->migrate();
    }

    /**
     * run package database migrations
     *
     * @return void
     */
    public function migrate()
    {
        $fileSystem = new Filesystem;
        $fileSystem->requireOnce(__DIR__ . "/migrations.php");

        (new Migration())->up();
    }
}