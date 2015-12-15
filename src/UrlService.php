<?php

/**
 * This file is part of the laravel url package.
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */

namespace Benrowe\Laravel\Url;

use Illuminate\Http\Request;
use Exception;

/**
 * URI Service that generates a public endpoint from the requested filesystem
 * The url that is generated DOES NOT gurantee the existance of the endpoint
 *
 * @package    Benrowe\Laravel\Url
 * @author     Ben Rowe <ben.rowe.83@gmail.com>
 * @copyright  Ben Rowe <ben.rowe.83@gmail.com>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://github.com/benrowe/laravel-filesystem-url
 */
class UrlService
{
    /**
     * The filesystem configurations
     *
     * @var array
     */
    private $config;

    /**
     * should the url that is generated be secure?
     *
     * @var boolean
     */
    private $secure;

    private $compiledConfig = [];

    /**
     * Constructor
     *
     * @param array   $config  the filesystem configuration
     * @param boolean $secure should the url be secure
     */
    public function __construct(array $config, $secure = false)
    {
        $this->config  = $config;
        $this->secure = $secure;
    }

    /**
     * Generate a complete URI
     *
     * @param  string $path the relative path of the resource
     * @param  [type] $disk [description]
     * @param boolean $secure force the url to be secure or not, if not defined uses current request
     * @return string
     */
    public function url($path, $disk = null, $secure = null)
    {
        $config =  $this->getDiskConfig($disk);
        if ($config['enabled'] === false) {
            return null;
        }
        if (!is_bool($secure)) {
            $secure = $this->secure;
        }

        $base = $secure ? $config['baseSecure'] : $config['base'];

        return $base.$config['prefix'].ltrim($path,'/');
    }

    /**
     * Get the filesystem disk identifier
     *
     * @param  string $disk the name of the disk
     * @return mixed
     * @throws Exception
     */
    private function getDiskConfig($disk)
    {
        if (!$disk) {
            $disk = $this->config['default'];
        }

        if (array_key_exists($disk, $this->compiledConfig)) {
            return $this->compiledConfig[$disk];
        }

        // ensure we have a basic config available
        if (!array_key_exists($disk, $this->config['disks'])) {
            throw new Exception('Specified disk "' . $disk . '" does not exist in config/filesystems.php');
        } else if (!array_key_exists('url', $this->config['disks'][$disk])) {
            throw new Exception('Specified disk "' . $disk . '" does not contain config for public access');
        }

        return $this->compiledConfig[$disk] = $this->normaliseConfig($this->config['disks'][$disk]['url']);
    }

    /**
     * Standardise the url configuration for use later
     *
     * @param  [type] $diskConfig [description]
     * @return [type]             [description]
     */
    private function normaliseConfig($diskConfig)
    {
        // ensure base config has ending slash
        $diskConfig['base'] = rtrim($diskConfig['base'], '/').'/';
        $diskConfig['prefix'] = isset($diskConfig['prefix']) ? trim($diskConfig['prefix'], '/').'/' : null;
        $diskConfig['baseSecure'] = isset($diskConfig['baseSecure']) ? rtrim($diskConfig['baseSecure'], '/').'/' : 'https'.substr($diskConfig['base'], 4);
        $diskConfig['enabled'] = !isset($diskConfig['enabled']) || $diskConfig['enabled'] === true;
        return $diskConfig;
    }
}
