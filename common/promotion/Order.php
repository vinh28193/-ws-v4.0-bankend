<?php

namespace common\promotion;

class Order extends PromotionItem
{
    /**
     * @var Product[]
     */
    private $_products;

    public function setProducts($products)
    {
        foreach ($products as $key => $product) {
            $this->_products[$key] = new Product($product);
        }
    }

    /**
     * @return Product[]
     */
    public function getProducts()
    {
        return $this->_products;
    }

    public function updateProduct($key, $value)
    {
        $this->_products[$key] = $value;
    }
}