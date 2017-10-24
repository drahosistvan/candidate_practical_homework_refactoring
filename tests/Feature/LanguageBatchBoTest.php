<?php
use Illuminate\Support\Collection;
use Language\ApiCall;
use Language\Config;
use Language\LanguageBatchBo;

class LanguageBatchBoTest extends PHPUnit_Framework_TestCase
{
    private $language;
    private $xmlFileList;
    private $phpFileList;

    public function setUp()
    {
        $this->language = new LanguageBatchBo();
        $this->xmlFileList = $this->xml_language_file_list();
        $this->phpFileList = $this->php_language_file_list();
    }

    /** @test */
    public function main_class_has_two_public_methods()
    {
        $this->assertTrue(
            method_exists($this->language, 'generateLanguageFiles'),
            'generateLanguageFiles method not exists in LanguageBatchBo'
        );
        $this->assertTrue(
            method_exists($this->language, 'generateAppletLanguageXmlFiles'),
            'generateAppletLanguageXmlFiles method not exists in LanguageBatchBo'
        );
    }

    /** @test */
    public function it_generates_language_files()
    {
        $this->delete_language_files();
        $this->language->generateLanguageFiles();

        $this->phpFileList->each(function ($content,$path) {
            $this->assertFileExists($path);
            $this->assertEquals($content, file_get_contents($path));
        });
    }

    /** @test */
    public function it_generates_xml_language_files()
    {
        $this->delete_xml_language_files();
        $this->language->generateAppletLanguageXmlFiles();

        $this->xmlFileList->each(function ($content,$path) {
            $this->assertFileExists($path);
            $this->assertEquals($content, file_get_contents($path));
        });
    }

    private function delete_language_files()
    {
        $this->phpFileList->each(function ($content,$path) {
            @unlink($path);
        });
    }

    private function delete_xml_language_files()
    {
        $this->xmlFileList->each(function ($content,$path) {
            @unlink($path);
        });
    }

    private function php_language_file_list()
    {
        $fileList = [];
        foreach (Config::get('system.translated_applications') as $application => $languages) {
            foreach ($languages as $language) {
                $fileList[$this->get_php_language_file_path($application, $language)] = $this->get_php_language_file_content($language);
            }
        }

        return new Collection($fileList);
    }

    private function get_php_language_file_path($application, $language)
    {
        return Config::get('system.paths.root') . "/cache/{$application}/{$language}.php";
    }

    private function get_php_language_file_content($language)
    {
        $languageResponse = ApiCall::call(
            'system_api',
            'language_api',
            [
                'system' => 'LanguageFiles',
                'action' => 'getLanguageFile',
            ],
            ['language' => $language]
        );

        return $languageResponse['data'];
    }

    private function xml_language_file_list()
    {
        $fileList = [];
        $languages = ApiCall::call(
            'system_api',
            'language_api',
            [
                'system' => 'LanguageFiles',
                'action' => 'getAppletLanguages',
            ],
            ['applet' => 'JSM2_MemberApplet']
        );
        foreach ($languages['data'] as $language) {
            $fileList[$this->get_xml_language_file_path($language)] = $this->get_xml_language_file_content($language);
        }

        return new Collection($fileList);
    }

    private function get_xml_language_file_path($language)
    {
        return Config::get('system.paths.root') . '/cache/flash/lang_' . $language . '.xml';
    }

    private function get_xml_language_file_content($language)
    {
        $languageResponse = ApiCall::call(
            'system_api',
            'language_api',
            [
                'system' => 'LanguageFiles',
                'action' => 'getAppletLanguageFile',
            ],
            [
                'applet'   => 'JSM2_MemberApplet',
                'language' => $language,
            ]
        );

        return $languageResponse['data'];
    }

    public function tearDown()
    {
        $this->delete_language_files();
        $this->delete_xml_language_files();
    }
}