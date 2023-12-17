<?php

namespace Application\Models;

use Application\Helpers\PaymentHelper;
use Application\Helpers\TenantHelper;
use System\Core\Model;

class Order extends Model
{
    const STATUS_INCOMPLETE = 'incomplete';
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_CANCELED = 'canceled';
    const STATUS_HOLD = 'hold';
    const STATUS_COMPLETED = 'completed';

    const ENTITY_TYPE = 'order';

    private $_table = 'orders';

    public function getTable()
    {
        return $this->_table;
    }

    public function create($data)
    {
        return $this->_db->insert($this->_table, $data);
    }

    public function getInfoByIds($ids)
    {
        if (empty($ids)) return [];

        $ids = (array)$ids;
        $SQL = "SELECT * FROM `{$this->_table}` 
            WHERE `id` IN ";

        $placeholder = array_fill(0, count($ids), '?');
        $values = array_values($ids);

        $SQL .= " (" . implode(', ', $placeholder) . ")";

        $result = $this->_db->query($SQL, $values)->getAll();

        if (!$result) return [];

        $output = [];
        foreach ($result as $row) {
            $output[$row['id']] = $row;
        }

        return $output;
    }

    public function calcTotalAmount($ownerId, $status, $isAdvisor = false)
    {
        $col = 'admin_amount';

        if ($isAdvisor) {
            $col = 'advisor_amount';
        }

        $SQL = "SELECT SUM(`$col`) as `totalAmount` FROM `{$this->_table}`
                                     INNER JOIN `users` on (`orders`.`entity_owner_id` = `users`.`id`)
                WHERE `entity_owner_id` = ?
                AND `status` = ?";

//        if ($isAdvisor) {
//            $SQL .= " OR (`users`.`entity_id` = {$ownerId} AND `status` = {$status})";
//        }

        $result = $this->_db->query($SQL, [$ownerId, $status])->get();

        if (empty($result['totalAmount'])) return 0;

        return $result['totalAmount'];
    }


    public function update($id, $data)
    {
        return $this->_db->update($this->_table, $id, $data);
    }

    public function take($id)
    {
        $SQL = "SELECT * FROM `{$this->_table}` WHERE `id` = ?";

        return $this->_db->query($SQL, [$id])->get();
    }

    public function getTotalAmount($userId, $status)
    {
        $SQL = "SELECT * FROM `{$this->_table}`
               WHERE `user_id` = ?
               AND `status` = ?";
    }

    public function getOrdersByCoupon($id)
    {
        $SQL = "SELECT * FROM `{$this->_table}`
                WHERE `used_coupon` = ?";

        return $this->_db->query($SQL, [$id])->getAll();
    }

    public function totalCount($userId, $type, $status = 'pending')
    {
        $SQL = "SELECT COUNT(*) as `count` FROM `{$this->_table}` 
                WHERE `entity_owner_id` = ? AND `entity_type` = ?
                AND `status` = ?";

        $result = $this->_db->query($SQL, [$userId, $type, $status])->get();

        return $result['count'];
    }

    public function getMyOrders(
        $userId = null,
        $year = null,
        $fromId = null,
        $count = false,
        $limit = null
    )
    {

        $SQL = "SELECT COUNT(*) as `count` FROM `{$this->_table}` ";

        if (!$count) {
            $SQL = "SELECT * FROM `{$this->_table}` ";
        }

        $where = array();
        $dbValues = array();

        if ($userId) {
            $where[] = " `user_id` = ? ";
            $dbValues[] = $userId;
        }

        // if ( $year )
        // {
        //     $

        //     $where[] = " `user_id` = ? ";
        //     $dbValues[] = $userId;
        // }

        if ($fromId) {
            $where[] = " `id` <= ?";
            $dbValues[] = $fromId;
        }

        if (!empty($where)) {
            $SQL .= " WHERE ";
            $SQL .= implode(' AND ', $where);
        }

        $SQL .= " ORDER BY `id` DESC";

        if (is_numeric($limit)) {
            $SQL .= " LIMIT ?";
            $dbValues[] = (int)$limit;
        }

        if (!$count) {
            $result = $this->_db->query($SQL, $dbValues)->getAll();
        } else {
            $result = $this->_db->query($SQL, $dbValues)->get();
        }

        return $result;
    }

