<?php

namespace Application\Models;

use System\Core\Model;

class Payment extends Model
{
    const METHOD_APPLE_PAY = 'apple';
    const METHOD_VISA = 'visa';
    const METHOD_STC = 'stc';
    const METHOD_MADA = 'mada';
    const METHOD_FREE = 'free';

    const BRANDS_MAPPING = [
        'stc_pay' => 'stc',
        'master' => 'visa',
        'visa' => 'visa',
        'mada' => 'mada',
    ];

    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED = 'failed';
    const STATUS_REFUND_INITIATED = 'refund_initiated';
    const STATUS_REFUNDED = 'refunded';


    private $_table = 'payments';
   
    public function create( $data )
    {
        return $this->_db->insert($this->_table, $data);
    }

    public function update( $id, $data )
    {
        return $this->_db->update($this->_table, $id, $data);
    }

    public function take( $id )
    {
        $SQL = "SELECT * FROM `{$this->_table}` WHERE `id` = ?";

        return $this->_db->query($SQL, [$id])->get();
    }

    public function all( $orderId = null, $status = null )
    {
        $dbValues = [];
        $SQL = "SELECT * FROM `{$this->_table}`";

        $where = array();
        $dbValues = array();

        
        if ( !empty($orderId) )
        {
            $where[] = " `order_id` = ? ";
            $dbValues[] = $orderId;
        }

        if( !empty($status) )
        {
            $where[] = ' `status` = ?';
            $dbValues[] = $status;
        }

        if ( !empty($where) )
        {
            $SQL .= " WHERE ";
            $SQL .= implode(' AND ', $where);
        }

        $SQL .= " ORDER BY `id` DESC";

        return $this->_db->query($SQL, $dbValues)->getAll();
    }

    public function getSuccessPayment( $orderId = null)
    {
        $SQL = "SELECT * FROM `{$this->_table}`
                WHERE `order_id` = ?
                AND `status` = 'success'";
        return $this->_db->query($SQL, [$orderId])->get();
    }
}