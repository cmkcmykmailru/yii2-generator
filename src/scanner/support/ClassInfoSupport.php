<?php

namespace grigor\generator\scanner\support;

use Scanner\Driver\Component;
use Scanner\Driver\Support\AbstractSupport;
use Scanner\Driver\Support\Support;
use function token_get_all;

class ClassInfoSupport extends AbstractSupport
{

    private static $self = null;
    private $parser;

    /**
     * ClassInfoSupport constructor.
     */
    public function __construct()
    {
        $this->parser = new AnnotationFeatureParser();
    }

    protected function installMethods(Component $component): void
    {
        $this->assignMethod($component, 'getClassInfo');
    }

    protected function uninstallMethods(Component $component): void
    {
        $this->revokeMethod($component, 'getClassInfo');
    }

    protected function checkArguments($method, $arguments): bool
    {
        return empty($arguments);
    }

    public static function create(Component $component): Support
    {
        if (static::$self === null) {
            static::$self = new self();
            return static::$self;
        }
        return static::$self;
    }

    public function getClassInfo(Component $component): array
    {
        $path = $component->getSource();
        $buffer = file_get_contents($path);
        $tokens = token_get_all($buffer);
        return $this->parser->parse($tokens);
    }

}