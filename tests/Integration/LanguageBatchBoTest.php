<?php

namespace Language\Test\Integration;

use Illuminate\Support\Collection;
use Language\Exceptions\InvalidApplicationTypeException;
use Language\LanguageBatchBo;
use Language\Model\ApplicationLanguage;
use Language\Services\Data\ApplicationLanguageCollection;
use PHPUnit_Framework_TestCase;

class LanguageBatchBoTest extends PHPUnit_Framework_TestCase
{
    private $app;
    private $collection;

    public function setUp()
    {
        $this->app = new LanguageBatchBo();
        $this->collection = $this->createMock(ApplicationLanguageCollection::class);

    }

    /** @test */
    public function it_cannot_generate_language_for_unknown_application_type()
    {
        $this->expectException(InvalidApplicationTypeException::class);
        $applicationCollection = $this->createMock(ApplicationLanguageCollection::class);
        $applicationCollection->expects($this->any())
            ->method('getApplicationLanguages')
            ->will($this->returnValue($this->getFakeInvalidCollectionData()));
        $app = new LanguageBatchBo($applicationCollection);
        $app->generateLanguageFiles();
    }

    private function getFakeCollectionData()
    {
        $collection = new Collection();
        $collection->push(
            new ApplicationLanguage('test-app', 'standard', 'cn', 'test')
        )->push(
            new ApplicationLanguage('test-app', 'standard', 'hu', 'test')
        );

        return $collection;
    }

    private function getFakeInvalidCollectionData()
    {
        $applicationLanguage = $this->getMockBuilder(ApplicationLanguage::class)
            ->setConstructorArgs(array('test-app', 'test', 'cn', 'test'))
            ->disableOriginalConstructor()
            ->getMock();
        $collection = new Collection();
        $collection->push(
            $applicationLanguage
        );

        return $collection;
    }
}