<?php

namespace Application\Controllers;

use Account;
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
        if (!$pAccountInfos["email"] || !$pAccountInfos["passwordHash"])
            return array("status" => "error", "msg" => "Invalid email or passwordHash");

        // On vérifie si l'email n'est déjà enregistrée
        $accountRepository = $this->mEntityManager->getRepository('Account');
        $account = $accountRepository->findOneBy(array('email' => $pAccountInfos['email']));
        if (!is_null($account))
            return array("status" => "error", "msg" => "E-mail already registered");
        
        $newAccount = new Account();
        $newAccount->setEmail($pAccountInfos["email"]);
        $newAccount->setPasswordhash($pAccountInfos["passwordHash"]);

        $this->mEntityManager->persist($newAccount);
        $this->mEntityManager->flush();
    }

    public function AuthenticateAccount(array $pAccountInfos)
    {
        $accountRepository = $this->mEntityManager->getRepository('Account');
        $accounts = $accountRepository->findAll();
        $account = $accountRepository->findOneByEmail($pAccountInfos['email']);

        if (is_null($account))
            return array("status" => "error", "msg" => "Account not found");

        if ($account->getPasswordhash() != $pAccountInfos["passwordHash"])
            return array("status" => "error", "msg" => "Wrong password");

        return array("status" => "ok", "msg" => $account);
    }
}
