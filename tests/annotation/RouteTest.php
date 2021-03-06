<?php

namespace grigor\tests\annotation;

use grigor\generator\exceptions\InvalidConfigurationException;
use PHPUnit\Framework\TestCase;

class RouteTest extends TestCase
{
    private const values = [
        'url' => 'url',
        'methods' => ['GET'],
        'alias' => 'alias/alias',
    ];
    private const badValues = [
        'url' => 2,
        'methods' => ['GET'],
        'alias' => 'alias/alias',
    ];

    public function testConstruct()
    {
        $values = self::values;

        $context = new \grigor\generator\annotation\Route($values);
        self::assertEquals($values['url'], $context->url);
        self::assertEquals($values['methods'], $context->methods);
        self::assertEquals($values['alias'], $context->alias);
    }

    public function testConstructBadUrlExeption()
    {
        $this->expectException(InvalidConfigurationException::class);
        $values = [
            'url' => 2,
            'methods' => ['GET'],
            'alias' => 'alias/alias',
        ];
        new \grigor\generator\annotation\Route($values);
    }

    public function testConstructBadMethodsExeption()
    {
        $this->expectException(InvalidConfigurationException::class);
        $values = [
            'url' => 'url',
            'methods' => 'GET',
            'alias' => 'alias/alias',
        ];
        new \grigor\generator\annotation\Route($values);
    }

    public function testConstructBadAliasExeption()
    {
        $this->expectException(InvalidConfigurationException::class);
        $values = [
            'url' => 'url',
            'methods' => ['GET'],
            'alias' => 1,
        ];
        new \grigor\generator\annotation\Route($values);
    }

    public function testConstructBadEmptyExeption()
    {
        $this->expectException(InvalidConfigurationException::class);
        $values = [];
        new \grigor\generator\annotation\Route($values);
    }

    public function testExport()
    {
        $values = [
            'url' => 'url',
            'methods' => ['GET'],
            'alias' => 'alias/alias',
        ];
        $route = new \grigor\generator\annotation\Route($values);
        $export = $route->extract();
        self::assertArrayHasKey('pattern', $export);
        self::assertArrayHasKey('verb', $export);
        self::assertArrayHasKey('alias', $export);

        self::assertEquals('url', $export['pattern']);
        self::assertEquals('alias/alias', $export['alias']);
        self::assertEquals(['GET'], $export['verb']);
        self::assertCount(3, $export);
        self::assertCount(1, $export['verb']);
    }

    public function testExportBadCount()
    {
        $values = [
            'url' => 'url',
            'methods' => ['GET'],
            'alias' => 'alias/alias',
            'some' => 'alias/alias',
        ];
        $route = new \grigor\generator\annotation\Route($values);
        $export = $route->extract();
        self::assertArrayHasKey('pattern', $export);
        self::assertArrayHasKey('verb', $export);
        self::assertArrayHasKey('alias', $export);

        self::assertEquals('url', $export['pattern']);
        self::assertEquals('alias/alias', $export['alias']);
        self::assertEquals(['GET'], $export['verb']);
        self::assertCount(3, $export);
        self::assertCount(1, $export['verb']);
    }
}
