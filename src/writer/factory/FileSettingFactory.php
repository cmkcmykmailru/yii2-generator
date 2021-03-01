<?php

namespace grigor\generator\writer\factory;

use grigor\generator\entities\FileSetting;
use grigor\generator\entities\Setting;
use grigor\generator\exceptions\InvalidConfigurationException;
use grigor\generator\scanner\visitor\RuleExtractor;
use grigor\generator\scanner\visitor\ServiceExtractor;
use grigor\generator\writer\SettingDto;
use Ramsey\Uuid\Uuid;

class FileSettingFactory implements SettingFactory
{

    private $rule;
    private $setting;
    private $path;

    /**
     * FileSettingFactory constructor.
     * @param $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function fill(SettingDto $dto): void
    {
        $this->clear();
        $uuid = Uuid::uuid4()->toString();

        $serviceSetting = [
            'identity' => $uuid,
            'path' => $this->path,
            'service' => [
                'class' => $dto->className,
                'method' => $dto->methodName
            ]
        ];

        $required = false;
        $rule = [
            'class' => self::RULE_CLASS_NAME,
            'identityService' => $uuid
        ];

        foreach ($dto->classAnnotations as $classAnnotation) {
            if ($classAnnotation instanceof RuleExtractor) {
                if ($required) {
                    throw new InvalidConfigurationException('To many @Route parameter  ' . $dto->className . '::' . $dto->methodName . '();');
                }
                $result = $classAnnotation->extract();

                $required = true;
                $this->rule = array_merge($result, $rule);
            } elseif ($classAnnotation instanceof ServiceExtractor) {
                $setting = $classAnnotation->extract();
                $serviceSetting += $setting;
            }
        }

        if (!$required) {
            throw new InvalidConfigurationException('No @Route parameter was found in ' . $dto->className . '::' . $dto->methodName . '();');
        }

        $this->setting = new FileSetting($serviceSetting);
    }

    private function clear(): void
    {
        $this->setting = null;
        $this->rule = null;
    }

    public function getRule(): array
    {
        return $this->rule;
    }

    public function getSetting(): Setting
    {
        return $this->setting;
    }
}