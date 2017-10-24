<?php

namespace Language\Contracts;

use Language\Model\ApplicationLanguage;
interface CacheDriver
{
    public function set(ApplicationLanguage $language);
}