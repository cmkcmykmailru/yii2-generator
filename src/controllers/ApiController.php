<?php

namespace grigor\generator\controllers;

use grigor\generator\scanner\handler\detector\AnnotationDetector;
use grigor\generator\scanner\support\ClassInfoSupport;
use grigor\generator\scanner\visitor\RESTfulBuilder;
use Psr\Container\ContainerInterface;
use Scanner\Driver\File\FilesSearchSettings;
use Scanner\Scanner;
use yii\console\Controller;
use Yii;

class ApiController extends Controller
{
    private $container;

    /**
     * ApiController constructor.
     * @param $container
     */
    public function __construct(
        $id,
        $module,
        ContainerInterface $container,
        $config = []
    )
    {
        $this->container = $container;
        parent::__construct($id, $module, $config);
    }

    public function actionCreate($path)
    {
        echo realpath(Yii::getAlias($path)), PHP_EOL;
        try {
            $settings = new FilesSearchSettings();
            $settings->search(['source' => Yii::getAlias($path)])
                ->filter(['FILE' => ['extension' => 'php']])
                ->support(['FILE' => [ClassInfoSupport::class]])
                ->strategy([
                    'handle' => [
                        'leaf' => [AnnotationDetector::class, 'multiTarget' => true]
                    ]
                ]);

            $scanner = new Scanner([], $this->container);
            $visitor = new RESTfulBuilder();
            $scanner->setScanVisitor($visitor);
            $scanner->search($settings);
        } catch (\yii\base\ErrorException $e) {

        }

    }
}