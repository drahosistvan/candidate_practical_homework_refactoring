<?php

namespace Language;

use Language\Contracts\CacheDriver;
use Language\Services\Cache\FileCache;
use Language\Services\Data\ApplicationLanguageCollection;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class LanguageBatchBo
{
    private $cacheDriver;
    private $applicationLanguageCollection;
    private $logger;

    public function __construct(
        CacheDriver $cacheDriver = null,
        ApplicationLanguageCollection $applicationLanguageCollection = null,
        LoggerInterface $logger = null
    ) {
        $this->logger = $logger ?: $this->setupDefaultLogger();
        $this->cacheDriver = $cacheDriver ?: new FileCache($this->logger);
        $this->applicationLanguageCollection = $applicationLanguageCollection ?: new ApplicationLanguageCollection();

    }

    public function generateLanguageFiles()
    {
        $this->applicationLanguageCollection->getApplicationLanguages()->each->cache($this->cacheDriver);
    }

    public function generateAppletLanguageXmlFiles()
    {
        $this->applicationLanguageCollection->getAppletLanguages()->each->cache($this->cacheDriver);
    }

    private function setupDefaultLogger()
    {
        return (new Logger('languageBatchBo'))->pushHandler(new StreamHandler('php://stdout', Logger::DEBUG));
    }
}
