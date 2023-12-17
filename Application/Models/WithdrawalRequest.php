<?php

namespace Application\Models;

use System\Core\Model;

class WithdrawalRequest extends Model
{

    private $_table = 'withdrawal_requests';

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    public function all(?string $from = null, ?string $to = null, ?string $status = null, ?int $userId = null)
    {
        $SQL = "SELECT `withdrawal_requests`.*, `users`.`name`, `users`.`email`, `users`.`type`, `wallets`.`balance` as wallet_balance, `wallets`.`id` as wallet_id, bank_info FROM {$this->_table} 
         INNER JOIN `users` ON (`users`.`id` = `withdrawal_requests`.`user_id`)
         INNER JOIN `wallets` ON (`wallets`.`user_id` = `withdrawal_requests`.`user_id`)
         LEFT OUTER JOIN (
                      SELECT user_settings.user_id, GROUP_CONCAT(user_settings.value) as bank_info FROM `user_settings` 
                        WHERE `user_settings`.`key` IN ('bank1', 'bank2')
                        GROUP BY `user_settings`.`user_id`
                   
                  ) as `settings` on (`settings`.`user_id` = `users`.`id`)                                                                                                                           
         
         WHERE 1=1  ";


        if ($from && $to) {
            $SQL .= " AND `withdrawal_requests`.`created_at` > ? AND `withdrawal_requests`.`created_at` < ?";
            $dbValus = [$from, $to];
        }

        if ($userId) {
            $SQL .= " AND `withdrawal_requests`.`user_id` = ?";
            $dbValus = [$userId];
        }

        if ($status) {
            $SQL .= ' AND `status` = ?';
            $dbValus [] = $status;
        }

        return $this->_db->query($SQL, $dbValus)->getAll();
    }

    public function getTotallWithdrawn(int $userId)
    {
        $SQL = "SELECT SUM(amount) AS total FROM `{$this->_table}` WHERE `status`= ? AND `user_id` = ?";

        $result = $this->_db->query($SQL, [
            self::STATUS_COMPLETED,
            $userId
        ])->get();

        return $result['total'] ?? 0;
    }

    public function create($data)
    {
        return $this->_db->insert($this->_table, $data);
    }

    public function getActiveRequestByUserId(int $userId)
    {
        $SQL = "SELECT * FROM {$this->_table} WHERE `user_id` = ? AND `status` IN (? , ?, ?)";

        return $this->_db->query($SQL, [
            $userId,
            self::STATUS_PENDING,
            self::STATUS_APPROVED,
            self::STATUS_PROCESSING,
        ])->get();
    }

    public function getById(int $id)
    {
        $SQL = "SELECT * FROM {$this->_table} WHERE `id` = ?";

        return $this->_db->query($SQL, [
            $id
        ])->get();
    }

    public function getAllByUserId(int $userId)
    {
        $SQL = "SELECT * FROM {$this->_table} WHERE `user_id` = ?";

        return $this->_db->query($SQL, [
            $userId,
        ])->getAll();
    }

    public function changeStatus(int $id, string $status)
    {
        $SQL = "UPDATE `{$this->_table}` SET `status` = ? , `updated_at` = ? WHERE `id` = ?";

        $this->_db->query($SQL, [
           $status, time(), $id
        ]);
    }
}