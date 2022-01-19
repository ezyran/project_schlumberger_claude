<?php

namespace Application\Controllers;

use Client;
use Account;
use Doctrine\ORM\EntityManager;

require_once "./bootstrap.php";

class ClientController
{
    #region Singleton

    private static ?ClientController $singleton = null;
    public static function GetClientController(EntityManager $pEntityManager)
    {
        if (!ClientController::$singleton)
        ClientController::$singleton = new ClientController($pEntityManager);
        return ClientController::$singleton;
    }

    #endregion
    #region Variables

    private $mEntityManager = null;

    #endregion


    private function __construct(EntityManager $pEm)
    {
        $this->mEntityManager = $pEm;
    }

    public function CreateClient(array $pClientInfos)
    {
        $newClient = new Client();
        $newClient->setName($pClientInfos['name']);
        $newClient->setSurname($pClientInfos['surname']);
        $newClient->setPhonenumber($pClientInfos['phoneNumber']);
        $newClient->setStreetnumber($pClientInfos['streetNumber']);
        $newClient->setStreetname($pClientInfos['streetName']);
        $newClient->setCity($pClientInfos['city']);
        $newClient->setZipcode($pClientInfos['zipcode']);

        $this->mEntityManager->persist($newClient);
        $this->mEntityManager->flush();

        $clientRepository = $this->mEntityManager->getRepository('Client');
        $client = $clientRepository->findOneBy(array(
            'name' => $pClientInfos['name'],
            'surname' => $pClientInfos['surname'],
            'phonenumber' => $pClientInfos['phoneNumber'],
            'streetnumber' => $pClientInfos['streetNumber'],
            'streetname' => $pClientInfos['streetName'],
            'city' => $pClientInfos['city'],
            'zipcode' =>$pClientInfos['zipcode']
        ));

        if (is_null($client))
            return array("status" => "error", "msg" => "Creation error");

        return array("status" => "ok", "msg" => $client);
    }

    public function EditClient(array $pClientInfos)
    {
        $clientRepository = $this->mEntityManager->getRepository('Client');
        $client = $clientRepository->findOneById($pClientInfos['id']);

        if (is_null($client))
            return array("status" => "error", "msg" => "Client not found.");

        $client->setName($pClientInfos['name']);
        $client->setSurname($pClientInfos['surname']);
        $client->setPhonenumber($pClientInfos['phoneNumber']);
        $client->setStreetnumber($pClientInfos['streetNumber']);
        $client->setStreetname($pClientInfos['streetName']);
        $client->setCity($pClientInfos['city']);
        $client->setZipcode($pClientInfos['zipcode']);

        $this->mEntityManager->persist($client);
        $this->mEntityManager->flush();

        return array("status" => "ok", "msg" => "Modification successful");
    }

    public function DeleteClient(array $pClientInfos)
    {
        $clientRepository = $this->mEntityManager->getRepository('Client');
        $client = $clientRepository->findOneById($pClientInfos['id']);

        if (is_null($client))
            return array("status" => "error", "msg" => "Client not found.");

        $this->mEntityManager->remove($client);
        $this->mEntityManager->flush();

        return array("status" => "ok", "msg" => "Modification successful");
    }

    public function GetClient(array $pClientInfos)
    {
        $clientRepository = $this->mEntityManager->getRepository('Client');
        $client = $clientRepository->findOneById($pClientInfos['id']);

        if (is_null($client))
            return array("status" => "error", "msg" => "Client not found.");

        return array("status" => "ok", "msg" => $client);
    }

    public function ListClients(array $pClientInfos)
    {
        $clientRepository = $this->mEntityManager->getRepository('Client');
        $clients = $clientRepository->findAll();

        return array("status" => "ok", "msg" => $clients);
    }

    public static function ClientToArray(Client $pClient)
    {
        return array(
            "id" => $pClient->getId(),
            "name" => $pClient->getName(),
            "surname" => $pClient->getSurname(),
            "phoneNumber" => $pClient->getPhonenumber(),
            "streetNumber" => $pClient->getStreetnumber(),
            "streetName" => $pClient->getStreetName(),
            "city" => $pClient->getCity(),
            "zipcode" => $pClient->getZipcode()
        );
    }

    public static function ClientListToArray(array $pClients)
    {
        $res = array();
        foreach ($pClients as $client) {
            $res[] = ClientController::ClientToArray($client);
        }
        return $res;
    }

}
