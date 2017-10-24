<?php

namespace Language\Models;

use Language\Config;

class ApplicationLanguage
{
    public $path;
    public $content;
    public $application;
    public $type;
    public $language;

    public function __construct($application, $type, $language, $content, $path = null)
    {
        $this->application = $application;
        $this->type = $type;
        $this->content = $content;
        $this->language = $language;
        $this->path = $path ?: $this->setDefaultPath();
    }

    protected function setDefaultPath() {
        switch ($this->type) {
            case 'applet':
                return Config::get('system.paths.root') . '/cache/flash/lang_' . $this->language . '.xml';
                break;
            case 'standard':
                return Config::get('system.paths.root') . '/cache/' . $this->application . '/'. $this->language . '.php';
                break;
            default:
                return;
        }
    }
}