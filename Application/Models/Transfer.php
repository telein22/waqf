<?php

namespace Application\Models;

use System\Core\Model;

class Transfer extends Model
{
    const RECEIVER_CHARITY = 'charity';
    const RECEIVER_ADVISOR = 'advisor';
    const RECEIVER_ADMIN = 'admin';

    const STATUS_INITIALIZED = 'initialized';
    const STATUS_TRANSFERRED = 'transferred';
    const STATUS_FAILED = 'failed';

    private $_table = 'transfers';

    public function __construct( $options = null )
    {
        parent::__construct($options);
    }

    public function create( $data )
    {
        return $this->_db->insert($this->_table, $data);
    }

    public function take( $userId, $key, $default = null )
    {
        $SQL = "SELECT `value` FROM `{$this->_table}`
            WHERE `user_id` = ? AND `key` = ?";

        $result = $this->_db->query($SQL, [$userId, $key])->get();

        return $result ? $result['value'] : $default;
    }

    public function listTransferred( $type )
    {
        $status = self::STATUS_TRANSFERRED;

        $SQL = "SELECT SUM(`receiver_amount`) as `amount` FROM `{$this->_table}`
            WHERE `status` = '$status'
            AND `receiver_type` = ?";

        $result = $this->_db->query($SQL, [$type])->get();

        return $result['amount'];
    }

    public function getByOrderIds( $ids )
    {
        $ids = (array) $ids;

        if ( empty($ids) ) return false;

        $placeholders = array_fill(0, count( $ids ), '?');
        $values = array_values( $ids );

        $SQL = "SELECT * FROM `$this->_table` 
                WHERE `order_id` IN ( " . implode(', ', $placeholders) . " )";
    
        return $this->_db->query($SQL, $ids)->getAll() ;
    }

    public function getByOrder( $id, $from = null, $to = null)
    {
        $dbValues = array($id);

        $SQL = "SELECT * FROM `{$this->_table}`
            WHERE `order_id` = ?";

        if( !empty($from) && !empty($to) )
        {
            $SQL .= " AND `created_at` > ? AND `created_at` < ? ";
            $dbValues[] = (int) $from;
            $dbValues[] = (int) $to;
        }

        $SQL .= " ORDER BY `id` DESC ";

        return $this->_db->query($SQL, $dbValues)->getAll();
    }

    public function calcTotalAmount( $receiverId, $receiverType ,$status  )
    {
        $SQL = "SELECT SUM(`receiver_amount`) as `totalAmount` FROM `{$this->_table}`
            WHERE `receiver_id` = ?
            AND `receiver_type` = ?
            AND `status` = ?
            ORDER BY `id` DESC";

        $result = $this->_db->query($SQL, [$receiverId, $receiverType, $status])->get();

        if( empty($result['totalAmount']) ) return 0;

        return $result['totalAmount'];
    }

    public function update( $id, $data )
    {
        return $this->_db->update($this->_table, $id, $data);
    }

    public function delete( $userId, $key )
    {
        $SQL = "DELETE FROM `{$this->_table}`
        WHERE `user_id` = ? AND `key` = ?";

        $result = $this->_db->query($SQL, [$userId, $key]);

        return (bool) $result->rowCount();
    }
}