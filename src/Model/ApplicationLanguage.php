<?php

namespace Language\Model;

use Language\Contracts\Cacheable;
use Language\Contracts\CacheDriver;

class ApplicationLanguage implements Cacheable
{
    public $content;
    public $application;
    public $type;
    public $language;

    public function __construct($application, $type, $language, $content)
    {
        $this->application = $application;
        $this->type = $type;
        $this->content = $content;
        $this->language = $language;
    }

    public function getCacheKey()
    {
        return $this->type.'.'.$this->application.'.'.$this->language;
    }

    public function getCacheContent()
    {
        return $this->content;
    }

    public function cache(CacheDriver $cacheDriver)
    {
        $cacheDriver->set($this);
    }
}