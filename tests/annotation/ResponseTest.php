<?php

namespace grigor\tests\annotation;

use grigor\generator\exceptions\InvalidConfigurationException;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    public function testConstruct()
    {
        $values = ['statusCode' => "200"];
        $context = new \grigor\generator\annotation\Response($values);
        self::assertEquals("200", $context->statusCode);
    }

    public function testConstructBadTypeExeption()
    {
        $this->expectException(InvalidConfigurationException::class);
        $values = ['statusCode' => 1];
        new \grigor\generator\annotation\Response($values);
    }

    public function testConstructBadEmptyExeption()
    {
        $this->expectException(InvalidConfigurationException::class);
        $values = [];
        new \grigor\generator\annotation\Response($values);
    }

    public function testConstructBadEmpty2Exeption()
    {
        $this->expectException(InvalidConfigurationException::class);
        $values = ['statusCode' => ''];
        new \grigor\generator\annotation\Response($values);
    }

    public function testExport()
    {
        $values = ['statusCode' => "201"];
        $context = new \grigor\generator\annotation\Response($values);
        $export = $context->extract();
        self::assertArrayHasKey('response', $export);
        self::assertEquals(201, $export['response']);
        self::assertIsInt($export['response']);
        self::assertCount(1, $export);
    }
}
