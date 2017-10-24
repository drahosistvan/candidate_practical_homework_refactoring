<?php

namespace Language\Cache;

use Language\Models\ApplicationLanguage;

class LanguageCache
{
    public static function set(ApplicationLanguage $language)
    {
        file_put_contents($language->path,$language->content);
    }
}