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
    private $folder;
    private $filename;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function set(ApplicationLanguage $language)
    {
        $this->folder = $this->getPathForApplicationLanguage($language);
        $this->filename = $this->getFilenameForApplicationLanguage($language);
        if (!is_dir($this->folder)) {
            mkdir($this->folder);
        }
        if (file_put_contents($this->folder.$this->filename, $language->getCacheContent()) === false) {
            $this->logger->error('Cannot write to file', ['path' => $this->folder.$this->filename]);
            throw new CacheCreationException('Cannot write the file');
        }

        $this->logger->info('Language file created successfully', ['path' => $this->folder.$this->filename]);
        return true;
    }

    protected function getPathForApplicationLanguage(ApplicationLanguage $language) {
        switch ($language->type) {
            case 'applet':
                return Config::get('system.paths.root') . '/cache/flash/';
                break;
            case 'standard':
                return Config::get('system.paths.root') . '/cache/'.$language->application . '/';
                break;
            default:
                throw new CacheCreationException('Cannot define path for '.$language->type.' application type');
                return;
        }
    }

    protected function getFilenameForApplicationLanguage(ApplicationLanguage $language) {
        switch ($language->type) {
            case 'applet':
                return 'lang_' . $language->language . '.xml';
                break;
            case 'standard':
                return $language->language . '.php';
                break;
            default:
                throw new CacheCreationException('Cannot define filename for '.$language->type.' application type');
                return;
        }
    }

}