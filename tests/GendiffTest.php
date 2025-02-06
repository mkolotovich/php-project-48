<?php

namespace Hexlet\Phpunit\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;
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
        $this->assertEquals($this->coll, genDiff($file1, $file2));
    }
    public function testGendiffYaml(): void
    {
        $file1 = getFixturePath('file1.yaml');
        $file2 = getFixturePath('file2.yml');
        $this->assertEquals($this->coll, genDiff($file1, $file2));
    }
    public function testGendiffPlain(): void
    {
        $file1 = getFixturePath('file1.json');
        $file2 = getFixturePath('file2.json');
        $this->coll = file_get_contents(getFixturePath('expected_plain_file.txt'));
        $this->assertEquals($this->coll, genDiff($file1, $file2, 'plain'));
    }
    public function testGendiffJsonFormatter(): void
    {
        $file1 = getFixturePath('file1.json');
        $file2 = getFixturePath('file2.json');
        $this->coll = file_get_contents(getFixturePath('expected_json_file.json'));
        $this->assertEquals($this->coll, genDiff($file1, $file2, 'json'));
    }
}
