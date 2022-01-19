<?php

use Application\Controllers\AccountController;
use Application\Controllers\ClientController;
use Application\Controllers\ProductController;

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Tuupola\Middleware\JwtAuthentication as JwtAuthentication;
use Doctrine\ORM\EntityManager;


require './controllers/JwtHandler.php';
require './controllers/AccountController.php';
require './controllers/ClientController.php';
require './controllers/ProductController.php';
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
    "ignore" => ["/api/signin", "/api/signup", "/api/product", "/api/product-list"],
    "error" => function ($response, $arguments) {
        $data = array('ERREUR' => 'Connexion', 'ERREUR' => 'JWT Non valide');
        $response = $response->withStatus(401);
        return $response->withHeader("Content-Type", "application/json")->getBody()->write(json_encode($data));
    }
]));


$app->post('/api/signin', function (Request $request, Response $response, $args) {
    global $entityManager;
    // Récupération des informations
    $body = $request->getParsedBody();

    // Vérifier si le body contient bien les paramètres attendus
    if (!array_key_exists('email', $body) || !array_key_exists('passwordHash', $body))
    {
        $response = $response->withStatus(400);
        $response = $response->withHeader("Content-Type", "application/json");
        $response->getBody()->write(json_encode(array("msg" => "Arguments invalides.")));
        return $response;
    }

    $email = trim($body['email']);
    $passwordHash = trim($body['passwordHash']);

    if ($email == "" || $passwordHash == "")
    {
        $response = $response->withStatus(400);
        $response = $response->withHeader("Content-Type", "application/json");
        $response->getBody()->write(json_encode(array("msg" => "Arguments invalides.")));
        return $response;
    }

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
        "id" => $account->getId(),
        "email" => $account->getEmail()
    )));
    return $response;
});

$app->post('/api/signup', function (Request $request, Response $response, $args) {
    global $entityManager;
    // Récupération des informations
    $body = $request->getParsedBody();

    // Vérifier si le body contient bien les paramètres attendus
    if (!array_key_exists('email', $body) || !array_key_exists('passwordHash', $body) || !array_key_exists('name', $body) || 
        !array_key_exists('surname', $body) || !array_key_exists('phoneNumber', $body) || !array_key_exists('streetNumber', $body) || 
        !array_key_exists('streetName', $body) || !array_key_exists('city', $body) || !array_key_exists('zipcode', $body))
    {
        $response = $response->withStatus(400);
        $response = $response->withHeader("Content-Type", "application/json");
        $response->getBody()->write(json_encode(array("msg" => "Arguments invalides.")));
        return $response;
    }

    // Création du Client
    $clientController = ClientController::GetClientController($entityManager);
    $clientCreationRes = $clientController->CreateClient($body);

    if ($clientCreationRes['status'] == "error")
    {
        $response = $response->withStatus(400);
        $response = $response->withHeader("Content-Type", "application/json");
        $response->getBody()->write(json_encode(array("msg" => $accountCreationRes["msg"])));
        return $response;
    }
    
    $email = trim($body['email']);
    $passwordHash = trim($body['passwordHash']);
    $client = $clientCreationRes["msg"];
    $clientId = $client->getId();

    // Création de l'Account
    $accountController = AccountController::GetAccountController($entityManager);
    $accountCreationRes = $accountController->CreateAccount(array('email' => $email, 'passwordHash' => $passwordHash, 'clientId' => $clientId));

    if ($accountCreationRes['status'] == "error")
    {
        $response = $response->withStatus(400);
        $response = $response->withHeader("Content-Type", "application/json");
        $response->getBody()->write(json_encode(array("msg" => $accountCreationRes["msg"])));
        return $response;
    }

    Add_CORS_Headers($response);
    $response = $response->withStatus(200);
    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode(array("msg" => "Creation successful")));
    return $response;
});

$app->get('/api/client/{id}', function (Request $request, Response $response, $args) {
    global $entityManager;

    if (!array_key_exists("id", $args) || trim($args["id"]) == "")
    {
        $response = $response->withStatus(400);
        $response = $response->withHeader("Content-Type", "application/json");
        $response->getBody()->write(json_encode(array("msg" => "Arguments invalides.")));
        return $response;
    }

    $id = trim($args["id"]);

    // Lecture client
    $clientController = ClientController::GetClientController($entityManager);
    $clientLectureRes = $clientController->GetClient(array("id" => $id));

    if ($clientLectureRes["status"] == "error")
    {
        $response = $response->withStatus(400);
        $response = $response->withHeader("Content-Type", "application/json");
        $response->getBody()->write(json_encode(array("msg" => $clientLectureRes["msg"])));
        return $response;
    }
    $client = $clientLectureRes["msg"];
    $client_array = array(
        "name" => $client->getName(),
        "surname" => $client->getSurname(),
        "phoneNumber" => $client->getPhonenumber(),
        "streetNumber" => $client->getStreetnumber(),
        "streetName" => $client->getStreetName(),
        "city" => $client->getCity(),
        "zipcode" => $client->getZipcode()
    );

    Add_CORS_Headers($response);
    $response = $response->withStatus(200);
    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode(array("client" => $client_array)));
    return $response;
});

$app->get('/api/product/{id}', function (Request $request, Response $response, $args) {
    global $entityManager;

    $id = trim($args["id"]);

    // Lecture produit
    $productController = ProductController::GetProductController($entityManager);
    $productLectureRes = $productController->GetProduct(array("id" => $id));

    if ($productLectureRes["status"] == "error")
    {
        $response = $response->withStatus(400);
        $response = $response->withHeader("Content-Type", "application/json");
        $response->getBody()->write(json_encode(array("msg" => $productLectureRes["msg"])));
        return $response;
    }
    $product = $productLectureRes["msg"];

    Add_CORS_Headers($response);
    $response = $response->withStatus(200);
    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode(array("product" => $product)));
    return $response; 
});

$app->get('/api/product-list', function (Request $request, Response $response, $args) {
    global $entityManager;

    // Lecture liste de produits
    $productController = ProductController::GetProductController($entityManager);
    $productLectureRes = $productController->ListProducts(array());

    if ($productLectureRes["status"] == "error")
    {
        $response = $response->withStatus(400);
        $response = $response->withHeader("Content-Type", "application/json");
        $response->getBody()->write(json_encode(array("msg" => $productLectureRes["msg"])));
        return $response;
    }
    $products = $productLectureRes["msg"];

    Add_CORS_Headers($response);
    $response = $response->withStatus(200);
    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode(array("products" => $products)));
    return $response; 
});

// Run app
$app->run();