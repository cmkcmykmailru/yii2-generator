<?php

namespace grigor\tests\annotation;

use grigor\generator\exceptions\InvalidConfigurationException;
use PHPUnit\Framework\TestCase;

class PermissionTest extends TestCase
{

    public function testConstruct()
    {
        $values = ['value' => ['string']];
        $context = new \grigor\generator\annotation\Permission($values);
        self::assertEquals(['string'], $context->value);
    }

    public function testConstructBadTypeExeption()
    {
        $this->expectException(InvalidConfigurationException::class);
        $values = ['value' => 1];
        new \grigor\generator\annotation\Permission($values);
    }

    public function testConstructBadEmptyExeption()
    {
        $this->expectException(InvalidConfigurationException::class);
        $values = [];
        new \grigor\generator\annotation\Permission($values);
    }

    public function testConstructBadEmpty2Exeption()
    {
        $this->expectException(InvalidConfigurationException::class);
        $values = ['value' => []];
        new \grigor\generator\annotation\Permission($values);
    }

    public function testConstructBadEmpty3Exeption()
    {
        $this->expectException(InvalidConfigurationException::class);
        $values = ['value' => [1]];
        new \grigor\generator\annotation\Permission($values);
    }

    public function testExport()
    {
        $values = ['value' => ['string']];
        $context = new \grigor\generator\annotation\Permission($values);
        $export = $context->extract();
        self::assertArrayHasKey('permissions', $export);
        self::assertEquals(['string'], $export['permissions']);
        self::assertCount(1, $export);
    }
}
