<?php

namespace Language\Test\Unit;

use Language\Exceptions\CacheCreationException;
use Language\Exceptions\InvalidDirectoryException;
use Language\Services\Cache\FileCache;
use PHPUnit_Framework_TestCase;
use Psr\Log\LoggerInterface;

class FileCacheTest extends PHPUnit_Framework_TestCase
{
    private $fileCache;
    private $cacheFolder;

    public function setUp(){
        $logger = $this->createMock(LoggerInterface::class);
        $this->fileCache = new FileCache($logger);
        $this->cacheFolder = realpath(dirname(__FILE__)).'/../../tmp/';
        $this->delete_cache_folder($this->cacheFolder);
    }

    /** @test */
    public function it_cannot_create_empty_directory()
    {
        $this->expectException(InvalidDirectoryException::class);
        $this->fileCache->set('Some content');
    }

    /** @test */
    public function if_cannot_create_file_exception_thrown()
    {
        $this->expectException(CacheCreationException::class);
        //die($this->cacheFolder);
        $this->fileCache->configure([
            'folder' => $this->cacheFolder,
            'filename' => 'test.txt'
        ])->set(new \stdClass());
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

    public function tearDown()
    {
        @unlink($this->cacheFolder.'test.txt');
        $this->delete_cache_folder($this->cacheFolder);
    }
}