<?php

namespace Language;

use Language\Api\LanguageApi;
use Language\Cache\LanguageCache;
use Language\Models\ApplicationLanguage;

/**
 * Business logic related to generating language files.
 */
class LanguageBatchBo
{
    public static function generateLanguageFiles()
    {
        foreach (Config::get('system.translated_applications') as $application => $languages) {
            foreach ($languages as $language) {
                $languageApplication = new ApplicationLanguage($application, 'standard', $language,
                    LanguageApi::getLanguageFile($language));
                LanguageCache::set($languageApplication);
            }
        }
    }

    public static function generateAppletLanguageXmlFiles()
    {
        $applets = [
            'memberapplet' => 'JSM2_MemberApplet',
        ];

        foreach ($applets as $appletDirectory => $appletLanguageId) {
            $languages = LanguageApi::getAppletLanguages($appletLanguageId);
            foreach ($languages as $language) {
                $languageApplication = new ApplicationLanguage($appletLanguageId, 'applet', $language,
                    LanguageApi::getAppletLanguageFile($appletLanguageId, $language));
                LanguageCache::set($languageApplication);
            }
        }
    }
}
