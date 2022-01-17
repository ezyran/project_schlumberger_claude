<?php

use Application\Controllers\AccountController;
use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Tuupola\Middleware\JwtAuthentication as JwtAuthentication;
use Doctrine\ORM\EntityManager;


require './controllers/JwtHandler.php';
require './controllers/AccountController.php';
require_once "bootstrap.php";

function Add_CORS_Headers($response) {
    $response = $response->withHeader("Access-Control-Allow-Origin", "http://localhost:4200")
    ->withHeader("Access-Control-Allow-Headers", "Content-Type, Authorization")
    ->withHeader("Access-Control-Allow-Methods", "GET, POST, PUT, PATCH, DELETE, OPTIONS")
    ->withHeader("Access-Control-Expose-Headers", "Authorization");

    return $response;
}

$app = AppFactory::create();

// Config authenticator Tuupola
$app->add(new JwtAuthentication([
    "secret" => Get_JWT_Secret(),
    "attribute" => "token",
    "header" => "Authorization",
    "regexp" => "/Bearer\s+(.*)$/i",
    "secure" => false,
    "algorithm" => ["HS512"],

    "path" => ["/api"],
    "ignore" => ["/api/register", "/api/login"],
    "error" => function ($response, $arguments) {
        $data = array('ERREUR' => 'Connexion', 'ERREUR' => 'JWT Non valide');
        $response = $response->withStatus(401);
        return $response->withHeader("Content-Type", "application/json")->getBody()->write(json_encode($data));
    }
]));


$app->post('/api/login', function (Request $request, Response $response, $args) {
    global $entityManager;
    // Récupération des informations
    $body = $request->getParsedBody();
    $email = $body['email'];
    $passwordHash = $body['passwordHash'];

    // Authentification de l'utilisateur
    $accountController = AccountController::GetAccountController($entityManager);
    $res = $accountController->AuthenticateAccount(array('email' => $email, 'passwordHash' => $passwordHash));

    // En cas d'erreur d'authentification
    if ($res["status"] == "error")
    {
        $response = $response->withStatus(401);
        $response = $response->withHeader("Content-Type", "application/json");
        $response->getBody()->write(json_encode(array("msg" => $res["msg"])));
        return $response;
    }
    $account = $res["msg"];

    Add_CORS_Headers($response);
    $jwt_token = Get_Encoded_JWT_Token($account);
    $response = $response->withHeader("Authorization", "Bearer { $jwt_token }");
    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode(array(
        "email" => $account->getEmail(),
        "passwordHash" => $account->getPasswordHash()
    )));
    return $response;
});

// $app->post('/api/register', function (Request $request, Response $response, $args) {
//     // Récupération des informations
//     $body = $request->getParsedBody();
//     $username = $body['username'];
//     $password = $body['password'];

//     // Authentification de l'utilisateur
//     $userManager = UserManager::GetUserManager();
//     $user = $userManager->RegisterUser($username, $password);

//     // En cas d'erreur d'authentification
//     if (array_key_exists("Error", $user))
//     {
//         $response = $response->withStatus(401);
//         $response = $response->withHeader("Content-Type", "application/json");
//         $response->getBody()->write(json_encode($user));
//         return $response;
//     }
//     Add_CORS_Headers($response);
//     $response->withHeader("Content-Type", "application/json");
//     $response->getBody()->write(json_encode($user));
//     return $response;
// });

// Run app
$app->run();