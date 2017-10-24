<?php

namespace Language\Services\Data;

use Illuminate\Support\Collection;
use Language\Config;
use Language\Model\ApplicationLanguage;
use Language\Services\Api\LanguageApi;

class ApplicationLanguageCollection
{
    public $applicationLanguages;
    private $languageApi;

    public function __construct(LanguageApi $languageApi = null)
    {
        $this->languageApi = $languageApi ?: new LanguageApi();
    }

    public function getApplicationLanguages()
    {
        $this->applicationLanguages = new Collection();
        foreach (Config::get('system.translated_applications') as $application => $languages) {
            foreach ($languages as $language) {
                $this->applicationLanguages->push(new ApplicationLanguage(
                    $application,
                    'standard',
                    $language,
                    $this->languageApi->getLanguageFile($language)
                ));
            }
        }

        return $this->applicationLanguages;
    }

    public function getAppletLanguages()
    {
        $this->applicationLanguages = new Collection();
        $applets = [
            'memberapplet' => 'JSM2_MemberApplet',
        ];

        foreach ($applets as $appletDirectory => $appletLanguageId) {
            $languages = $this->languageApi->getAppletLanguages($appletLanguageId);
            foreach ($languages as $language) {
                $this->applicationLanguages->push(new ApplicationLanguage(
                    $appletLanguageId,
                    'applet',
                    $language,
                    $this->languageApi->getAppletLanguageFile($appletLanguageId, $language)
                ));
            }
        }

        return $this->applicationLanguages;
    }
}