<?php


namespace grigor\generator\annotation;

use grigor\generator\exceptions\InvalidConfigurationException;
use grigor\generator\scanner\visitor\ServiceExtractor;

/**
 * @Annotation
 * @Target("METHOD")
 */
class Permission implements ServiceExtractor
{
    /** @phpstan-var list<string> */
    public $value;

    public function __construct(array $values)
    {
        if (!isset($values['value']) || !is_array($values['value'])) {
            throw new InvalidConfigurationException('The value "permissions" must be a array.');
        }
        if (empty($values['value'])) {
            throw new InvalidConfigurationException('The value "permissions" must not be empty.');
        }
        $this->assertValueString($values['value']);
        $this->value = $values['value'];
    }

    private function assertValueString(array $values): void
    {
        foreach ($values as $value) {
            if (!is_string($value)) {
                throw new InvalidConfigurationException('Permissions.values must be strings.');
            }
        }
    }

    public function extract(): array
    {
        return [
            'permissions' => $this->value
        ];
    }
}