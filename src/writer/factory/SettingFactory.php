<?php

namespace grigor\generator\writer\factory;

use grigor\generator\entities\Setting;
use grigor\generator\writer\SettingDto;

interface SettingFactory
{
    public const RULE_CLASS_NAME = 'grigor\rest\urls\ServiceRule';

    public function fill(SettingDto $dto): void;

    public function getRule(): array;

    public function getSetting(): Setting;
}