<?php

namespace Language\Contracts;

use Language\Model\ApplicationLanguage;
interface Cacher
{
    public function set(ApplicationLanguage $language);
}