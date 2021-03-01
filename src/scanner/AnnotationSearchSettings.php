<?php

namespace grigor\generator\scanner;

use grigor\generator\scanner\handler\detector\AnnotationDetector;
use grigor\generator\scanner\support\ClassInfoSupport;
use Scanner\Driver\File\FilesSearchSettings;

class AnnotationSearchSettings extends FilesSearchSettings
{

    /**
     * AnnotationSearchSettings constructor.
     */
    public function __construct()
    {
        $this->filter(['FILE' => ['extension' => 'php']])
            ->support(['FILE' => [ClassInfoSupport::class]])
            ->strategy([
                'handle' => [
                    'leaf' => [AnnotationDetector::class, 'multiTarget' => true]
                ]
            ]);
    }

    public function setPath($path)
    {
        $this->search(['source' => $path]);
    }

}