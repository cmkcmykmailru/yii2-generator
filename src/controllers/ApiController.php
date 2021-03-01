<?php

namespace grigor\generator\controllers;

use grigor\generator\forms\DevDirectoriesForm;
use grigor\generator\forms\PathForm;
use grigor\generator\scanner\visitor\Outer\ConsoleOuter;
use grigor\generator\services\Service;
use Psr\Container\ContainerInterface;
use yii\console\Controller;
use Yii;

class ApiController extends Controller
{
    private $container;
    private $manageService;
    private $outer;

    /**
     * ApiController constructor.
     * @param $container
     */
    public function __construct(
        $id,
        $module,
        ContainerInterface $container,
        Service $manageService,
        ConsoleOuter $outer,
        $config = []
    )
    {
        $this->container = $container;
        $this->manageService = $manageService;
        $this->outer = $outer;
        parent::__construct($id, $module, $config);
    }

    public function actionCreate($path = null)
    {
        if ($path === null) {
            $path = '.';
        }

        $form = new PathForm(['path' => $path]);

        if ($form->validate()) {
            $this->manageService->scanDirectory($form->exportDto(), $this->outer);
            return 0;
        }
        return 1;
    }

    public function actionDev()
    {
        $directories = Yii::$app->params['devDirectories'];
        $form = new DevDirectoriesForm([
            'paths' => $directories
        ]);
        if ($form->validate()) {
            $this->manageService->scanDevDirectories($form->exportDto(), $this->outer);
            return 0;
        }
        return 1;
    }
}