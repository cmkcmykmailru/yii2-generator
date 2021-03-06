<?php

namespace grigor\tests\support;

use grigor\generator\scanner\support\AnnotationFeatureParser;
use PHPUnit\Framework\TestCase;

class AnnotationFeatureParserTest extends TestCase
{

    public function testParseContains()
    {
        $parser = new AnnotationFeatureParser();

        $class = $this->createClassString([
            'use grigor\generator\annotation as API;',
            'use grigor\generator\annotation\Context;',
            'use grigor\generator\annotation\Permission;',
            'use grigor\generator\annotation\Response;',
            'use grigor\generator\annotation\Route;',
            'use grigor\generator\annotation\Serializer;',
        ]);

        $array = $parser->parse($tokens = token_get_all($class));

        self::assertArrayHasKey('use', $array);

        self::assertArrayHasKey('class', $array);
        self::assertContains('grigor\generator\annotation', $array['use']);
        self::assertContains('grigor\generator\annotation\Context', $array['use']);
        self::assertContains('grigor\generator\annotation\Permission', $array['use']);
        self::assertContains('grigor\generator\annotation\Response', $array['use']);
        self::assertContains('grigor\generator\annotation\Route', $array['use']);
        self::assertContains('grigor\generator\annotation\Serializer', $array['use']);
        self::assertEquals('grigor\tests\detector\Test', $array['class']);
    }

    public function testParseCount()
    {
        $parser = new AnnotationFeatureParser();

        $class = $this->createClassString([
            'use grigor\generator\annotation as API;',
            'use grigor\generator\annotation\Context;',
            'use grigor\generator\annotation\Permission;',
        ]);

        $array = $parser->parse($tokens = token_get_all($class));
        self::assertCount(2, $array);
        self::assertCount(3, $array['use']);
    }

    public function testParseCountEmpty()
    {
        $parser = new AnnotationFeatureParser();
        $class = $this->createClassString([]);

        $array = $parser->parse($tokens = token_get_all($class));
        self::assertArrayHasKey('class', $array);
        self::assertNotContains('use', $array);
    }

    public function testParseNoPhp()
    {
        $parser = new AnnotationFeatureParser();

        $class = 'sdgnsfgnfhgjdf jjhjfh jdhjfhj h jfhjfhjfhjfhjfhjf';

        $array = $parser->parse($tokens = token_get_all($class));
        self::assertCount(0, $array);
    }

    private function createClassString(array $uses): string
    {
        $text = '';
        foreach ($uses as $use) {
            $text .= $use;
        }
        return '<?php
              namespace grigor\tests\detector;
              
              ' . $text . '
              
              class Test{

              }
       ';
    }
}
