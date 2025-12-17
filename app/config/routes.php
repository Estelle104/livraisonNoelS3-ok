<?php

use app\controllers\UserController;
use app\controllers\LivraisonController;
use app\controllers\BeneficeController;
use app\controllers\ColisController;
use app\middlewares\SecurityHeadersMiddleware;
use flight\net\Router;
use flight\Engine;

/**
 * @var Router $router
 * @var Engine $app
 */

session_start();

// Routes publiques
$router->get('/login', [UserController::class, 'loginForm']);
$router->post('/login', [UserController::class, 'login']);

// Groupe sécurisé
$router->group('', function(Router $router) use ($app) {

    // Accueil
    $router->get('/app/accueil', [UserController::class, 'home']);
    $router->get('/logout', [UserController::class, 'logout']);

    // Gestion livraison
    $router->get('/app/livraison', [LivraisonController::class, 'index']);
    $router->get('/app/livraison/@id:[0-9]+', [LivraisonController::class, 'show']);
    $router->post('/app/livraison', [LivraisonController::class, 'store']);
    // $router->put('/app/livraison/@id:[0-9]+', [LivraisonController::class, 'update']);
    $router->post('/app/livraison/@id:[0-9]+', [LivraisonController::class, 'update']);
    $router->delete('/app/livraison/@id:[0-9]+', [LivraisonController::class, 'delete']);

    // Bénéfices
    $router->get('/app/benefice', [BeneficeController::class, 'index']);
    $router->get('/app/benefice/details', [BeneficeController::class, 'details']);

    // Colis
    $router->post('/app/colis', [ColisController::class, 'store']);

    // Redirection racine
    $router->get('/', function() use ($app) {
        $app->redirect('/app/accueil');
    });

}, [ SecurityHeadersMiddleware::class ]);