<?php

namespace Language\Contracts;

interface CacheDriver
{
    public function set($stringContent);
    public function configure($configurationArray);
}