<?php

namespace grigor\generator\scanner\visitor;

use grigor\generator\scanner\handler\detector\AnnotationDetector;
use grigor\generator\scanner\visitor\Outer\Outer;
use grigor\generator\writer\SettingsManager;
use ReflectionClass;
use Scanner\Driver\Parser\NodeFactory;
use Scanner\Strategy\AbstractScanStrategy;

class OutAnnotationVisitor extends AnnotationVisitor
{
    private $counter = 0;
    private $outer;
    private $annotationDetector;
    private $methodCounter = 0;

    public function __construct(SettingsManager $settingsManager, Outer $outer, AnnotationDetector $annotationDetector)
    {
        parent::__construct($settingsManager);
        $this->outer = $outer;
        $this->annotationDetector = $annotationDetector;
    }

    public function scanStarted(AbstractScanStrategy $scanStrategy, $detect): void
    {
        $this->outer->rootStart(realpath($detect));
    }

    public function scanCompleted(AbstractScanStrategy $scanStrategy, $detect): void
    {
        parent::scanCompleted($scanStrategy, $detect);
        $this->outer->rootComplete();
    }

    /**
     * @param AbstractScanStrategy $scanStrategy
     * @param NodeFactory $factory
     * @param $detect
     * @param $found
     * @param ReflectionClass $reflectionClass
     */
    public function visitLeaf(AbstractScanStrategy $scanStrategy, NodeFactory $factory, $detect, $found, $reflectionClass = null): void
    {
        $this->counter++;
        parent::visitLeaf($scanStrategy, $factory, $detect, $found, $reflectionClass);
    }

    protected function addSetting(array $classAnnotations, string $className, string $methodName): void
    {
        $this->methodCounter++;
        $this->outer->settingFound($classAnnotations, $className, $methodName);
        parent::addSetting($classAnnotations, $className, $methodName);
    }

    public function visitNode(AbstractScanStrategy $scanStrategy, NodeFactory $factory, $detect, $found, $data = null): void
    {
        $this->outer->infinityProgress($detect . DIRECTORY_SEPARATOR . $found);
    }

    public function outTotal($resultTime)
    {
        $this->outer->completed($this->counter, $resultTime, $this->methodCounter, $this->annotationDetector->counter);
    }
}