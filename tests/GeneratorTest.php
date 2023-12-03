<?php

namespace Simply\Maker\Test;

use PHPUnit\Framework\TestCase;
use Simply\Maker\FileManager;
use Simply\Maker\Generator;

class GeneratorTest extends TestCase
{
    private Generator $generator;

    public function setUp(): void
    {
        $fileManager = $this->createMock(FileManager::class);
        $this->generator = new Generator($fileManager);
    }

    public function testCreateClassNameDetail(): void
    {
        $fullClassName = 'ClementCore\Test\TestClass';
        $classNameDetail = $this->generator->createClassNameDetails($fullClassName);
        $this->assertEquals('ClementCore\Test\TestClass', $classNameDetail->getFullName());
        $this->assertEquals('TestClass', $classNameDetail->getClassName());
    }
}
