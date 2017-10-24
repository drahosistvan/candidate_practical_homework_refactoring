<?php

namespace Language;

use Language\Services\Api\LanguageApi;
use Language\Services\Cache\FileCache;
use Language\Services\Cache\LanguageCache;
use Language\Model\ApplicationLanguage;

class LanguageBatchBo
{
    private $cacheDriver;
    private $languageCache;
    private $languageApi;

    public function __construct(LanguageCache $languageCache = null, LanguageApi $languageApi = null)
    {
        $this->cacheDriver = new FileCache();
        $this->languageCache = $languageCache ?: new LanguageCache();
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
                $this->languageCache->create($languageApplication, $this->cacheDriver);
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
                $this->languageCache->create($languageApplication, $this->cacheDriver);
            }
        }
    }
}
