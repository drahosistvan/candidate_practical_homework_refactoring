<?php

namespace Language;

use Language\Contracts\CacheDriver;
use Language\Services\Api\LanguageApi;
use Language\Services\Cache\FileCache;
use Language\Model\ApplicationLanguage;

class LanguageBatchBo
{
    private $cacheDriver;
    private $languageApi;

    public function __construct(CacheDriver $cacheDriver = null, LanguageApi $languageApi = null)
    {
        $this->cacheDriver = $cacheDriver ?: new FileCache();
        $this->languageApi = $languageApi ?: new LanguageApi();
    }

    public function generateLanguageFiles()
    {
        foreach (Config::get('system.translated_applications') as $application => $languages) {
            foreach ($languages as $language) {
                $languageApplication = new ApplicationLanguage(
                    $application,
                    'standard',
                    $language,
                    $this->languageApi->getLanguageFile($language)
                );
                $this->cacheDriver->set($languageApplication);
            }
        }
    }

    public function generateAppletLanguageXmlFiles()
    {
        $applets = [
            'memberapplet' => 'JSM2_MemberApplet',
        ];

        foreach ($applets as $appletDirectory => $appletLanguageId) {
            $languages = $this->languageApi->getAppletLanguages($appletLanguageId);
            foreach ($languages as $language) {
                $languageApplication = new ApplicationLanguage(
                    $appletLanguageId,
                    'applet',
                    $language,
                    $this->languageApi->getAppletLanguageFile($appletLanguageId, $language)
                );
                $this->cacheDriver->set($languageApplication);
            }
        }
    }
}
