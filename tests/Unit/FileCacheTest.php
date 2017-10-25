<?php

namespace LanguageTest\Unit;

use Language\Exceptions\CacheCreationException;
use Language\Model\ApplicationLanguage;
use Language\Services\Cache\FileCache;
use PHPUnit_Framework_TestCase;
use Psr\Log\LoggerInterface;

class FileCacheTest extends PHPUnit_Framework_TestCase
{
    private $fileCache;

    public function setUp(){
        $logger = $this->createMock(LoggerInterface::class);
        $this->fileCache = new FileCache($logger);
    }

    /** @test */
    public function it_cannot_generate_filename_for_unknown_application_type()
    {
        $this->expectException(CacheCreationException::class);
        $this->fileCache->set(new ApplicationLanguage('a','a','c','d'));

    }
}