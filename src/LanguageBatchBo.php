<?php

namespace Language;

use Language\Contracts\CacheDriver;
use Language\Services\Cache\FileCache;
use Language\Services\Data\ApplicationLanguageCollection;

class LanguageBatchBo
{
    private $cacheDriver;
    private $applicationLanguageCollection;

    public function __construct(CacheDriver $cacheDriver = null, ApplicationLanguageCollection $applicationLanguageCollection = null)
    {
        $this->cacheDriver = $cacheDriver ?: new FileCache();
        $this->applicationLanguageCollection = $applicationLanguageCollection ?: new ApplicationLanguageCollection();
    }

    public function generateLanguageFiles()
    {
        $this->applicationLanguageCollection->getApplicationLanguages()->each(function($applicationLanguage){
            $this->cacheDriver->set($applicationLanguage);
        });
    }

    public function generateAppletLanguageXmlFiles()
    {
        $this->applicationLanguageCollection->getAppletLanguages()->each(function($applicationLanguage){
            $this->cacheDriver->set($applicationLanguage);
        });
    }
}
