<?php

namespace grigor\tests\writer;

use grigor\generator\repository\DefaultSettingRepository;
use grigor\generator\writer\DefaultSettingsManager;
use grigor\generator\writer\factory\FileSettingFactory;
use yii\helpers\FileHelper;

abstract class SettingsManagerTestCase extends FactoryTestCase
{
    protected const DATA_PATH = __DIR__ . DIRECTORY_SEPARATOR . 'data';
    protected const SERVICES_PATH = self::DATA_PATH . DIRECTORY_SEPARATOR . 'services';
    protected const RULES_PATH = self::DATA_PATH . DIRECTORY_SEPARATOR . 'rules.php';

    protected function setUp(): void
    {
        FileHelper::createDirectory(self::SERVICES_PATH);
        $manager = $this->createMockSettingsManager();
        $manager->addSetting($this->createMockSettingDto(
            [
                $this->createPermissionAnnotation(),
                $this->createRouteAnnotation(),
                $this->createSerializerAnnotation(),
                $this->createResponseAnnotation(),
                $this->createContextAnnotation()
            ], 'TestClass', 'func')
        );
        $manager->addSetting($this->createMockSettingDto(
            [
                $this->createPermissionAnnotation(),
                $this->createRouteAnnotation(),
                $this->createSerializerAnnotation(),
                $this->createResponseAnnotation(),
                $this->createContextAnnotation()
            ], 'TestClass2', 'func2')
        );
        $manager->flush();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        FileHelper::removeDirectory(self::DATA_PATH);
    }

    protected function createMockSettingsManager()
    {
        return new DefaultSettingsManager(
            new FileSettingFactory(self::SERVICES_PATH . DIRECTORY_SEPARATOR),
            new DefaultSettingRepository(),
            self::RULES_PATH
        );
    }

    protected function getRules()
    {
        return include self::RULES_PATH;
    }

    protected function getService($file)
    {
        return include $file;
    }
}