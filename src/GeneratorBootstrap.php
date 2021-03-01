<?php

namespace grigor\generator;

use grigor\generator\repository\DefaultSettingRepository;
use grigor\generator\repository\SettingsRepository;
use grigor\generator\scanner\handler\detector\AnnotationDetector;
use grigor\generator\scanner\YiiContainerAdapter;
use grigor\generator\services\ApiManageService;
use grigor\generator\services\EventProxyService;
use grigor\generator\services\Service;
use grigor\generator\writer\DefaultSettingsManager;
use grigor\generator\writer\factory\FileSettingFactory;
use grigor\generator\writer\factory\SettingFactory;
use grigor\generator\writer\SettingsManager;
use Psr\Container\ContainerInterface;
use Scanner\Scanner;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\di\Instance;

class GeneratorBootstrap implements BootstrapInterface
{

    public function bootstrap($app)
    {
        $container = \Yii::$container;
        $servicePath = rtrim(Yii::getAlias(
                Yii::$app->params['serviceDirectoryPath']), DIRECTORY_SEPARATOR
            ) . DIRECTORY_SEPARATOR;

        $container->setSingleton(ContainerInterface::class, function () {
            return new YiiContainerAdapter();
        });

        $container->setSingleton(AnnotationDetector::class, AnnotationDetector::class);

        $container->setSingleton(FileSettingFactory::class, [], [
            $servicePath
        ]);

        $container->setSingleton(SettingFactory::class, FileSettingFactory::class);


        $container->setSingleton(SettingsRepository::class, DefaultSettingRepository::class);

        $container->setSingleton(DefaultSettingsManager::class, [], [
                Instance::of(SettingFactory::class),
                Instance::of(SettingsRepository::class),
                Yii::getAlias(Yii::$app->params['rulesPath']),
            ]
        );

        $container->setSingleton(SettingsManager::class, DefaultSettingsManager::class);


        $container->setSingleton(Scanner::class, [], [
            [],
            Instance::of(ContainerInterface::class)
        ]);

        $container->setSingleton(ApiManageService::class, ApiManageService::class);

        $container->setSingleton(EventProxyService::class, [], [
            Instance::of(ApiManageService::class)
        ]);

        $container->setSingleton(Service::class, EventProxyService::class);

        Event::on(EventProxyService::class, EventProxyService::EVENT_BEFORE_SCAN_DIRECTORY, [$this, 'cleanServiceDirectory']);
    }

    public function cleanServiceDirectory()
    {
        $folder = Yii::getAlias(Yii::$app->params['serviceDirectoryPath']);
        $files = glob($folder . "/*");
        foreach ($files as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
    }
}

