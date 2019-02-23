<?php

namespace userbackend\cart;

use userbackend\cart\storage\StorageInterface;


class ShoppingCart
{
    /**
     * @var StorageInterface
     */
    private $storage;

    private $_items = [];

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /****Todo 00
     * 1 Shop người bán Amazon / Ebay  --> 1 Khách hàng mua hàng ---> N order + flow tiền + product SKU --> lưu vào Giỏ Hàng Session  + API cho  App
     * 1 Shop người bán Amazon / Ebay  --> 1 Khách hàng mua hàng ---> 1 order + flow tiền + product SKU --> lưu vào Giỏ Hàng Session  + API cho  App
     * Todo 01 : Làm lại Wallet - Ngân Lượng
     * Todo 02 : Chuyển bảng customer  --> thành bảng  mới
     * Todo 03 : Thiết kế lại thanh toán theo luồng Wallet
     *****/

    public function add($id, $amount)
    {
        $this->loadItems();
        if (array_key_exists($id, $this->_items)) {
            $this->_items[$id]['amount'] += $amount;
        } else {
            $this->_items[$id] = [
                'id' => $id,
                'amount' => $amount,
            ];
        }
        $this->saveItems();
    }

    public function remove($id)
    {
        $this->loadItems();
        $this->_items = array_diff_key($this->_items, [$id => []]);
        $this->saveItems();
    }

    public function clear()
    {
        $this->_items = [];
        $this->saveItems();
    }

    public function getItems()
    {
        $this->loadItems();
        return $this->_items;
    }

    private function loadItems()
    {
        $this->_items = $this->storage->load();
    }

    private function saveItems()
    {
        $this->storage->save($this->_items);
    }
}