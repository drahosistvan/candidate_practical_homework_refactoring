<?php

namespace Language\Api;

use Language\ApiCall;
use Language\Exceptions\Api\ApiErrorExcepiton;
use Language\Exceptions\Api\WrongApiContentException;
use Language\Exceptions\Api\WrongApiResponseException;

class LanguageApi
{
    public static function get($data)
    {
        list($target, $mode, $getParameters, $postParameters) = $data;
        $response = ApiCall::call($target, $mode, $getParameters, $postParameters);

        return self::transformResponse($response);
    }

    public static function getLanguageFile($language)
    {
        $data = [
            'system_api',
            'language_api',
            [
                'system' => 'LanguageFiles',
                'action' => 'getLanguageFile',
            ],
            [
                'language' => $language
            ],
        ];

        return self::get($data);
    }

    public static function getAppletLanguageFile($applet, $language)
    {
        $data = [
            'system_api',
            'language_api',
            [
                'system' => 'LanguageFiles',
                'action' => 'getAppletLanguageFile',
            ],
            [
                'applet'   => $applet,
                'language' => $language,
            ],
        ];

        return self::get($data);
    }

    public static function getAppletLanguages($applet)
    {
        $data = [
            'system_api',
            'language_api',
            [
                'system' => 'LanguageFiles',
                'action' => 'getAppletLanguages',
            ],
            ['applet' => $applet],
        ];

        return self::get($data);
    }

    private static function transformResponse($response)
    {
        self::checkResponse($response);

        return $response['data'];
    }

    private static function checkResponse($response)
    {
        if ($response === false || !isset($response['status'])) {
            throw new ApiErrorExcepiton('Error during the api call');
        }

        if ($response['status'] != 'OK') {
            throw new WrongApiResponseException('Wrong response: '
                . (!empty($result['error_type']) ? 'Type(' . $result['error_type'] . ') ' : '')
                . (!empty($result['error_code']) ? 'Code(' . $result['error_code'] . ') ' : '')
                . ((string)$result['data']));
        }

        if ($response['data'] === false) {
            throw new WrongApiContentException('Wrong content!');
        }
    }
}