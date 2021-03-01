<?php

namespace grigor\generator\repository;

use grigor\generator\entities\Setting;

class DefaultSettingRepository implements SettingsRepository
{

    public function save(Setting $setting): void
    {
        if (!$setting->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

}