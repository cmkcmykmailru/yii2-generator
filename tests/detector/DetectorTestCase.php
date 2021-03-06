<?php

namespace grigor\tests\detector;

use PHPUnit\Framework\TestCase;
use yii\helpers\FileHelper;

abstract class DetectorTestCase extends TestCase
{
    protected const DATA = __DIR__ . DIRECTORY_SEPARATOR . 'data';

    public function setUp(): void
    {
        FileHelper::createDirectory(self::DATA);

        $uses = [
            'use grigor\generator\annotation as API;',
            'use grigor\generator\annotation\Context;',
            'use grigor\generator\annotation\Permission;',
            'use grigor\generator\annotation\Response;',
            'use grigor\generator\annotation\Route;',
            'use grigor\generator\annotation\Serializer;',
        ];
        $name = 'Normal';
        $class = $this->createClassString($uses, $name);

        $this->fputs($class, $name);


        $uses = [
            'use api\forms\Form;',
            'use Ramsey\Uuid\Uuid;',
            'use yii\base\InvalidConfigException;',
            'use yii\data\ActiveDataProvider;',
            'use yii\data\DataProviderInterface;',
            'use grigor\generator\annotation as API;',
            'use grigor\generator\annotation\Context;',
            'use grigor\generator\annotation\Permission;',
            'use grigor\generator\annotation\Response;',
            'use grigor\generator\annotation\Route;',
            'use grigor\generator\annotation\Serializer;',
        ];
        $name = 'Normal2';
        $class = $this->createClassString($uses, $name);
        $this->fputs($class, $name);

        $uses = [];
        $name = 'Bad';
        $class = $this->createClassString($uses, $name);
        $this->fputs($class, $name);

        $uses = ['use api\forms\Form;'];
        $name = 'Bad2';
        $class = $this->createClassString($uses, $name);
        $this->fputs($class, $name);


        $this->fputs('', 'empty');
        $this->fputs('dh rth rtyrty r yr ydy dfjhdfh jdfhjdhjf hjfhj fh f jfghj', 'NoClass');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        FileHelper::removeDirectory(self::DATA);
    }

    protected function fputs($class, $name)
    {
        file_put_contents(self::DATA . DIRECTORY_SEPARATOR . $name . '.php', $class);
    }

    protected function createClassString(array $uses, string $name): string
    {
        $text = '';
        foreach ($uses as $use) {
            $text .= $use . PHP_EOL;
        }
        return '<?php
namespace grigor\tests\detector\data;
              
' . $text . '
              
class ' . $name . '{

}';
    }

}