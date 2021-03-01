<?php

namespace grigor\generator\services;

use grigor\generator\forms\DevDirectoriesDto;
use grigor\generator\forms\PathDto;
use grigor\generator\scanner\visitor\Outer\Outer;

interface Service
{
    public function scanDirectory(PathDto $dto, Outer $outer = null): void;

    public function scanDevDirectories(DevDirectoriesDto $dto, Outer $outer = null): void;
}