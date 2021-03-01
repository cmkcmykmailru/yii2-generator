yii2-generator
=====
Генератор настроек rest api на основе аннотаций для расширения [yii2-rest](https://github.com/cmkcmykmailru/yii2-rest).
По сути это утилита которая преобразует аннотации в коде в настройки которые нужны расширению [yii2-rest](https://github.com/cmkcmykmailru/yii2-rest).
Она может использоваться в консоли и отдельно от нее, в зависимости от потребностей.

Установка
------------

Предпочтительный способ установки этого расширения - через [composer](http://getcomposer.org/download/).

Запустите команду

```
php composer.phar require --prefer-dist grigor/yii2-generator "*"
```

или добавьте в composer.json

```
"grigor/yii2-generator": "*",
```

Процесс настройки описан в расширении [yii2-rest](https://github.com/cmkcmykmailru/yii2-rest).

Система имеет две консольные команды

```shell
php yii generator/api/create
php yii generator/api/dev
```

Команда generator/api/create сканирует проект и ищет настройки в аннотациях, за тем конвертирует
их для [yii2-rest](https://github.com/cmkcmykmailru/yii2-rest). В параметре можно указать путь сканируемой папки, тогда
будет сканироваться не весь проект, а только указанная папка.

Вывод будет примерно таким:
```shell
Начал сканировать директорию: /var/www/users
Найден метод: api\repositories\SomeServiceOrRepository::getDemoContextMethod(...);         
              Route:       /v1/context/demo/<id:[\w\-]+>
              Method:      GET
              Context:     api\context\FindModel
              Response:    201

Найден метод: api\repositories\SomeServiceOrRepository::getPhone(...);
              Route:       /v1/shop/phones/<id:[\w\-]+>
              Method:      GET
              Serializer:  api\serializers\SerializePhone

Найден метод: api\repositories\SomeServiceOrRepository::getAllPhones(...);
              Route:       /v1/shop/phones
              Method:      GET
              Serializer:  api\serializers\SerializePhone

Найден метод: api\repositories\SomeServiceOrRepository::createPhone(...);
              Route:       /v1/shop/phones
              Method:      POST
              Response:    201

Найден метод: api\repositories\SomeServiceOrRepository::createAndReturnPhone(...);
              Route:       /v2/shop/phones
              Method:      POST
              Response:    201

Найден метод: api\repositories\SomeServiceOrRepository::updatePhone(...);
              Route:       /v1/shop/phones/<id:[\w\-]+>
              Method:      PUT
              Response:    202
              Serializer:  api\serializers\SerializePhone

Завершил.                                                                                                               

Целевых файлов:............1 шт.
Методов:...................6 шт.
Просканировано:............9933 файлов
Затрачено времени:.........3.805 сек.

```
Для того, чтобы аннотации работали нужно в классе который их содержит используйте:

```php
use grigor\generator\annotation as API;
```

Пример аннотации:

```php 
    /**
     * @API\Route(
     *     url="/v1/shop/phones",
     *     methods={"GET"},
     *     alias="phones/index"
     * )
     * @API\Serializer("api\serializers\SerializePhone")
     * @return DataProviderInterface
     */
     public function getAllPhones(): DataProviderInterface
```

###Все настройки в аннотациях

####@Route
```shell
Route(
     url="/v1/shop/phones",
     methods={"GET"},
     alias="phones/index"
 )
```
url - адрес по который будет отрабатывать

methods - указываются методы GET POST PUT и т.д

alias - т.к. контроллеров как таковых нет для генерации url в коде нужно указывать такой alias
используется этот алиас при генерации ссылок в коде Yii::$app->urlManager->createUrl(["phones/index"]); используется в штатном режиме.

####@Serializer
```shell
Serializer("api\serializers\SerializePhone")
```
Если вы хотите как-то по другому сериализовать возвращаемый объект можете указать калсс сериализатора. см. подробности [yii2-rest](https://github.com/cmkcmykmailru/yii2-rest).

####@Permission
```shell
Permission({"admin", "user"})
```
Если вам нужно ограничить доступ, то можно перечислить разрешения и роли.

####@Context
```shell
Context("api\context\FindModel")
```
Ограничитель области действия, можно так сказать, (типа как findModel).
Вернуть он может массив с недостающими в отрабатывающем методе параметрами. 
Например у нас есть метод public function getProfile(string $id)
а api-шка должна отдавать профиль текущего юзера. Т.е. для пользователя системы нет 
параметра id получается url примерно такой /v2/user/profile метод GET и все, но мы 
используем public function getProfile(string $id) где нужно передать id user-а в данном 
случае текущего. см. подробнее [yii2-rest](https://github.com/cmkcmykmailru/yii2-rest).

####@Response
```shell
Response(statusCode="202")
```
Если нужно вернуть статус код отличающийся от 200, то можно использовать такую аннотацию.

###Настройки
Команда generator/api/dev сканирует папки указанные в настройках приложения например в
common/config/params.php:

```php
<?php
return [
    ...
    /**
     * Это пути где будут лежать настройки правил для рест апи и настройки методов которые будут отрабатывать в место actions.
     *
     * Если использовать yii2-generator, то лучше пути сразу писать без @alias или конвертировать
     * в относительный|реальный путь. Ниже будет описано почему или см. yii2-generator 
     * grigor\generator\tools\DeveloperTool::beforeAppRunScanDevDirectories($config);.
     */
    'serviceDirectoryPath' => Yii::getAlias('@api/data/static/services'),// тут будут лежать настройки методов.
    'rulesPath' => Yii::getAlias('@api/data/static/rules.php'), // тут сами правила со ссылками на настройки выше.
    /**
     * Параметр говорит генератору в каких папках ведется разработка ядра для апи, в общем случае где искать php файлы 
     * с аннотациями содержащими настройки для апи.
     * Этот параметр использует только yii2-generator, но он использует и параметры выше.
     */
    'devDirectories' => [
        Yii::getAlias('@api'),
    ]
    ...
];
```


При разработке удобно будет после каждого изменения аннотаций не набирать команды в консоли, для
этого можно произвести следующие манипуляции с файлом index.php в папке api/web, именно до запуска приложения 
добавить такую строчку grigor\generator\tools\DeveloperTool::beforeAppRunScanDevDirectories($config);
этот метод перегенерирует настройки перед запуском приложения. Все это дело может выглядеть следующим образом:


```php 
<?php

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/../../common/config/bootstrap.php';
require __DIR__ . '/../config/bootstrap.php';

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/../../common/config/main.php',
    require __DIR__ . '/../../common/config/main-local.php',
    require __DIR__ . '/../config/main.php',
    require __DIR__ . '/../config/main-local.php'
);
/*
//этот вариант подойдет если вы держите настройки rest api в базе.
if (!exec('cd ../../ && php yii generator/api/dev')) {
    throw \RuntimeException('Что то пошло не так.');
}
*/

grigor\generator\tools\DeveloperTool::beforeAppRunScanDevDirectories($config);
(new yii\web\Application($config))->run();

```

Данные манипуляции можно использовать только в момент разработки, не забудьте на продакшене удалить эту строчку или настройте environments.
Хоть система работает быстро, если у вас очень много методов c api (переваливает за тысячи :)) и начинает подтормаживать,
то в конфиге можно сократить кол-во сканируемых папок (переключиться на текущую).