<?php

namespace Application\Models;

use System\Core\Model;

class SearchHistory extends Model
{  
    private $_table = 'search_history';

    public function update( $data, $id )
    {
        return $this->_db->update($this->_table, $id, $data);
    }

    public function create( $data ) {
        return $this->_db->insert($this->_table, $data);
    }

    public function all( $from = null, $to = null )
    {
        $userM = Model::get('\Application\Models\User');
        $userT = $userM->getTable();
        $values = array();

        $SQL = "SELECT *, COUNT(*) as `count` FROM `{$this->_table}` as `sh`
                INNER JOIN `{$userT}` as `u`
                ON (`sh`.`user_id` = `u`.`id`)";

        if( !empty($from) && !empty($to) )
        {
            $SQL .= 'WHERE `sh`.`created_at` > ? AND `sh`.`created_at` < ?';

            $values[] = (int) $from;
            $values[] = (int) $to;
        }

        $SQL .= ' GROUP BY `sh`.`user_id`,`sh`.`term`';
                
        return $this->_db->query($SQL, $values)->getAll();
    }

    public function getById( $id )
    {
        $SQL = "SELECT * FROM `{$this->_table}` WHERE `id` = '$id'";
        return $this->_db->query($SQL)->get();
    }

    public function deleteById( $id )
    {
        $SQL = "DELETE FROM `{$this->_table}` WHERE `id` = ?";

        return $this->_db->query($SQL, [$id]) ;
    }

}