<?php

namespace Application\Models;

use System\Core\Model;

class Log extends Model
{  
    private $_table = 'logs';

    public function update( $id, $data )
    {
        return $this->_db->update($this->_table, $id, $data);
    }

    public function create( $data ) {
        return $this->_db->insert($this->_table, $data);
    }

    public function all( $from, $to )
    {
        $dbValues = array();

        $SQL = "SELECT * FROM `$this->_table`";

        if (!empty($from) && !empty($to)) {

            $SQL .= ' WHERE `created_at` > ? AND `created_at` < ? ';
            $dbValues[] = (int) $from;
            $dbValues[] = (int) $to;
        }

        $SQL .= " ORDER BY `id` DESC ";

        return $this->_db->query($SQL, $dbValues)->getAll();
    }
}