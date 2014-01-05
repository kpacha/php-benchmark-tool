<?php

$config = array(
    'global' => array(
        'logPath' => __DIR__ . '/../build',
        'publicPath' => __DIR__ . '/../public/report',
        'wait' => 5
    )
);

$config['ab'] = array(
    'path' => '/usr/bin/ab',
    'arguments' => array(
        'timeLimit' => 20,
        'concurrency' => 5,
        'logPath' => $config['global']['logPath'] . '/ab/'
    )
);

$config['gnuplot'] = array(
    'path' => '/usr/bin/gnuplot',
    'logPath' => $config['global']['logPath'],
    'publicPath' => $config['global']['publicPath']
);

$config['html'] = array(
    'logPath' => $config['global']['logPath'],
    'publicPath' => $config['global']['publicPath']
);

$config['targets'] = array(
    'group1' => array(
        'http://www.google.com/',
    ),
);
