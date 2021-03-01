<?php

namespace grigor\generator\scanner\visitor;

use Scanner\Driver\Parser\NodeFactory;
use Scanner\Strategy\AbstractScanStrategy;
use Scanner\Strategy\ScanVisitor;

class Visitor implements ScanVisitor
{

    public function scanStarted(AbstractScanStrategy $scanStrategy, $detect): void
    {

    }

    public function scanCompleted(AbstractScanStrategy $scanStrategy, $detect): void
    {

    }

    public function visitLeaf(AbstractScanStrategy $scanStrategy, NodeFactory $factory, $detect, $found, $data = null): void
    {

    }

    public function visitNode(AbstractScanStrategy $scanStrategy, NodeFactory $factory, $detect, $found, $data = null): void
    {

    }
}