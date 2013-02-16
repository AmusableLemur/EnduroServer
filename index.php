<?php

require __DIR__.'/vendor/autoload.php';

$app = new Silex\Application();

$app->get('/{label}/{id}', function ($id, $label) use ($app) {
    return 'Racer #'.$app->escape($id).' not found';
})->assert('id', '\d+');

$app->run();

