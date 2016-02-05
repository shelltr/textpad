<?php
require 'vendor/autoload.php';
require 'functions.php';

/* debugging data */
$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];
$c = new \Slim\Container($configuration);

/* actual app */
$app = new \Slim\App($c);

$app->get('/', function ($request, $response, $args) {
    $gen_uid = gen_uid();
    return $response->withRedirect('/' . $gen_uid);
});

$app->get('/{gen_uid}', function ($request, $response, $args) {
    ?>
    <textarea>sup</textarea>
    <?php
    getConnection();
    //return $response->write("Hello " . $args['gen_uid']);
});

$app->run();