<?php

namespace grigor\generator\annotation;

use grigor\generator\exceptions\InvalidConfigurationException;

/**
 * @Annotation
 * @Target("METHOD")
 */
final class Route implements RuleExporter
{
    /** @var string */
    public $url;
    /** @var array */
    public $methods;
    /** @var string */
    public $alias;

    public function __construct(array $values)
    {
        if (empty($values)) {
            throw new InvalidConfigurationException('The Route parameter is required. It must contain three fields: url, method and alias.');
        }
        if (!isset($values['url'])) {
            throw new InvalidConfigurationException('The url parameter was not found.');
        }
        if (!isset($values['methods'])) {
            throw new InvalidConfigurationException('The methods parameter was not found.');
        }
        if (!isset($values['alias'])) {
            throw new InvalidConfigurationException('The alias parameter was not found.');
        }
        if (!is_array($values['methods'])) {
            throw new InvalidConfigurationException('The value "methods" must be a array.');
        }
        if (!is_string($values['alias'])) {
            throw new InvalidConfigurationException('The value "alias" must be a string.');
        }
        if (!is_string($values['url'])) {
            throw new InvalidConfigurationException('The value "url" must be a string.');
        }
        $this->url = $values['url'];
        $this->methods = $values['methods'];
        $this->alias = $values['alias'];
    }

    public function export(): array
    {
        return [
            'pattern' => $this->url,
            'verb' => $this->methods,
            'alias' => $this->alias
        ];
    }
}