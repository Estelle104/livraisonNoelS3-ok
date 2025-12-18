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

// $router->get('/', Flight::render('test', $mesage = ['andrana']));

// Routes publiques
$router->get('/login', [UserController::class, 'loginForm']);
$router->post('/login', [UserController::class, 'login']);

// Groupe sécurisé
$router->group('', function(Router $router) use ($app) {

    Flight::route('/debug', function () {
        echo 'BASE_URL = ' . Flight::get('flight.base_url');
    });
    

    // Accueil
    $router->get('/accueil', [UserController::class, 'home']);
    $router->get('/logout', [UserController::class, 'logout']);

    // Gestion livraison
    $router->get('/livraison', [LivraisonController::class, 'index']);
    $router->get('/livraison/@id:[0-9]+', [LivraisonController::class, 'show']);
    $router->post('/livraison', [LivraisonController::class, 'store']);
    // $router->put('/livraison/@id:[0-9]+', [LivraisonController::class, 'update']);
    $router->post('/livraison/@id:[0-9]+', [LivraisonController::class, 'updateEtat']);
    // $router->delete('/livraison/@id:[0-9]+', [LivraisonController::class, 'delete']);

    // Bénéfices
    $router->get('/benefice', [BeneficeController::class, 'index']);
    $router->get('/benefice/details', [BeneficeController::class, 'details']);

    // Colis
    $router->post('/colis', [ColisController::class, 'store']);


    // Redirection racine
    $router->get('/', function() use ($app) {
        $app->redirect('/accueil');
    });

}, [ SecurityHeadersMiddleware::class ]);