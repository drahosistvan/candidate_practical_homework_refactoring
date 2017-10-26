<?php

namespace Language;

use Language\Contracts\CacheDriver;
use Language\Exceptions\InvalidApplicationTypeException;
use Language\Model\ApplicationLanguage;
use Language\Model\ApplicationType;
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
        ApplicationLanguageCollection $applicationLanguageCollection = null,
        CacheDriver $cacheDriver = null,
        LoggerInterface $logger = null
    ) {
        $this->logger = $logger ?: $this->setupDefaultLogger();
        $this->cacheDriver = $cacheDriver ?: new FileCache($this->logger);
        $this->applicationLanguageCollection = $applicationLanguageCollection ?: new ApplicationLanguageCollection();
    }

    public function generateLanguageFiles()
    {
        $this->applicationLanguageCollection->getApplicationLanguages()->each(function ($applicationLanguage) {
            $this->cacheDriver->configure($this->getCacheConfig($applicationLanguage))->set($applicationLanguage->getContent());
        });
    }

    public function generateAppletLanguageXmlFiles()
    {
        $this->applicationLanguageCollection->getAppletLanguages()->each(function ($applicationLanguage) {
            $this->cacheDriver->configure($this->getCacheConfig($applicationLanguage))->set($applicationLanguage->getContent());
        });
    }

    private function setupDefaultLogger()
    {
        return (new Logger('languageBatchBo'))->pushHandler(new StreamHandler('php://stdout', Logger::DEBUG));
    }

    protected function getCacheConfig(ApplicationLanguage $language)
    {
        switch ($language->type) {
            case ApplicationType::APPLET:
                return [
                    'folder'   => $this->getCacheFolder() . 'flash/',
                    'filename' => 'lang_' . $language->language . '.xml',
                ];
                break;
            case ApplicationType::STANDARD:
                return [
                    'folder'   => $this->getCacheFolder() . $language->application . '/',
                    'filename' => $language->language . '.php',
                ];
                break;
            default:
                $this->logger->error('Unexpected application type: ' . $language->type);
                throw new InvalidApplicationTypeException('Unexpected application type: ' . $language->type);
        }
    }

    protected function getCacheFolder()
    {
        return Config::get('system.paths.root') . '/cache/';
    }
}
