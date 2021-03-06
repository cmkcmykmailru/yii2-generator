<?php

return [
    'identity' => 'uuid1',
    'path' => $path ,
    'service' => [
        'class' => 'app\controllers\FakeService',
        'method' => 'method1'
    ],
    'context' => 'app\controllers\Fake2ActionContext',
    'permissions' => ['admin'],
    'response' => 200,
    'serializer' => 'serializer'
];