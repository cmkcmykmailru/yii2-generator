<?php

namespace grigor\generator\writer;

use grigor\generator\annotation\RuleExporter;
use grigor\generator\annotation\ServiceExporter;
use grigor\generator\exceptions\InvalidConfigurationException;
use Ramsey\Uuid\Uuid;
use yii\base\BaseObject;
use Yii;

class FileSettingsWriter extends BaseObject implements SettingsWriter
{
    public $folderPath;
    public $rulesPath;
    protected $rules;
    protected $settings;

    /**
     * FileSettingsWriter constructor.
     */
    public function __construct($config = [])
    {
        $this->settings = [];
        parent::__construct($config);
    }

    public function write(): void
    {
        $text = $this->exportRules($this->rules);
        file_put_contents(Yii::getAlias($this->rulesPath), $text);
        $path = Yii::getAlias($this->folderPath . DIRECTORY_SEPARATOR);
        $this->cleanFolder($path);
        foreach ($this->settings as $identity => $setting) {
            $textSetting = $this->exportSetting($setting);
            file_put_contents($path . $identity . '.php', $textSetting);
        }
    }

    private function exportRules($rules): string
    {
        $text = "<?php return [";
        foreach ($rules as $key => $rule) {
            $text .= $key . " => [";
            $text .= $this->exportRole($rule);
            $text .= "],";
        }
        return $text . '];';
    }

    private function exportRole($items): string
    {
        $text = "";
        foreach ($items as $key => $item) {
            if ($key === 'verb') {
                if (is_array($item)) {
                    $text .= "'verb' => ['" . implode("', '", $item) . "'],";
                } else {
                    $text .= "'verb' => '" . $item . "',";
                }
            } else {
                $text .= "'" . $key . "' => '" . $item . "',";
            }
        }
        return $text;
    }

    private function exportSetting($settings): string
    {
        $textSetting = "<?php return [";
        foreach ($settings as $key => $setting) {
            if ($key === 'service') {
                $textSetting .= "'service' => [";
                $textSetting .= "'class' => '" . $setting['class'] . "',";
                $textSetting .= "'method' => '" . $setting['method'] . "',";
                $textSetting .= '],';
            } elseif ($key === 'permissions') {
                $textSetting .= "'" . $key . "' => ['" . implode("', '", $setting) . "'],";
            } else {
                if (is_int($setting)) {
                    $textSetting .= "'" . $key . "' => " . $setting . ",";
                } else {
                    $textSetting .= "'" . $key . "' => '" . $setting . "',";
                }
            }
        }
        $textSetting .= "];";
        return $textSetting;
    }

    private function cleanFolder($folder)
    {
        $files = glob($folder . "/*");
        $c = count($files);
        if ($c > 0) {
            foreach ($files as $file) {
                if (file_exists($file)) {
                    unlink($file);
                }
            }
        }
    }

    public function addSettings($classAnnotations, $className, $methodName): void
    {
        $uuid = Uuid::uuid4()->toString();

        $serviceSetting = [
            'service' => [
                'class' => $className,
                'method' => $methodName
            ]
        ];
        $required = false;
        foreach ($classAnnotations as $classAnnotation) {
            if ($classAnnotation instanceof RuleExporter) {
                $result = $classAnnotation->export();

                $rules = [
                    'class' => self::RULE_CLASS_NAME,
                    'identityService' => $uuid
                ];
                $required = true;
                $this->rules[] = array_merge($result, $rules);
            } elseif ($classAnnotation instanceof ServiceExporter) {
                $setting = $classAnnotation->export();
                $serviceSetting = $serviceSetting + $setting;
            }
        }
        if (!$required) {
            throw new InvalidConfigurationException('No @Route parameter was found in ' . $className . '::' . $methodName . '();');
        }

        $this->settings[$uuid] = $serviceSetting;
    }

}