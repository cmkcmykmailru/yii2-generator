<?php

namespace grigor\generator\scanner\handler\detector;

use ReflectionClass;
use ReflectionException;
use Scanner\Driver\Parser\NodeFactory;
use Scanner\Strategy\TargetHandler;

class AnnotationDetector implements TargetHandler
{
    private const ANNOTATIONS_MAP = [
        'grigor\generator\annotation',
        'grigor\generator\annotation\Context',
        'grigor\generator\annotation\Permission',
        'grigor\generator\annotation\Response',
        'grigor\generator\annotation\Route',
        'grigor\generator\annotation\Serializer',
    ];
    public $counter = 0;

    public function handle(NodeFactory $factory, $detect, $found)
    {
        $this->counter++;
        $phpFile = $factory->createLeaf($detect, $found);
        $info = $phpFile->getClassInfo();
        if (empty($info)) {
            $phpFile->revokeAllSupports();
            return null;
        }
        if (!isset($info['use']) || !isset($info['class'])) {
            $phpFile->revokeAllSupports();
            return null;
        }
        $uses = $info['use'];
        foreach ($uses as $use) {
            if (in_array(ltrim($use, '\\'), self::ANNOTATIONS_MAP, true)) {
                $phpFile->revokeAllSupports();
                try {
                    return new ReflectionClass($info['class']);
                } catch (ReflectionException $e) {
                }
            }
        }
        $phpFile->revokeAllSupports();
        return null;
    }

}