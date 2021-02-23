<?php

namespace grigor\generator;

use grigor\generator\scanner\handler\detector\AnnotationDetector;
use grigor\generator\scanner\YiiContainerAdapter;
use grigor\generator\writer\FileSettingsWriter;
use grigor\generator\writer\SettingsWriter;
use Psr\Container\ContainerInterface;
use yii\base\BootstrapInterface;

class GeneratorBootstrap implements BootstrapInterface
{

    public function bootstrap($app)
    {
        $container = \Yii::$container;
        $container->setSingleton(ContainerInterface::class, function () {
            return new YiiContainerAdapter();
        });
        $container->setSingleton(AnnotationDetector::class, AnnotationDetector::class);
        $container->setSingleton(SettingsWriter::class, FileSettingsWriter::class);
    }
}

