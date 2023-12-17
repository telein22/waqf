<?php

namespace Application\Models;

use System\Core\Model;

class Queue extends Model
{
    const TYPE_NOTIFICATION = 'notification';
    const TYPE_EMAIL = 'email';
    const TYPE_TRANSFER = 'transfer';

    private $_table = 'queues';

    public function create( $data )
    {
        return $this->_db->insert($this->_table, $data);
    }

    public function fetch( $type = null, $limit = null )
    {
        $type = (array) $type;

        $SQL = "SELECT * FROM `{$this->_table}`
                ORDER BY `priority` DESC";

        $dbValues = [];
        if ( !empty($type) )
        {
            $placeholders = array_fill(0, count($type), '?');
            $values = array_values($type);

            $SQL = " WHERE `type` IN (" . implode(', ', $placeholders) .  ") ";
            $dbValues = array_merge($dbValues, $values);
        }

        return $this->_db->query($SQL, $dbValues)->getAll();
    }

    public function delete( $ids )
    {
        $ids = (array) $ids;

        if ( empty($ids) ) return false;

        $placeholders = array_fill(0, count( $ids ), '?');
        $values = array_values( $ids );

        $SQL = "DELETE FROM `{$this->_table}` 
                WHERE `id` IN ( " . implode(', ', $placeholders) . " )";
    
        return (bool) $this->_db->query($SQL, $ids)->rowCount() ;
    }
}