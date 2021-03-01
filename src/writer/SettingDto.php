<?php

namespace grigor\generator\writer;

class SettingDto extends Dto
{
    public $classAnnotations;
    public $className;
    public $methodName;

    protected function setData(array $classAnnotations, string $className, string $methodName): void
    {
        $this->classAnnotations = $classAnnotations;
        $this->className = $className;
        $this->methodName = $methodName;
    }
}