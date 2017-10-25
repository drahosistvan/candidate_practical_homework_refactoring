<?php

namespace Language\Services\Cache;

use Language\Config;
use Language\Contracts\CacheDriver;
use Language\Exceptions\CacheCreationException;
use Language\Exceptions\InvalidApplicationTypeException;
use Language\Model\ApplicationLanguage;
use Language\Model\ApplicationType;
use Psr\Log\LoggerInterface;

class FileCache implements CacheDriver
{
    private $logger;
    private $folder;
    private $filename;
    private $content;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function set(ApplicationLanguage $language)
    {
        $this->content = $language->getCacheContent();

        $this->setPathVariables($language)
            ->setFolder()
            ->saveContentToFile();

        return true;
    }

    protected function setFolder()
    {
        if (!is_dir($this->folder)) {
            mkdir($this->folder);
            $this->logger->info('Creating folder', ['folder' => $this->folder]);
        }

        return $this;
    }

    protected function saveContentToFile()
    {
        if (file_put_contents($this->folder . $this->filename, $this->content) === false) {
            $this->logger->error('Cannot write to file', ['path' => $this->folder . $this->filename]);
            throw new CacheCreationException('Cannot write the file');
        }

        $this->logger->info('Language file created successfully', ['path' => $this->folder . $this->filename]);

        return $this;
    }

    protected function setPathVariables(ApplicationLanguage $language)
    {
        switch ($language->type) {
            case ApplicationType::APPLET:
                $this->folder = Config::get('system.paths.root') . '/cache/flash/';
                $this->filename = 'lang_' . $language->language . '.xml';

                return $this;
                break;
            case ApplicationType::STANDARD:
                $this->folder = Config::get('system.paths.root') . '/cache/' . $language->application . '/';
                $this->filename = $language->language . '.php';

                return $this;
                break;
            default:
                $this->logger->error('Unexpected application type: ' . $language->type);
                throw new InvalidApplicationTypeException('Unexpected application type: ' . $language->type);
        }
    }
}