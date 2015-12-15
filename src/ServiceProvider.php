<?php

namespace Benrowe\Laravel\Url;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

/**
 * Url Service Provider
 * Registers the service provider into the application IOC
 *
 */
class ServiceProvider extends LaravelServiceProvider
{
    protected $defer;

    public function boot()
    {
        // blade directives
        Blade::directives(
            'file',
            function ($expression) {
                return '<?php
                try {
                    echo app(\'filesystem-url\')->url' . $expression . ';
                } catch (Exception $e) {
                }
                ?>';
            }
        );
    }

    public function register()
    {
        $this->app->bindShared('filesystem-url', function ($app) {
            return new UrlService(config('filesystems'), $app['request']);
        });
        $this->app->alias('filesystem-url', 'Benrowe\Laravel\Url\UrlService');
    }

    public function provides()
    {
        return [
            'Benrowe\Laravel\Url\UrlService',
            'filesystem-url'
        ];
    }
}
