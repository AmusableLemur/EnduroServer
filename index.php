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
    $data = explode("\n", $file);
    $racers = array();

    foreach ($data as $row) {
        if (strlen($row) < 1) {
            continue;
        }

        $row = explode(' ', $row);

        $racers[$row[0]][] = $row[1];
    }

    return $racers;
};

/**
 * Route for loading racer time data, label must be "start" or "finish", id must
 * be numeric.
 * @example GET /start/1 HTTP/1.1
 */
$app->get('/{label}/{id}', function ($id, $label) use ($app, $loadRacers) {
    return implode("\n", $loadRacers($label.'.txt')[$id]);
})
->assert('id', '\d+')
->assert('label', '(start|finish)');

/**
 * Route for storing racer time data, same rules as for loading racer time. Time
 * must be in format hh.mm.ss
 * @example PUT /start/1/12.12.12 HTTP/1.1
 */
$app->put('/{label}/{id}/{time}', function ($id, $label, $time) use ($app) {
    file_put_contents($label.'.txt', $id.' '.$time."\n", FILE_APPEND);

    return sprintf("%s added to #%d's %s times", $time, $id, $label);
})
->assert('id', '\d+')
->assert('label', '(start|finish)')
->assert('time', '\d{2}\.\d{2}\.\d{2}');

$app->run();

