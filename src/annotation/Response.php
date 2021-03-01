<?php


namespace grigor\generator\annotation;

use grigor\generator\exceptions\InvalidConfigurationException;
use grigor\generator\scanner\visitor\ServiceExtractor;

/**
 * @Annotation
 * @Target("METHOD")
 */
final class Response implements ServiceExtractor
{
    /** @var string */
    public $statusCode;

    public function __construct(array $values)
    {
        if (!isset($values['statusCode']) || !is_string($values['statusCode'])) {
            throw new InvalidConfigurationException('The value "statusCode" must be a string.');
        }
        if (empty($values['statusCode'])) {
            throw new InvalidConfigurationException('The value "statusCode" must not be empty.');
        }
        $this->statusCode = $values['statusCode'];
    }

    public function extract(): array
    {
        return [
            'response' => (int)$this->statusCode
        ];
    }
}