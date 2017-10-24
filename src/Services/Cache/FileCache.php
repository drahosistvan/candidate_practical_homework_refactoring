<?php

namespace Language\Services\Cache;

use Language\Contracts\CacheDriver;
use Language\Exceptions\CacheCreationException;
use Language\Model\ApplicationLanguage;
use Language\Config;
use Psr\Log\LoggerInterface;

class FileCache implements CacheDriver
{
    private $logger;
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function set(ApplicationLanguage $language)
    {
        if (file_put_contents($this->getPathForApplicationLanguage($language), $language->getCacheContent()) === false) {
            $this->logger->error('Cannot write to file', ['path' => $this->getPathForApplicationLanguage($language)]);
            throw new CacheCreationException('Cannot write the file');
        }

        $this->logger->info('Language file created successfully', ['path' => $this->getPathForApplicationLanguage($language)]);
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