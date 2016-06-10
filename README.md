# Laravel Filesystem Url

[![Build Status](https://travis-ci.org/benrowe/laravel-filesystem-url.svg?branch=master&format=flat-square)](https://travis-ci.org/benrowe/laravel-filesystem-url)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/benrowe/laravel-filesystem-url/badges/quality-score.png?b=master&format=flat-square)](https://scrutinizer-ci.com/g/benrowe/laravel-filesystem-url/?branch=master)
[![Total Downloads](https://poser.pugx.org/benrowe/laravel-filesystem-url/d/total.svg?format=flat-square)](https://packagist.org/packages/benrowe/laravel-filesystem-url)
[![Latest Stable Version](https://poser.pugx.org/benrowe/laravel-filesystem-url/v/stable.svg?format=flat-square)](https://packagist.org/packages/benrowe/laravel-filesystem-url)
[![Latest Unstable Version](https://poser.pugx.org/benrowe/laravel-filesystem-url/v/unstable.svg?format=flat-square)](https://packagist.org/packages/benrowe/laravel-filesystem-url)
[![License](https://poser.pugx.org/benrowe/laravel-filesystem-url/license.svg?format=flat-square)](https://packagist.org/packages/benrowe/laravel-filesystem-url)


Provides a url generation service for your configured filesystems

This extends from laravel's filesystem config.

## Installation

### Composer

Simply add a dependency on benrowe/laravel-filesystem-url to your project's composer.json file if you use [Composer]() to manage the dependencies of your project.

    {
        "require-dev": {
             "benrowe/laravel-filesystem-url": "*"
        }
    }

You can also install this package via the composer command:

    composer require 'benrowe/laravel-filesystem-url=*'

## Configuration

### Service Provider + Facade

Once you've installed the package via composer, you need to register the provided
service provider into laravel's provider stack.

    Benrowe\Laravel\Url\ServiceProvider::class

Optionally you can register the facade:

    'Url' => Benrowe\Laravel\Url\Facade::class,

### Filesystem

The url builder uses the existing filesystem config, by extending it with some additional details.

Each `disk` thats configured can have a `url` key + associated settings

    'local' => [
        'url' => [
            'base' => 'http://localhost',
            'baseSecure' => 'https://localhost', // optional
            'prefix' => 'assets', // optional
            'enabled' => true, //optional
        ]
    ]

Any filesystems that don't have the url key won't allow a url to be generated (throws an exception).

## Usage

The primary method is the `url($path, $disk = null, $secure = false)`

It can be accessed in the following ways:

### Facade

    Url::url('path/to/file.jpg', 'local', $forceSecure);
    // outputs as http://localhost/assets/path/to/file.jpg

### Blade Directive

The package provides a convenient blade directive

    @url('path/to/file.jpg', 'diskname')
    // the blade directive will trap exceptions if the disk doesn't exist, or is not configured correctly.

## Todo

* Dynamic config - ability to get the config for s3 buckets based on other config, api, etc.
