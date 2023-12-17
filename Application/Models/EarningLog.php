<?php

namespace Application\Models;

use System\Core\Model;

class EarningLog extends Model
{
    const TYPE_SOCIAL = 'social';
    const TYPE_SERVICE = 'service';

    const LOG_ORDER_PENDING = 'order_pending';
    const LOG_ORDER_DECLINE = 'order_decline';
    const LOG_ORDER_ACCEPT = 'order_accept';
    const LOG_ORDER_CANCEL = 'order_cancel';
    const LOG_ORDER_COMPLETE = 'order_complete';


    private $_table = 'earning_logs';


    public function create($data)
    {
        return $this->_db->insert($this->_table, $data);
    }

    public function update($id, $data)
    {
        return $this->_db->update($this->_table, $id, $data);
    }

    public function all( $userId, $from = null, $to = null, $fromId, $limit = null )
    {
        $dbValues = array(
            $userId
        );

        $SQL = "SELECT * FROM `{$this->_table}`
        WHERE `user_id` = ?";

        if (!empty($from) && !empty($to)) {

            $SQL .= ' AND `created_at` > ? AND `created_at` < ? ';
            $dbValues[] = (int) $from;
            $dbValues[] = (int) $to;
        }

        if (is_numeric($fromId)) {
            $SQL .= " AND `id` <= ? ";
            $dbValues[] = $fromId;
        }

        $SQL .= " ORDER BY `id` DESC";

        if ( is_numeric($limit) )
        {
            $SQL .= " LIMIT ?";
            $dbValues[] = (int) $limit;
        }

        return $this->_db->query($SQL, $dbValues)->getAll();
    }
}