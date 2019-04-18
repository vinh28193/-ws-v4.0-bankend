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
        foreach ($products as $product) {
            $this->_products[] = new Product($product);
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