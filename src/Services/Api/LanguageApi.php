<?php

namespace Language\Services\Api;

use Language\ApiCall;
use Language\Exceptions\Api\ApiErrorExcepiton;
use Language\Exceptions\Api\WrongApiContentException;
use Language\Exceptions\Api\WrongApiResponseException;

class LanguageApi
{
    public function get($data)
    {
        list($target, $mode, $getParameters, $postParameters) = $data;
        $response = ApiCall::call($target, $mode, $getParameters, $postParameters);

        return self::transformResponse($response);
    }

    public function getLanguageFile($language)
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

        return $this->get($data);
    }

    public function getAppletLanguageFile($applet, $language)
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

        return $this->get($data);
    }

    public function getAppletLanguages($applet)
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

        return $this->get($data);
    }

    private function transformResponse($response)
    {
        $this->checkResponse($response);

        return $response['data'];
    }

    private function checkResponse($response)
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