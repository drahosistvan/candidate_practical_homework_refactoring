<?php

namespace Language\Models;

class ApplicationLanguageFile
{
    public $path;
    public $content;

    public function __construct($path, $content)
    {
        $this->path = $path;
        $this->content = $content;
    }
}