<?php
require 'includes/vendor/autoload.php';
require 'functions.php';
require 'config.php';
use Psr7Middlewares\Middleware\TrailingSlash;

/* debugging data */
$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];
$c = new \Slim\Container($configuration);

/* actual app */
$app = new \Slim\App($c);
$app->add(new TrailingSlash(false));

/* Twig Settings */
$container = $app->getContainer();

// Register Twig View helper and configure it
$container['view'] = function ($c) {
    //You can change this as you want
    $view = new \Slim\Views\Twig('templates', [
        'cache' => false //or specify a cache directory
    ]);

    // Instantiate and add Slim specific extension
    $view->addExtension(new Slim\Views\TwigExtension(
        $c['router'],
        $c['request']->getUri()
    ));

    return $view;
};

$app->get( '/', function($request, $response, $args) {
    $gen_uid = gen_uid();
    return $response->withRedirect( $gen_uid );
});

$app->get('/{gen_uid}', function($request, $response, $args) {
    // get from database if exists
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
    $sql = 'SELECT user_text FROM textpad WHERE gen_uid=?';
    $q = $pdo->prepare($sql);
    $q->execute(array($args['gen_uid']));
    $note_details = $q->fetch();
    Database::disconnect();
    
    return $this->view->render($response, 'note-template.twig', 
        ['gen_uid' => $args['gen_uid'],
        'user_text' => $note_details['user_text']]);
});

$app->get('/{gen_uid}/raw.txt', function($request, $response, $args) {
    // get from database if exists
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
    $sql = 'SELECT user_text FROM textpad WHERE gen_uid=?';
    $q = $pdo->prepare($sql);
    $q->execute(array($args['gen_uid']));
    $note_details = $q->fetch();
    Database::disconnect();

    $response = $response->withHeader('Content-type', 'text/plain');
    return $response->write($note_details['user_text']);
});

$app->post('/{gen_uid}', function($request, $response, $args) {
    // add to database
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
    $sql = "INSERT INTO textpad (gen_uid, user_text, last_updated) VALUES (?, ?, CURDATE()) ON DUPLICATE KEY UPDATE user_text=?, last_updated=CURDATE()";
    $q = $pdo->prepare($sql);
    
    // get content from note-template.twig
    $post = $request->getParsedBody();
    $user_text = $post['user_text']; 
    $q->execute(array($args['gen_uid'], $user_text, $user_text));
    Database::disconnect();

    return $this->view->render($response, 'note-template.twig', ['gen_uid' => $args['gen_uid'], 'user_text' => $user_text]);
});

// Run app
$app->run();

