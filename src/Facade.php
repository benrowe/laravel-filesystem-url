<?php

/**
 * This file is part of the laravel url package.
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */


namespace Benrowe\Laravel\Url;

/**
 *
 * @package    Benrowe\Laravel\Url
 * @author     Ben Rowe <ben.rowe.83@gmail.com>
 * @copyright  Ben Rowe <ben.rowe.83@gmail.com>
 * @link       https://github.com/benrowe/laravel-filesystem-url
 */
class Facade extends \Illuminate\Support\Facades\Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'filesystem-url';
    }
}
