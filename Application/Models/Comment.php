<?php

namespace Application\Models;

use System\Core\Model;

class Comment extends Model
{
    private $_table = 'comments';

    public function getTable()
    {
        return $this->_table;
    }

    public function create( $data )
    {
        return $this->_db->insert($this->_table, $data);
    }

    public function getComment( $id )
    {
        $SQL = "SELECT * FROM `{$this->_table}` WHERE `id` = ?";
        return $this->_db->query($SQL, [$id])->get();
    }

    public function getCommentByIds( $ids )
    {
        if ( empty($ids) ) return [];

        $ids = (array) $ids;
        $SQL = "SELECT * FROM `{$this->_table}` 
            WHERE `id` IN ";
        
        $placeholder = array_fill(0, count($ids), '?');
        $values = array_values($ids);

        $SQL .= " (" . implode(', ', $placeholder) . ")";

        $result = $this->_db->query($SQL, $values)->getAll();
        
        if ( !$result ) return [];

        $output = [];
        foreach ( $result as $row )
        {
            $output[$row['id']] = $row;
        }

        return $output;
    }

    public function getComments( array $entities, $limit = 5, $fromId = null )
    {
        $SQL = "SELECT *,
            @row_number:=CASE
                WHEN @entity = CONCAT(`entity_type`, `entity_id`) THEN @row_number + 1
                ELSE 1
            END as `row`,
            @entity:=CONCAT(`entity_type`, `entity_id`) as `join_entity`
            FROM `{$this->_table}`
            WHERE ";

        $dbValues = [];
        $eWheres = [];
        foreach ( $entities as $key => $values )
        {
            $values = (array) $values;

            foreach ( $values as $value )
            {
                $eWheres[] = " (`entity_type` = ? AND `entity_id` = ?) ";
                $dbValues[] = $key;
                $dbValues[] = $value;
            }
            
        }

        $eWheres = implode(' OR ', $eWheres);
        $SQL .= $eWheres;

        if ( is_numeric($fromId) )
        {
            $SQL .= " AND `id` <= ? ";
            $dbValues[] = $fromId;
        }
        $SQL .= " ORDER BY `entity_type`, `entity_id`, `id` DESC";
        
        $this->_db->query("SET @row_number = 0, @entity=NULL");

        $SQL = "SELECT * FROM ($SQL) AS `comments` WHERE `row` <= ?";
        $dbValues[] = $limit;

        $result = $this->_db->query($SQL, $dbValues)->getAll();
        
        return $result;
    }

    public function countComment( array $entities )
    {

        $SQL = "SELECT `entity_type`, `entity_id`, COUNT(`id`) as `total` FROM `{$this->_table}`
            WHERE ";

        $dbValues = [];
        $eWheres = [];
        foreach ( $entities as $key => $values )
        {
            $values = (array) $values;

            foreach ( $values as $value )
            {
                $eWheres[] = " (`entity_type` = ? AND `entity_id` = ?) ";
                $dbValues[] = $key;
                $dbValues[] = $value;
            }
            
        }

        $eWheres = implode(' OR ', $eWheres);
        $SQL .= $eWheres;

        $SQL .= " GROUP BY `entity_type`, `entity_id` ";

        return $this->_db->query($SQL, $dbValues)->getAll();

    }

    public function delete( $id )
    {
        $SQL = "DELETE FROM `{$this->_table}` WHERE `id` = ?";
        return (bool) $this->_db->query($SQL, [$id])->rowCount();
    }
}