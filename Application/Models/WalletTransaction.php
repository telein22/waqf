<?php

namespace Application\Models;

use System\Core\Model;

class WalletTransaction extends Model
{

    private $_table = 'wallet_transactions';

    public function create( $data )
    {
        return $this->_db->insert($this->_table, $data);
    }

    public function getByWalletId(int $walletId)
    {
        $SQL = "SELECT `wallet_transactions`.*, `orders`.`entity_type`, `orders`.`entity_id`, `users`.`name` as beneficiary FROM {$this->_table}
                  INNER JOIN `orders` ON (`orders`.`id` = `wallet_transactions`.`order_id`)
                  INNER JOIN `users` ON (`orders`.`user_id` = `users`.`id`) 
                  WHERE `wallet_id` = ?";

        return $this->_db->query($SQL, [
           $walletId
        ])->getAll();
    }

    public function getTotalAmount(int $walletId): float
    {
        $SQL = "SELECT sum(`amount`) as total FROM `{$this->_table}` WHERE `wallet_id` = ?";

        $result = $this->_db->query($SQL, [$walletId])->get();

        if (empty($result['total'])) return 0;

        return $result['total'];
    }
}