    public function getOrders(
        $ownerId = null,
        $entityType = null,
        $entityId = null,
        $status = null,
        $skip = null,
        $limit = null,
        $fromId = null,
        $fromDate = null,
        $toDate = null
    )
    {
        $SQL = "SELECT * FROM `{$this->_table}` ";

        $where = array();
        $dbValues = array();

        if ($ownerId) {
            if (is_array($ownerId)) {
                $ids = implode($ownerId, ',');
                $where[] = " `entity_owner_id` in  ({$ids})";
            } else {
                $where[] = " `entity_owner_id` = ? ";
                $dbValues[] = $ownerId;
            }
        }

        if ($entityType) {
            $where[] = " `entity_type` = ? ";
            $dbValues[] = $entityType;
        }

        if ($entityId) {
            $where[] = " `entity_id` = ? ";
            $dbValues[] = $entityId;
        }

        if ($status) {
            $where[] = " `status` = ? ";
            $dbValues[] = $status;
        }

        if ($fromId) {
            $where[] = " `id` <= ?";
            $dbValues[] = $fromId;
        }

        if (!empty($fromDate)) {
            $where[] = ' `created_at` > ? ';
            $dbValues[] = (int)$fromDate;
        }

        if (!empty($toDate)) {
            $where[] = '`created_at` < ?';
            $dbValues[] = (int)$toDate;
        }

        $where[] = '`tenant_id` =  ?';
        $dbValues[] = TenantHelper::getId();

        if (!empty($where)) {
            $SQL .= " WHERE ";
            $SQL .= implode(' AND ', $where);
        }

        $SQL .= " ORDER BY `id` DESC";

        if (is_numeric($skip) && is_numeric($limit)) {
            $SQL .= " LIMIT ?, ?";
            $dbValues[] = (int)$skip;
            $dbValues[] = (int)$limit;
        }

        $result = $this->_db->query($SQL, $dbValues)->getAll();

        return $result;
    }

    public function ifOrdered($entityId, $entityType)
    {
        $SQL = "SELECT * FROM `{$this->_table}`
                WHERE `entity_id` = ?
                AND `entity_type` = ?";

        return $this->_db->query($SQL, [$entityId, $entityType])->getAll();
    }

    public function hasOrdered($userId, $entityId, $entityType)
    {
        $SQL = "SELECT * FROM `{$this->_table}`
                WHERE `entity_id` = ?
                AND `entity_type` = ?
                AND `user_id` = ?
                AND `status` IN (?, ?, ?)";

        return $this->_db->query($SQL, [$entityId, $entityType, $userId, self::STATUS_APPROVED, self::STATUS_COMPLETED, self::STATUS_PENDING])->get();
    }

    public function updateStatusByEntityId($id, $type, $status)
    {
        $SQL = "UPDATE `orders` SET `status` = ?
                WHERE `entity_id` = ?
                AND `entity_type` = ?
                AND `status` = ?";

        return $this->_db->query($SQL, [$status, $id, $type, self::STATUS_APPROVED]);
    }

    public function updateInvoiceFileName($id, $filename)
    {
        $SQL = "UPDATE `orders` SET `invoice_filename` = ?
                WHERE `id` = ?";

        return $this->_db->query($SQL, [$filename, $id]);
    }

    public function getApprovedOrders()
    {
        $SQL = "SELECT * FROM `{$this->_table}`
                WHERE `status` = ?";

        return $this->_db->query($SQL, [self::STATUS_APPROVED])->getAll();
    }

    public function updateOrderInfoForApplePay(array $order, string $actualPaymentMethod, $response): void
    {
        if ($order['payment_method'] != Payment::METHOD_APPLE_PAY) {
            return;
        }

        $brand = Payment::BRANDS_MAPPING[strtolower($actualPaymentMethod)];
        $finalAmount = $order['payable'] - PaymentHelper::getGetWayPercentageCut($order['payable'], $brand);

        $SQL = "UPDATE `orders` SET `final_amount` = ?, `payment_method` = ? 
                WHERE `id` = ?";

        $this->_db->query($SQL, [$finalAmount, $brand, $order['id']]);
    }
}