<?php

use Benrowe\Laravel\Url\UrlService;

class UrlTest extends PHPUnit_Framework_TestCase
{
    protected $url;

    /**
     * Create the url service
     */
    protected function setUp()
    {
        parent::setUp();
        $this->url = new UrlService([
            'default' => 'local',
            'disks' => [
                'local' => [
                    'url' => [
                        'base' => 'http://localhost',
                    ],
                ],
                'secure' => [
                    'url' => [
                        'base' => 'https://localhost',
                        'baseSecure' => 'https://localhost',
                    ],
                ],
                'secureonly' => [
                    'url' => [
                        'baseSecure' => 'https://localhost',
                    ],
                ],
                'prefix' => [
                    'url' => [
                        'base' => 'http://localhost',
                        'prefix' => 'public',
                    ],
                ],
                'disabled' => [
                    'url' => [
                        'base' => 'http://localhost',
                        'enabled' => false,
                    ],
                ],
                'notpublic' => []
            ],
        ], false);
    }

    /**
     *
     *
     *
     */
    public function testUrl()
    {
        // basic url
        $this->assertSame('http://localhost/images/img.jpg', $this->url->url('/images/img.jpg'), 'basic match with leading slash');
        $this->assertSame('http://localhost/images/img.jpg', $this->url->url('images/img.jpg'), 'basic match without leading slash');
        $this->assertSame('https://localhost/images/img.jpg', $this->url->url('images/img.jpg', null, true), 'basic match with forced secure');

        // secure
        $this->assertSame('https://localhost/images/img.jpg', $this->url->url('images/img.jpg', 'secure'));

        // secureonly
        $this->assertSame('https://localhost/images/img.jpg', $this->url->url('images/img.jpg', 'secure'));
        $this->assertSame('https://localhost/images/img.jpg', $this->url->url('images/img.jpg', 'secure', true));
        $this->assertSame('https://localhost/images/img.jpg', $this->url->url('images/img.jpg', 'secure', false));

        // prefix
        $this->assertSame('http://localhost/public/images/img.jpg', $this->url->url('images/img.jpg', 'prefix'));
        $this->assertSame('https://localhost/public/images/img.jpg', $this->url->url('images/img.jpg', 'prefix', true));

        // disabled
        $this->assertSame(null, $this->url->url('images/img.jpg', 'disabled'));
        $this->assertSame(null, $this->url->url('images/img.jpg', 'disabled', true));

        // notpublic
        $this->setExpectedException('Exception');
        $this->url->url('doesntexist', 'notpublic');
    }
}
