<?php
namespace Language\Contracts;


interface Cacheable
{
    public function getCacheKey();
    public function getCacheContent();
}