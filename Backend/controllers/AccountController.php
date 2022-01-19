<?php

namespace Application\Controllers;

use Account;
use Client;
use Application\Controllers\ClientController;

use Doctrine\ORM\EntityManager;

require_once "./bootstrap.php";

class AccountController
{
    #region Singleton

    private static ?AccountController $singleton = null;
    public static function GetAccountController(EntityManager $pEntityManager)
    {
        if (!AccountController::$singleton)
            AccountController::$singleton = new AccountController($pEntityManager);
        return AccountController::$singleton;
    }

    #endregion
    #region Variables

    private $mEntityManager = null;

    #endregion


    private function __construct(EntityManager $pEm)
    {
        $this->mEntityManager = $pEm;
    }

    public function CreateAccount(array $pAccountInfos)
    {
        // Si 'email' ou 'passwordHash' vide, renvoyer erreur
        if (!$pAccountInfos["email"] || !$pAccountInfos["passwordHash"] || !$pAccountInfos["clientId"])
            return array("status" => "error", "msg" => "Invalid email or passwordHash");

        // On vérifie si l'email n'est déjà enregistrée
        $accountRepository = $this->mEntityManager->getRepository('Account');
        $account = $accountRepository->findOneByEmail($pAccountInfos['email']);
        if (!is_null($account))
            return array("status" => "error", "msg" => "E-mail already registered");

        // Récupération du Client
        $clientGetRes = ClientController::GetClientController($this->mEntityManager)->getClient(array( "id" => $pAccountInfos["clientId"]));

        if ($clientGetRes["status"] == "error")
            return array("status" => "error", "msg" => "GetClient failed on AccountCreation");

        $client = $clientGetRes["msg"];

        $newAccount = new Account();
        $newAccount->setEmail($pAccountInfos["email"]);
        $newAccount->setPasswordhash($pAccountInfos["passwordHash"]);
        $newAccount->setClient($client);

        $this->mEntityManager->persist($newAccount);
        $this->mEntityManager->flush();

        return array("status" => "ok", "msg" => $newAccount);
    }

    public function AuthenticateAccount(array $pAccountInfos)
    {
        $accountRepository = $this->mEntityManager->getRepository('Account');
        $account = $accountRepository->findOneByEmail($pAccountInfos['email']);

        if (is_null($account))
            return array("status" => "error", "msg" => "Account not found");

        if ($account->getPasswordhash() != $pAccountInfos["passwordHash"])
            return array("status" => "error", "msg" => "Wrong password");

        return array("status" => "ok", "msg" => $account);
    }

    public static function AccountToArray(Account $pAccount)
    {
        return array(
            "id" => $pAccount->getId(),
            "email" => $pAccount->getEmail(),
            "client" => ClientController::ClientToArray($pAccount->getClient())
        );
    }
}
