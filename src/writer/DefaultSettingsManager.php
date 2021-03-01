<?php

namespace grigor\generator\writer;

use grigor\generator\repository\SettingsRepository;
use grigor\generator\writer\factory\SettingFactory;

class DefaultSettingsManager implements SettingsManager
{
    public $rulesPath;
    protected $rules = [];
    protected $settings = [];
    protected $factory;
    protected $repository;

    /**
     * DefaultSettingsManager constructor.
     */
    public function __construct(
        SettingFactory $factory,
        SettingsRepository $repository,
        string $rulesPath
    )
    {
        $this->factory = $factory;
        $this->repository = $repository;
        $this->rulesPath = $rulesPath;
    }

    public function flush(): void
    {
        if (empty($this->settings)) {
            return;
        }

        foreach ($this->settings as $dto) {
            $this->factory->fill($dto);
            $this->rules[] = $this->factory->getRule();
            $setting = $this->factory->getSetting();
            $this->repository->save($setting);
        }

        $code = $this->rulesToCode($this->rules);
        file_put_contents($this->rulesPath, $code);
        $this->settings = [];
        $this->rules = [];
    }

    private function rulesToCode(array $rules): string
    {
        $text = "<?php return [";
        foreach ($rules as $key => $rule) {
            $text .= $key . " => [";
            $text .= $this->ruleToCode($rule);
            $text .= "],";
        }
        return $text . '];';
    }

    private function ruleToCode($items): string
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

    public function addSetting(SettingDto $dto): void
    {
        $this->settings[] = $dto;
    }

}