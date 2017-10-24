<?php

namespace Language\Models;

class ApplicationLanguage
{
    public $path;
    public $content;

    public function __construct($path, $content)
    {
        $this->path = $path;
        $this->content = $content;
    }
}