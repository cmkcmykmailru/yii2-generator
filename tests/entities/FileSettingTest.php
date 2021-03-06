<?php

namespace grigor\tests\entities;

use grigor\generator\entities\FileSetting;
use PHPUnit\Framework\TestCase;
use yii\helpers\FileHelper;

class FileSettingTest extends TestCase
{
    public const path = __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR;

    public function testExport()
    {
        $normal = $this->getMockData('normal');
        $fileSetting = new FileSetting($normal);
        $fileSetting->save();
        self::assertFileExists($fileSetting->path);
        $array = $this->getFile($fileSetting);

        self::assertArrayHasKey('service', $array);
        self::assertIsArray($array['service']);

        self::assertArrayHasKey('class', $array['service']);
        self::assertEquals('app\controllers\FakeService', $array['service']['class']);

        self::assertArrayHasKey('method', $array['service']);
        self::assertEquals('method1', $array['service']['method']);

        self::assertArrayHasKey('permissions', $array);
        self::assertEquals('admin', $array['permissions'][0]);

        self::assertArrayHasKey('serializer', $array);
        self::assertEquals('serializer', $array['serializer']);

        self::assertArrayHasKey('response', $array);
        self::assertEquals(200, $array['response']);

        self::assertArrayHasKey('context', $array);
        self::assertEquals('app\controllers\Fake2ActionContext', $array['context']);
    }

    public function setUp(): void
    {
        FileHelper::createDirectory(FileSettingTest::path);
    }

    protected function getFile($fileSetting)
    {
        return include $fileSetting->path . $fileSetting->identity . '.php';
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        FileHelper::removeDirectory(FileSettingTest::path);
    }

    protected function getMockData(string $name): array
    {
        $path = FileSettingTest::path;
        return require __DIR__ . DIRECTORY_SEPARATOR . 'fakeData' . DIRECTORY_SEPARATOR . $name . '.php';
    }


}