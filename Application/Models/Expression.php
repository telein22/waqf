<?php

namespace Application\Models;

use System\Core\Model;

class Expression extends Model
{

    const LIKE = 'like';

    private $_table = 'expressions';

    public function getTable()
    {
        return $this->_table;
    }

    public function create( $data )
    {
        return $this->_db->insert($this->_table, $data);
    }

    public function delete( $entityType, $entityId, $type = null, $id = null )
    {
        $SQL = "DELETE FROM `{$this->_table}`
            WHERE `entity_type` = ? AND `entity_id` = ? ";
        
        $dbValues = [$entityType, $entityId];

        if ( $type )
        {
            $SQL .= " AND `type` = ? ";
            $dbValues[] = $type;
        }

        if ( $id )
        {
            $SQL .= " AND `id` = ? ";
            $dbValues[] = $id;
        }

        $userM = Model::get(User::class);
        $user = $userM->getInfo();

        if(isset($user['id'])) {
            $SQL .= " AND `user_id` = {$user['id']} ";
        }

        $result = $this->_db->query($SQL, $dbValues)->rowCount();

        return (bool) $result;
    }

    public function countExpression( array $entities, $type )
    {

        $SQL = "SELECT
                `entity_type`,
                `entity_id`,
                `type`,
                COUNT(`id`) as `total`
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
        $SQL .= '(' . $eWheres . ') AND `type` = ?';
        $dbValues[] = $type;

        $SQL .= " GROUP BY `entity_type`, `entity_id`, `type` ";

        return $this->_db->query($SQL, $dbValues)->getAll();

    }

    
    public function isExpressed( $userId, array $entities, $type )
    {

        $SQL = "SELECT `id`, `entity_type`, `entity_id`, `type` FROM `{$this->_table}`
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
        $SQL .= '(' . $eWheres . ') AND `type` = ? AND `user_id` = ?';
        $dbValues[] = $type;
        $dbValues[] = $userId;

        return $this->_db->query($SQL, $dbValues)->getAll();

    }

}