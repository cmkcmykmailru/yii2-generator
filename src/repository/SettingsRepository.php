<?php

namespace grigor\generator\repository;

use grigor\generator\entities\Setting;

interface SettingsRepository
{
    public function save(Setting $setting): void;

}