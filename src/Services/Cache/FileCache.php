<?php

namespace Language\Services\Cache;

use Language\Contracts\CacheDriver;
use Language\Exceptions\CacheCreationException;
use Language\Exceptions\InvalidDirectoryException;
use Psr\Log\LoggerInterface;

class FileCache implements CacheDriver
{
    private $logger;
    private $folder;
    private $filename;
    private $content;

    public function __construct(LoggerInterface $logger, $config = [])
    {
        $this->logger = $logger;
        $this->configure($config);
    }

    public function set($content)
    {
        $this->content = $content;
        $this->setFolder()->saveContentToFile();

        return true;
    }

    protected function setFolder()
    {
        if (!is_dir($this->folder)) {
            $this->logger->info('Creating folder', ['folder' => $this->folder]);
            $this->makeDirectory($this->folder);
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

    protected function makeDirectory($dir)
    {
        if (empty($dir)) {
            throw new InvalidDirectoryException('Cannot create empty directory');
        }
        if (!mkdir($this->folder, 0777, true)) {
            $this->logger->info('Cannot create folder', ['folder' => $this->folder]);
            throw new CacheCreationException('Cannot create directory');
        }
    }

    public function configure($config = [])
    {
        $this->folder = isset($config['folder']) ? $config['folder'] : '';
        $this->filename = isset($config['filename']) ? $config['filename'] : '';

        return $this;
    }
}