<?php

/**
 * This file is part of the laravel url package.
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */


namespace Benrowe\Laravel\Url;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Blade;

/**
 * Url Service Provider
 * Registers the service provider into the application IOC
 *
 * @package    Benrowe\Laravel\Url
 * @author     Ben Rowe <ben.rowe.83@gmail.com>
 * @copyright  Ben Rowe <ben.rowe.83@gmail.com>
 * @link       https://github.com/benrowe/laravel-filesystem-url
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
            return new UrlService(config('filesystems'), $app['request']->secure());
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
