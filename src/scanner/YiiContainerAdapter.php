<?php

namespace grigor\generator\scanner;

use Psr\Container\ContainerInterface;
use Yii;

class YiiContainerAdapter implements ContainerInterface
{
    public function get($id)
    {
        return Yii::$container->get($id);
    }

    public function has($id)
    {
        return Yii::$container->has($id);
    }
}