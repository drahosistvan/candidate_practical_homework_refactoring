<?php

namespace Language\Services\Cache;

use Language\Model\ApplicationLanguage;
use Language\Contracts\Cacher;

class LanguageCache
{
    public function create(ApplicationLanguage $language, Cacher $cacher)
    {
        $cacher->set($language);
    }
}