<?php

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Awobaz\Mutator\Mutator;

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

        $this->app['config']->set('mutators.getters_property','getters');
        $this->app['config']->set('mutators.setters_property','setters');

        Mutator::add('trim_space', function($model, $value, $key){
            return trim($value);
        });

        Mutator::add('remove_extra_space', function($model, $value, $key){
            return preg_replace('/\s+/', ' ', $value);
        })->add('nice', function($model, $value, $key){
            return str_replace('awesome', 'nice', $value);
        })->add('prepend_star', function($model, $value, $key){
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