<?php

namespace grigor\generator\writer;

abstract class Dto
{
    public function cloneWithData(array $classAnnotations, string $className, string $methodName): Dto
    {
        $clone = clone $this;
        $clone->setData($classAnnotations, $className, $methodName);

        return $clone;
    }

    abstract protected function setData(array $classAnnotations, string $className, string $methodName): void;
}