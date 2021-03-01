<?php

namespace grigor\generator\services;

use grigor\generator\forms\DevDirectoriesDto;
use grigor\generator\forms\PathDto;
use grigor\generator\scanner\AnnotationSearchSettings;
use grigor\generator\scanner\handler\detector\AnnotationDetector;
use grigor\generator\scanner\visitor\AnnotationVisitor;
use grigor\generator\scanner\visitor\OutAnnotationVisitor;
use grigor\generator\scanner\visitor\Outer\Outer;
use grigor\generator\writer\SettingsManager;
use Scanner\Scanner;

class ApiManageService implements Service
{
    private $scanner;
    private $settingsWriter;
    private $searchSettings;
    private $annotationDetector;

    /**
     * ApiService constructor.
     * @param $scanner
     */
    public function __construct(
        Scanner $scanner,
        SettingsManager $settingsWriter,
        AnnotationSearchSettings $searchSettings,
        AnnotationDetector $annotationDetector
    )
    {
        $this->scanner = $scanner;
        $this->settingsWriter = $settingsWriter;
        $this->searchSettings = $searchSettings;
        $this->annotationDetector = $annotationDetector;
    }

    public function scanDirectory(PathDto $dto, Outer $outer = null): void
    {
        if ($outer !== null) {
            $start = microtime(true);
            $visitor = new OutAnnotationVisitor($this->settingsWriter, $outer, $this->annotationDetector);
        } else {
            $visitor = new AnnotationVisitor($this->settingsWriter);
        }

        $this->searchSettings->setPath($dto->path);

        $this->scanner->setScanVisitor($visitor);
        $this->scanner->search($this->searchSettings);

        if ($outer !== null) {
            $resultTime = (microtime(true) - $start);
            $resultTime = round($resultTime, 3);
            $visitor->outTotal($resultTime);
        }
    }

    public function scanDevDirectories(DevDirectoriesDto $dto, Outer $outer = null): void
    {

        if ($outer !== null) {
            $start = microtime(true);
            $visitor = new OutAnnotationVisitor($this->settingsWriter, $outer, $this->annotationDetector);
        } else {
            $visitor = new AnnotationVisitor($this->settingsWriter);
        }

        $this->scanner->setScanVisitor($visitor);
        $this->scanner->setSearchSettings($this->searchSettings);
        $directories = $dto->directories;
        foreach ($directories as $directory) {
            $this->scanner->searchAgain($directory);
        }

        if ($outer !== null) {
            $resultTime = (microtime(true) - $start);
            $resultTime = round($resultTime, 3);
            $visitor->outTotal($resultTime);
        }
    }
}