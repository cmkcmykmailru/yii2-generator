<?php

namespace grigor\generator\services;

use grigor\generator\forms\DevDirectoriesDto;
use grigor\generator\forms\PathDto;
use grigor\generator\scanner\visitor\Outer\Outer;
use yii\base\Component;

class EventProxyService extends Component implements Service
{
    public const EVENT_BEFORE_SCAN_DIRECTORY = 'beforeScanDirectory';
    public const EVENT_AFTER_SCAN_DIRECTORY = 'eventAfterScanDirectory';

    private $realService;

    /**
     * EventProxy constructor.
     * @param $realService
     */
    public function __construct(Service $realService, $config = [])
    {
        $this->realService = $realService;
        parent::__construct($config);
    }

    public function scanDirectory(PathDto $dto, Outer $outer = null): void
    {
        $this->trigger(self::EVENT_BEFORE_SCAN_DIRECTORY);
        $this->realService->scanDirectory($dto, $outer);
        $this->trigger(self::EVENT_AFTER_SCAN_DIRECTORY);
    }

    public function scanDevDirectories(DevDirectoriesDto $dto, Outer $outer = null): void
    {
        $this->trigger(self::EVENT_BEFORE_SCAN_DIRECTORY);
        $this->realService->scanDevDirectories($dto, $outer);
        $this->trigger(self::EVENT_AFTER_SCAN_DIRECTORY);
    }
}