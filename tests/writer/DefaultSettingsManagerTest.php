<?php

namespace grigor\tests\writer;

class DefaultSettingsManagerTest extends SettingsManagerTestCase
{

    public function testFlush()
    {

        self::assertFileExists(self::RULES_PATH);
        $rules = $this->getRules();
        self::assertIsArray($rules);
        self::assertCount(2, $rules);
        $services = [];
        foreach ($rules as $rule) {
            $id = $rule['identityService'];
            $file = self::SERVICES_PATH . DIRECTORY_SEPARATOR . $id . '.php';
            self::assertFileExists($file);
            $service = $this->getService($file);
            self::assertIsArray($service);
            $services[] = $service;
        }
        self::assertEquals(count($rules), count($services));
    }

    public function testFlushRules()
    {
        $rules = $this->getRules();
        $url = 'url';
        $methods = 'GET';
        $alias = 'alias/alias';
        $id = [];
        foreach ($rules as $key => $rule) {
            self::assertArrayHasKey('pattern', $rule);

            self::assertEquals($url, $rule['pattern']);

            self::assertArrayHasKey('verb', $rule);
            self::assertEquals($methods, $rule['verb'][0]);

            self::assertArrayHasKey('alias', $rule);
            self::assertEquals($alias, $rule['alias']);

            self::assertArrayHasKey('class', $rule);
            self::assertEquals('grigor\rest\urls\ServiceRule', $rule['class']);

            self::assertArrayHasKey('identityService', $rule);
            $id[] = $rule['identityService'];
        }
        self::assertNotEquals($id[0],$id[1]);
    }

}
