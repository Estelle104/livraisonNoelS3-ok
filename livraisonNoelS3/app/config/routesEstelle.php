<?php

use app\controllers\UserController;
use app\controllers\LivraisonController;
use app\controllers\BeneficeController;
use app\controllers\ColisController;
use app\controllers\ZoneLivraisonController;
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

    // zoneLLivrasion
    $router->post('/zones', [ZoneLivraisonController::class, 'index']);
    $router->get('/zones/create', [ZoneLivraisonController::class, 'create']);
    $router->post('/zones/store', [ZoneLivraisonController::class, 'store']);
    $router->get('/zones/edit/@id', [ZoneLivraisonController::class, 'edit']);
    $router->post('/zones/update/@id', [ZoneLivraisonController::class, 'update']);
    $router->get('/zones/delete/@id', [ZoneLivraisonController::class, 'delete']);

    // Redirection racine
    $router->get('/', function() use ($app) {
        $app->redirect('/login');
    });

}, [ SecurityHeadersMiddleware::class ]);