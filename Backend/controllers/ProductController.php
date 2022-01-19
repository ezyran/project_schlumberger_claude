<?php

namespace Application\Controllers;

use Product;

use Doctrine\ORM\EntityManager;

require_once "./bootstrap.php";

class ProductController
{
    #region Singleton

    private static ?ProductController $singleton = null;
    public static function GetProductController(EntityManager $pEntityManager)
    {
        if (!ProductController::$singleton)
            ProductController::$singleton = new ProductController($pEntityManager);
        return ProductController::$singleton;
    }

    #endregion
    #region Variables

    private $mEntityManager = null;

    #endregion


    private function __construct(EntityManager $pEm)
    {
        $this->mEntityManager = $pEm;
    }

    public function GetProduct(array $pProductInfos)
    {
        $productRepository = $this->mEntityManager->getRepository('Product');
        $product = $productRepository->findOneById($pProductInfos['id']);

        if (is_null($product))
            return array("status" => "error", "msg" => "Product not found.");

        return array("status" => "ok", "msg" => $this->ProductToArray($product));
    }

    public function ListProducts(array $pProductInfos)
    {
        $productRepository = $this->mEntityManager->getRepository('Product');
        $products = $productRepository->findAll();

        return array("status" => "ok", "msg" => $this->ProductListToArray($products));
    }

    public function ProductToArray(Product $pProduct)
    {
        return array(
            "id" => $pProduct->getId(),
            "name" => $pProduct->getName(),
            "description" => $pProduct->getDescription(),
            "price" => $pProduct->getPrice()
        );
    }

    public function ProductListToArray(array $pProductList)
    {
        $res = array();
        foreach ($pProductList as $product) {
            $res[] = $this->ProductToArray($product);
        }
        return $res;
    }

}