<?php

namespace grigor\generator\writer;

interface SettingsManager
{
    public function flush(): void;

    public function addSetting(SettingDto $dto): void;
}