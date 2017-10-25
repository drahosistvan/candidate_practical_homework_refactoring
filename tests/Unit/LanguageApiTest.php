<?php

use Language\Services\Api\LanguageApi;
use Language\Exceptions\Api\ApiErrorExcepiton;

class LanguageApiTest extends PHPUnit_Framework_TestCase
{
    private $api;

    public function setUp(){
        $this->api = new LanguageApi();
    }

    /** @test */
    public function it_can_fail_when_wrong_method_is_called()
    {
        $this->expectException(ApiErrorExcepiton::class);
        $data = [
            'system_api',
            'language_api',
            [
                'system' => 'LanguageFiles',
                'action' => 'invalidAction',
            ],
            [
                'language' => 'hu'
            ],
        ];
        $this->api->get($data);
    }

    /** @test */
    public function it_can_provide_data()
    {
        $data = [
            'system_api',
            'language_api',
            [
                'system' => 'LanguageFiles',
                'action' => 'getAppletLanguages',
            ],
            [
                'language' => 'hu'
            ],
        ];
        $this->assertNotEmpty($this->api->get($data));
    }
}