<?php

namespace Application\Models;

use System\Core\Model;

class Message extends Model
{

    const ENTITY_TYPE = 'message';

    private $_table = 'messages';

    public function create($data)
    {
        return $this->_db->insert($this->_table, $data);
    }

    public function getMessages( $conversationId )
    {
        $SQL = "SELECT * FROM `{$this->_table}` WHERE `conversation_id` = ?";

        return $this->_db->query($SQL, [$conversationId])->getAll();
    }

    public function getById( $id )
    {
        $SQL = "SELECT * FROM `{$this->_table}`
        WHERE `id` = ?";

        return $this->_db->query($SQL, [$id])->get();
    }

    public function getInfoByIds( $ids )
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
}