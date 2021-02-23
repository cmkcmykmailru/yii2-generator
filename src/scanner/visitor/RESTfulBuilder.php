<?php

namespace grigor\generator\scanner\visitor;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use grigor\generator\writer\SettingsWriter;
use ReflectionClass;
use Scanner\Driver\Parser\NodeFactory;
use Scanner\Strategy\AbstractScanStrategy;
use Scanner\Strategy\ScanVisitor;

class RESTfulBuilder implements ScanVisitor
{
    private $reader;
    private $start;
    private $counter = 0;
    private $settingsWriter;

    /**
     * RESTfulBuilder constructor.
     * @param $settingsWriter
     */
    public function __construct(SettingsWriter $settingsWriter)
    {
        AnnotationRegistry::registerLoader('class_exists');
        $this->reader = new AnnotationReader();
        $this->settingsWriter = $settingsWriter;
    }

    public function scanStarted(AbstractScanStrategy $scanStrategy, $detect): void
    {
        $this->start = microtime(true);
    }

    public function scanCompleted(AbstractScanStrategy $scanStrategy, $detect): void
    {
        $this->settingsWriter->write();
        echo 'Время выполнения скрипта: ' . (microtime(true) - $this->start) . ' сек.' . PHP_EOL;
        echo 'Обработано файлов: ' . $this->counter . 'шт.' . PHP_EOL;
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
        $methods = $reflectionClass->getMethods();
        $className = $reflectionClass->getName();

        foreach ($methods as $method) {
            $classAnnotations = $this->reader->getMethodAnnotations($method);
            if (!empty($classAnnotations)) {
                $methodName = $method->getName();
                $this->settingsWriter->addSettings($classAnnotations, $className, $methodName);
            }
        }
    }

    public function visitNode(AbstractScanStrategy $scanStrategy, NodeFactory $factory, $detect, $found, $data = null): void
    {

    }
}