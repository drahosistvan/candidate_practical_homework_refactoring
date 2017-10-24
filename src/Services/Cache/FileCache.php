<?php

namespace Language\Services\Cache;

use Language\Contracts\CacheDriver;
use Language\Exceptions\CacheCreationException;
use Language\Model\ApplicationLanguage;
use Language\Config;

class FileCache implements CacheDriver
{
    public function set(ApplicationLanguage $language)
    {
        if (file_put_contents($this->getPathForApplicationLanguage($language), $language->getCacheContent()) === false) {
            throw new CacheCreationException('Cannot write the file');
        }

        return true;
    }

    protected function getPathForApplicationLanguage(ApplicationLanguage $language) {
        switch ($language->type) {
            case 'applet':
                return Config::get('system.paths.root') . '/cache/flash/lang_' . $language->language . '.xml';
                break;
            case 'standard':
                return Config::get('system.paths.root') . '/cache/' . $language->application . '/'. $language->language . '.php';
                break;
            default:
                return;
        }
    }

}