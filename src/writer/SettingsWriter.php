<?php

namespace grigor\generator\writer;

interface SettingsWriter
{
    public const RULE_CLASS_NAME = 'grigor\rest\urls\ServiceRule';

    public function write(): void;

    public function addSettings($classAnnotations, $className, $methodName): void;
}