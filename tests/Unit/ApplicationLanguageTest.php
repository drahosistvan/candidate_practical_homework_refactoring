<?php

namespace LanguageTest\Unit;

use Language\Contracts\Cacheable;
use Language\Exceptions\InvalidApplicationTypeException;
use Language\Exceptions\InvalidContentException;
use Language\Model\ApplicationLanguage;
use PHPUnit_Framework_TestCase;


class ApplicationLanguageTest extends PHPUnit_Framework_TestCase
{
    private $applicationLanguage;

    public function setUp()
    {
        $this->applicationLanguage = new ApplicationLanguage('a', 'standard', 'c', 'a');
    }

    /** @test */
    public function its_content_cannot_be_empty()
    {
        $this->expectException(InvalidContentException::class);
        new ApplicationLanguage('a', 'standard', 'c', '');
    }

    /** @test */
    public function application_type_cannot_be_unknown()
    {
        $this->expectException(InvalidApplicationTypeException::class);
        new ApplicationLanguage('a','b','c','d');
    }


}