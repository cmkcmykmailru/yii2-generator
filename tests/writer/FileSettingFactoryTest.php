<?php

namespace grigor\tests\writer;

use grigor\generator\entities\FileSetting;
use grigor\generator\exceptions\InvalidConfigurationException;
use grigor\generator\writer\factory\FileSettingFactory;

class FileSettingFactoryTest extends FactoryTestCase
{

    public function testFill()
    {
        $factory = new FileSettingFactory(__DIR__);
        $dto = $this->createMockSettingDto([
            $this->createPermissionAnnotation(),
            $this->createRouteAnnotation(),
            $this->createSerializerAnnotation(),
            $this->createResponseAnnotation(),
            $this->createContextAnnotation()
        ], 'TestClass', 'func');
        $factory->fill($dto);

        $setting = $factory->getSetting();
        self::assertInstanceOf(FileSetting::class, $setting);
        self::assertIsArray($factory->getRule());
    }

    public function testFillExceptionTooMany()
    {
        $this->expectException(InvalidConfigurationException::class);
        $factory = new FileSettingFactory(__DIR__);
        $dto = $this->createMockSettingDto([
            $this->createPermissionAnnotation(),
            $this->createRouteAnnotation(),
            $this->createRouteAnnotation(),
            $this->createSerializerAnnotation(),
            $this->createResponseAnnotation(),
            $this->createContextAnnotation()
        ], 'TestClass', 'func');
        $factory->fill($dto);
    }

    public function testFillExceptionEmptyRoute()
    {
        $this->expectException(InvalidConfigurationException::class);
        $factory = new FileSettingFactory(__DIR__);
        $dto = $this->createMockSettingDto([
            $this->createPermissionAnnotation(),
            $this->createSerializerAnnotation(),
            $this->createResponseAnnotation(),
            $this->createContextAnnotation()
        ], 'TestClass', 'func');
        $factory->fill($dto);
    }

    public function testGetRule()
    {
        $factory = new FileSettingFactory(__DIR__);
        $dto = $this->createMockSettingDto([
            $this->createPermissionAnnotation(),
            $this->createRouteAnnotation(),
            $this->createSerializerAnnotation(),
            $this->createResponseAnnotation(),
            $this->createContextAnnotation()
        ], 'TestClass', 'func');
        $factory->fill($dto);

        $rule = $factory->getRule();
        self::assertCount(5, $rule);
        self::assertArrayHasKey('pattern', $rule);
        self::assertArrayHasKey('verb', $rule);
        self::assertArrayHasKey('alias', $rule);
        self::assertArrayHasKey('class', $rule);
        self::assertArrayHasKey('identityService', $rule);

        self::assertEquals("url", $rule['pattern']);
        self::assertEquals('GET', $rule['verb'][0]);
        self::assertEquals("alias/alias", $rule['alias']);
        self::assertEquals('grigor\rest\urls\ServiceRule', $rule['class']);

        self::assertIsString($rule['identityService']);
        self::assertEquals(36, strlen($rule['identityService']));
    }

    public function testGetSetting()
    {
        $factory = new FileSettingFactory(__DIR__);
        $dto = $this->createMockSettingDto([
            $this->createPermissionAnnotation(),
            $this->createRouteAnnotation(),
            $this->createSerializerAnnotation(),
            $this->createResponseAnnotation(),
            $this->createContextAnnotation()
        ], 'TestClass', 'func');
        $factory->fill($dto);

        $setting = $factory->getSetting();
        $rule = $factory->getRule();

        self::assertIsArray($setting->service);
        self::assertArrayHasKey('class', $setting->service);
        self::assertArrayHasKey('method', $setting->service);
        self::assertEquals('TestClass', $setting->service['class']);
        self::assertEquals('func', $setting->service['method']);

        self::assertIsString($setting->context);
        self::assertEquals('string', $setting->context);

        self::assertIsInt($setting->response);
        self::assertEquals(200, $setting->response);

        self::assertIsString($setting->serializer);
        self::assertEquals('api\Serializer', $setting->serializer);

        self::assertIsArray($setting->permissions);
        self::assertContains("admin", $setting->permissions);
        self::assertContains("user", $setting->permissions);
        self::assertContains("guest", $setting->permissions);

        self::assertIsString($setting->path);
        self::assertEquals(__DIR__, $setting->path);

        self::assertIsString( $setting->identity);
        self::assertEquals($rule['identityService'],$setting->identity);
    }

}
