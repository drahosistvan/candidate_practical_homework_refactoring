<?php

namespace Language\Model;


use Language\Exceptions\InvalidApplicationTypeException;

class ApplicationType
{
    const STANDARD = 'standard';
    const APPLET = 'applet';

    public function getType($type)
    {
        switch ($type) {
            case strtolower($type) == self::STANDARD:
                return self::STANDARD;
                break;
            case strtolower($type) == self::APPLET:
                return self::APPLET;
                break;
            default:
                throw new InvalidApplicationTypeException("Application type not supported");
        }
    }
}