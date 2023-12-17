<?php

namespace Application\Models;

use System\Core\Model;

class Wallet extends Model
{

    private $_table = 'wallets';

    public function all(array $where = [])
    {
        $SQL = "SELECT `wallets`.*, `users`.`name`, `users`.`email`, `bank_info`  FROM `wallets` 
                  INNER JOIN `users` ON (`users`.`id` = `wallets`.`user_id`)
                  LEFT OUTER JOIN (
                      SELECT user_settings.user_id, GROUP_CONCAT(user_settings.value) as bank_info FROM `user_settings` 
                        WHERE `user_settings`.`key` IN ('bank1', 'bank2')
                        GROUP BY `user_settings`.`user_id`
                   
                  ) as `settings` on (`settings`.`user_id` = `users`.`id`)";
                  
                    

        return $this->_db->query($SQL)->getAll();
    }

    public function updateBalance(int $userId, float $balance, int $orderId): int
    {
        $SQL = "INSERT INTO {$this->_table} (user_id, balance, created_at, updated_at) VALUES (?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE balance = balance + ?;";

        $this->_db->query($SQL, [
            $userId,
            $balance,
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s'),
            $balance
        ]);

        return $this->_db->lastInsertId();
    }

    public function getByUserId(int $userId)
    {
        $SQL = "SELECT * FROM {$this->_table} WHERE `user_id` = ?";

        return $this->_db->query($SQL, [$userId])->get();
    }

    public function deductFromBalance(int $id, float $amount)
    {
        $SQL = "UPDATE `{$this->_table}` SET balance = balance - ? WHERE `user_id` = ?";

        return $this->_db->query($SQL, [$amount, $id])->get();
    }
}