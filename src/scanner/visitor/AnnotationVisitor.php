<?php

namespace grigor\generator\scanner\visitor;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use grigor\generator\writer\SettingDto;
use grigor\generator\writer\SettingsManager;
use ReflectionClass;
use Scanner\Driver\Parser\NodeFactory;
use Scanner\Strategy\AbstractScanStrategy;

class AnnotationVisitor extends Visitor
{
    private $reader;
    private $settingsManager;
    private $dtoPrototype;

    /**
     * AnnotationVisitor constructor.
     * @param $settingsManager
     */
    public function __construct(SettingsManager $settingsManager)
    {
        AnnotationRegistry::registerLoader('class_exists');
        $this->reader = new AnnotationReader();
        $this->settingsManager = $settingsManager;
        $this->dtoPrototype = new SettingDto();
    }

    public function scanCompleted(AbstractScanStrategy $scanStrategy, $detect): void
    {
        $this->settingsManager->flush();
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
        $methods = $reflectionClass->getMethods();
        $className = $reflectionClass->getName();
        foreach ($methods as $method) {
            $classAnnotations = $this->reader->getMethodAnnotations($method);
            if (!empty($classAnnotations)) {
                $methodName = $method->getName();
                $this->addSetting($classAnnotations, $className, $methodName);
            }
        }
    }

    protected function addSetting(array $classAnnotations, string $className, string $methodName): void
    {
        $this->settingsManager->addSetting($this->dtoPrototype->cloneWithData($classAnnotations, $className, $methodName));
    }

}