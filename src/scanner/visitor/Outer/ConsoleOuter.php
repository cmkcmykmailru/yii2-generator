<?php

namespace grigor\generator\scanner\visitor\Outer;

class ConsoleOuter implements Outer
{
    private $counter = 0;
    private $preCount;

    public function infinityProgress(string $data): void
    {
        $data .= "	\r";
        $this->progress($data);
    }

    public function settingFound(array $classAnnotations, string $className, string $methodName): void
    {
        $this->clear();
        $data = "\033[1;33m" . 'Найден метод:' . "\033[0;32m" . ' ' . $className . '::' . $methodName . '(...);' . "\033[0m" . PHP_EOL;
        $this->out($data);
        $this->render($classAnnotations);
    }

    public function rootStart(string $data): void
    {
        $this->clear();
        $data = "\033[1;33m" . 'Начал сканировать директорию:' . "\033[0;32m" . ' ' . $data . "\033[0m" . PHP_EOL;
        $this->out($data);
    }

    public function rootComplete(): void
    {
        $this->clear();
        $data = "\033[1;33m" . 'Завершил.' . "\033[0m" . PHP_EOL. PHP_EOL;
        $this->out($data);
    }

    private function render($classAnnotations): void
    {
        foreach ($classAnnotations as $classAnnotation) {
            $extract = $classAnnotation->extract();

            if (isset($extract['pattern'], $extract['verb'])) {
                $textSetting = "              Route:       " . "\033[0;31m" . $extract['pattern'] . "\033[0m" . PHP_EOL;
                $textSetting .= "              Method:      " . "\033[0;31m" . implode(', ', $extract['verb']) . "\033[0m";
                $this->out($textSetting . PHP_EOL);
            }
            if (isset($extract['response'])) {
                $textSetting = "              Response:    " . "\033[0;31m" . $extract['response'] . "\033[0m";
                $this->out($textSetting . PHP_EOL);
            }
            if (isset($extract['permissions'])) {
                $textSetting = "              Permissions: " . "\033[0;31m" . implode(', ', $extract['permissions']) . "\033[0m";
                $this->out($textSetting . PHP_EOL);
            }
            if (isset($extract['context'])) {
                $textSetting = "              Context:     " . "\033[0;31m" . $extract['context'] . "\033[0m";
                $this->out($textSetting . PHP_EOL);
            }
            if (isset($extract['serializer'])) {
                $textSetting = "              Serializer:  " . "\033[0;31m" . $extract['serializer'] . "\033[0m";
                $this->out($textSetting . PHP_EOL);
            }
        }
        $this->out(PHP_EOL);
    }

    public function completed(int $counter, float $resultTime, int $countMethods, int $countFiles): void
    {
        $this->clear();
        $this->out(
            "\033[0;36m" . 'Целевых файлов:............' . $counter . ' шт.' . "\033[0m" . PHP_EOL
            . "\033[0;36m" . 'Методов:...................' . $countMethods . ' шт.' . "\033[0m" . PHP_EOL
            . "\033[0;36m" . 'Просканировано:............' . $countFiles . ' файлов' . "\033[0m" . PHP_EOL
            . "\033[0;36m" . 'Затрачено времени:.........' . $resultTime . ' сек.' . "\033[0m" . PHP_EOL
        );
    }

    protected function progress($data): void
    {
        if ($this->counter === 20) {
            $this->clear();
            $this->out($data);
            $this->preCount = strlen($data);
            $this->counter = 0;
            return;
        }
        $this->counter++;
    }

    protected function out($data): void
    {
        fwrite(STDOUT, $data);
        $this->preCount = strlen($data);
    }

    protected function clear(): void
    {
        $clear = " ";
        for ($i = 0; $i <= $this->preCount; $i++) {
            $clear .= " ";
        }
        fwrite(STDOUT, "\r" . $clear . "\r");
    }


}