<?php

namespace Language\Model;

use Language\Contracts\Cacheable;
use Language\Contracts\CacheDriver;
use Language\Exceptions\InvalidContentException;

class ApplicationLanguage implements Cacheable
{
    private $content;
    public $application;
    public $type;
    public $language;

    public function __construct($application, $type, $language, $content)
    {
        $this->application = $application;
        $this->type = $type;
        $this->language = $language;

        $this->setContent($content);
    }

    public function getCacheKey()
    {
        return $this->type . '.' . $this->application . '.' . $this->language;
    }

    public function getCacheContent()
    {
        return $this->content;
    }

    public function cache(CacheDriver $cacheDriver)
    {
        $cacheDriver->set($this);
    }

    public function setContent($content)
    {
        if (empty($content)) {
            throw new InvalidContentException('Application language content cannot be empty');
        }

        $this->content = $content;

        return true;
    }
}