<?php

namespace LanguageTest\Unit;

use Language\Contracts\CallableApi;
use Language\Exceptions\Api\ApiErrorExcepiton;
use Language\Exceptions\Api\WrongApiContentException;
use Language\Exceptions\Api\WrongApiResponseException;
use Language\Services\Api\LanguageApi;
use PHPUnit_Framework_TestCase;

class LanguageApiTest extends PHPUnit_Framework_TestCase
{
    private $api;

    public function setUp()
    {
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
                'language' => 'hu',
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
                'language' => 'hu',
            ],
        ];
        $this->assertNotEmpty($this->api->get($data));
    }

    /** @test */
    public function it_can_throw_exception_if_no_data_returned()
    {
        $this->expectException(WrongApiContentException::class);
        $apiMock = $this->createMock(CallableApi::class);
        $apiMock->expects($this->any())
            ->method('call')
            ->will($this->returnValue([
                'status' => 'OK',
            ]));

        $languageApi = new LanguageApi($apiMock);
        $languageApi->getAppletLanguages('applet');
    }

    /** @test */
    public function it_can_throw_exception_if_status_is_not_ok()
    {
        $this->expectException(WrongApiResponseException::class);
        $apiMock = $this->createMock(CallableApi::class);
        $apiMock->expects($this->any())
            ->method('call')
            ->will($this->returnValue([
                'status' => 'ERROR',
            ]));

        $languageApi = new LanguageApi($apiMock);
        $languageApi->getAppletLanguages('applet');
    }


}