<?php

require __DIR__.'/vendor/autoload.php';

$app = new Silex\Application();

$app['debug'] = true;

/**
 * Loads racers from a textfile and returns them as an array
 * @var string
 */
$loadRacers = function ($filename) {
    $file = file_get_contents($filename);

    return explode("\n", $file);
};

/**
 * Route for loading racer time data, label must be "start" or "finish", id must
 * be numeric. Returns result as JSON.
 * @example GET /start/1 HTTP/1.1
 */
$app->get('/{label}/{id}', function ($id, $label) use ($app, $loadRacers) {
    return json_encode($loadRacers($label.'.txt'));
})
->assert('id', '\d+')
->assert('label', '(start|finish)');

$app->run();

