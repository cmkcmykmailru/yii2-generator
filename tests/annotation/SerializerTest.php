<?php

namespace grigor\tests\annotation;

use grigor\generator\exceptions\InvalidConfigurationException;
use PHPUnit\Framework\TestCase;

class SerializerTest extends TestCase
{
    public function testConstruct()
    {
        $values = ['value' => 'string'];
        $context = new \grigor\generator\annotation\Serializer($values);
        self::assertEquals('string', $context->value);
    }

    public function testConstructBadTypeExeption()
    {
        $this->expectException(InvalidConfigurationException::class);
        $values = ['value' => 1];
        new \grigor\generator\annotation\Serializer($values);
    }

    public function testConstructBadEmptyExeption()
    {
        $this->expectException(InvalidConfigurationException::class);
        $values = [];
        new \grigor\generator\annotation\Serializer($values);
    }

    public function testConstructBadEmpty2Exeption()
    {
        $this->expectException(InvalidConfigurationException::class);
        $values = ['value' => ''];
        new \grigor\generator\annotation\Serializer($values);
    }

    public function testExport()
    {
        $values = ['value' => 'string'];
        $context = new \grigor\generator\annotation\Serializer($values);
        $export = $context->extract();
        self::assertArrayHasKey('serializer', $export);
        self::assertEquals('string', $export['serializer']);
        self::assertCount(1, $export);
    }
}
