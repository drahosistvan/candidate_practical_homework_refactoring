<?php

namespace Language\Services\Cache;

use Language\Model\ApplicationLanguage;
use Language\Contracts\CacheDriver;

class LanguageCache
{
    public function create(ApplicationLanguage $language, CacheDriver $cacher)
    {
        $cacher->set($language);
    }
}