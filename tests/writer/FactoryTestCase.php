<?php

namespace grigor\tests\writer;

use grigor\generator\writer\SettingDto;
use PHPUnit\Framework\TestCase;

abstract class FactoryTestCase extends TestCase
{

    protected function createMockSettingDto(array $annotations, $className, $method): SettingDto
    {
        $setting = new SettingDto();
        $setting->classAnnotations = $annotations;
        $setting->className = $className;
        $setting->methodName = $method;
        return $setting;
    }

    protected function createContextAnnotation($name = 'api\class')
    {
        $values = ['value' => 'string'];
        return new \grigor\generator\annotation\Context($values);
    }

    protected function createRouteAnnotation($url = 'url', $methods = ['GET'], $alias = 'alias/alias')
    {
        $values = [
            'url' => $url,
            'methods' => $methods,
            'alias' => $alias,
        ];
        return new \grigor\generator\annotation\Route($values);
    }

    protected function createPermissionAnnotation($roles = ['admin', 'user', 'guest'])
    {
        $values = ['value' => $roles];
        return new \grigor\generator\annotation\Permission($values);
    }

    protected function createSerializerAnnotation($name = 'api\Serializer')
    {
        $values = ['value' => $name];
        return new \grigor\generator\annotation\Serializer($values);
    }

    protected function createResponseAnnotation($stausCode = "200")
    {
        $values = ['statusCode' => $stausCode];
        return new \grigor\generator\annotation\Response($values);
    }
}
