<?php

namespace grigor\generator\scanner\visitor\Outer;

interface Outer
{
    public function settingFound(array $classAnnotations, string $className, string $methodName): void;

    public function infinityProgress(string $data): void;

    public function completed(int $counter, float $resultTime, int $countMethods, int $countFiles): void;

    public function rootStart(string $data): void;

    public function rootComplete(): void;
}