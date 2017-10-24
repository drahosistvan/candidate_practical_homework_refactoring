<?php
use Language\ApiCall;
use Language\Config;
use Language\LanguageBatchBo;

class LanguageBatchBoTest extends PHPUnit_Framework_TestCase
{
    private $language;

    public function setUp()
    {
        $this->language = new LanguageBatchBo();
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

        foreach ($this->php_language_file_list() as $languageFile) {
            $this->assertFileExists($languageFile);
        }
    }

    /** @test */
    public function it_generates_xml_language_files()
    {
        $this->delete_xml_language_files();
        $this->language->generateAppletLanguageXmlFiles();

        foreach ($this->xml_language_file_list() as $languageFile) {
            $this->assertFileExists($languageFile);
        }
    }

    private function delete_language_files()
    {
        foreach ($this->php_language_file_list() as $languageFile) {
            @unlink($languageFile);
        }
    }

    private function delete_xml_language_files()
    {
        foreach ($this->xml_language_file_list() as $languageFile) {
            @unlink($languageFile);
        }
    }

    private function php_language_file_list()
    {
        $fileList = [];
        foreach (Config::get('system.translated_applications') as $application => $languages) {
            foreach ($languages as $language) {
                $fileList[] = Config::get('system.paths.root') . "/cache/{$application}/{$language}.php";
            }
        }

        return $fileList;
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
            $fileList[] = (Config::get('system.paths.root') . '/cache/flash/lang_' . $language . '.xml');
        }

        return $fileList;
    }
}