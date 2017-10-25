<?php

namespace LanguageTest\Unit;

use Language\Exceptions\InvalidApplicationTypeException;
use Language\Model\ApplicationLanguage;
use Language\Services\Cache\FileCache;
use PHPUnit_Framework_TestCase;
use Psr\Log\LoggerInterface;
use Language\Config;

class FileCacheTest extends PHPUnit_Framework_TestCase
{
    private $fileCache;

    public function setUp(){
        $logger = $this->createMock(LoggerInterface::class);
        $this->fileCache = new FileCache($logger);
        $this->delete_cache_folder(Config::get('system.paths.root').'/cache');
    }

    /** @test */
    public function it_cannot_generate_filename_for_unknown_application_type()
    {
        $this->expectException(InvalidApplicationTypeException::class);
        $apiMock = $this->getMockBuilder(ApplicationLanguage::class)
            ->setConstructorArgs(['a','b','c','d'])->getMock();

        $this->fileCache->set($apiMock);
    }

    private function delete_cache_folder($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir."/".$object) == "dir"){
                        $this->delete_cache_folder($dir."/".$object);
                    }else{
                        $this->delete_cache_folder($dir."/".$object);
                    }
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }
}