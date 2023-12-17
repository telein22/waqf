<?php

namespace Application\Models;

use System\Core\Config;
use System\Core\Model;

class CallSlot extends Model
{
    private $_table = 'call_slots';
    const ENTITY_TYPE = 'call_slots';

    public function create($data)
    {
        return $this->_db->insert($this->_table, $data);
    }

    public function all(array $where = null)
    {
        $SQL = "SELECT * FROM `{$this->_table}`";

        if (!empty($where)) {
            $SQL .= " WHERE ";

            foreach ($where as $key => $value) {
                if (is_array($value)) {
                    $values = implode($value, ',');
                    $SQL .= "`{$key}` in ({$values})";
                } else {
                    $SQL .= "`{$key}` = {$value}";
                }

                if ($key != array_key_last($where)) {
                    $SQL .= " AND ";
                }
            }

        }

        return $this->_db->query($SQL)->getAll();
    }

    public function getAvailableSlots($userId, $from, $to)
    {
        /**
         * @var \Application\Models\Order
         */
        $orderM = Model::get('\Application\Models\Order');
        $orderT = $orderM->getTable();
        $orderCancelStatus = $orderM::STATUS_CANCELED;

        /**
         * @var \Application\Models\Call
         */
        $callM = Model::get('\Application\Models\Call');
        $callT = $callM->getTable();
        $callType = $callM::ENTITY_TYPE;

        $SQL = "SELECT `cs`.* FROM
        `{$this->_table}` as `cs`
        WHERE
        `cs`.`user_id` = ? AND
        `cs`.`date` >= ? AND `cs`.`date` <= ?
        AND `cs`.`id` IN (
            SELECT `slot_id` FROM `{$callT}`
            WHERE `is_temp` <> 0
            AND `id` NOT IN (
                SELECT `entity_id` FROM `{$orderT}`
                WHERE `entity_type` = '{$callType}'
                AND `status` <> '{$orderCancelStatus}'
            ) 
        )
        ORDER BY `cs`.`date` ASC, `cs`.`time` ASC ";

        $dbValues = [$userId, $from, $to];

        return $this->_db->query($SQL, $dbValues)->getAll();
    }

    public function getSlots($userId, $from = null, $to = null, $skip = null, $limit = null)
    {
        $SQL = "SELECT * FROM
                `{$this->_table}` WHERE
                `user_id` = ? ";

        $dbValues = [$userId];

        if ( $from )
        {
            $SQL .= " AND `date` >= ? ";
            $dbValues[] = $from;        
        }
        
        if ( $to )
        {
            $SQL .= " AND `date` <= ? ";
            $dbValues[] = $to;        
        }
        
        $SQL .= " ORDER BY `date` ASC, `time` ASC ";

        if (is_numeric($skip) && is_numeric($limit)) {
            $SQL .= " LIMIT ?, ? ";
            $dbValues[] = (int) $skip;
            $dbValues[] = (int) $limit;
        }

        // var_dump($SQL, $dbValues);exit;

        return $this->_db->query($SQL, $dbValues)->getAll();
    }

    public function getById($id)
    {
        $SQL = "SELECT * FROM `{$this->_table}`
        WHERE `id` = ?";

        return $this->_db->query($SQL, [$id])->get();
    }

    public function delete($id)
    {
        $SQL = "DELETE FROM `{$this->_table}`
                WHERE `id` = ?";

        return (bool) $this->_db->query($SQL, [$id])->rowCount();
    }

    public function getSlotCountAt( $startTime, $endTime )
    {
        $config = Config::get("Website");
        $duration = $config->call_duration;

        $SQL = "SELECT (COUNT(*) * 2) AS `count` FROM `{$this->_table}`
            WHERE (CAST(CONCAT(`date`, ' ', `time`) AS  DATETIME) <= ?
            AND ADDDATE(CAST(CONCAT(`date`, ' ', `time`) AS  DATETIME), INTERVAL {$duration} MINUTE) > ?)
            OR (CAST(CONCAT(`date`, ' ', `time`) AS  DATETIME) < ?
            AND ADDDATE(CAST(CONCAT(`date`, ' ', `time`) AS  DATETIME), INTERVAL {$duration} MINUTE) >= ?)
            OR (CAST(CONCAT(`date`, ' ', `time`) AS  DATETIME) > ?
            AND ADDDATE(CAST(CONCAT(`date`, ' ', `time`) AS  DATETIME), INTERVAL {$duration} MINUTE) <= ?)";
        
        $result = $this->_db->query($SQL, [$startTime, $startTime, $endTime, $endTime, $startTime, $endTime])->get();

        return $result['count'] ? $result['count'] : 0;
    }

    public function getSlotBookingBySlotIds( $ids )
    {
        /**
         * @var Call
         */
        $callM = Model::get(Call::class);
        $callTable = $callM->getTable();
        $SQL = "SELECT * FROM `{$callTable}`
        WHERE `slot_id` IN ";

        $implode = implode(", ", array_fill(0, count($ids), '?'));
        $values = array_values($ids);

        $SQL .= "($implode) AND `status` <> ? AND `is_temp` = 0 ORDER BY `id` DESC";
        $values[] = Call::STATUS_CANCELED;
        $result = $this->_db->query($SQL, $values)->getAll();

        $output = [];
        foreach ( $result as $item )
        {
            $output[$item['slot_id']][] = $item;
        }

        return $output;
    }
}
