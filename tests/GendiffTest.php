<?php

namespace Hexlet\Phpunit\Tests;

use PHPUnit\Framework\TestCase;

use function Gendiff\Gendiff\generateDiff;
use function Gendiff\ReadFile\getFixturePath;

class GendiffTest extends TestCase
{
    private $coll;

    public function setUp(): void
    {
        $this->coll = file_get_contents(getFixturePath('expected_nested_file.txt'));
    }
    public function testGendiffJson(): void
    {
        $file1 = getFixturePath('file1.json');
        $file2 = getFixturePath('file2.json');
        $this->assertEquals($this->coll, generateDiff($file1, $file2));
    }
    public function testGendiffYaml(): void
    {
        $file1 = getFixturePath('file1.yaml');
        $file2 = getFixturePath('file2.yml');
        $this->assertEquals($this->coll, generateDiff($file1, $file2));
    }
    public function testGendiffPlain(): void
    {
        $file1 = getFixturePath('file1.yaml');
        $file2 = getFixturePath('file2.yml');
        $this->coll = file_get_contents(getFixturePath('expected_plain_file.txt'));
        $this->assertEquals($this->coll, generateDiff($file1, $file2, 'plain'));
    }
}
