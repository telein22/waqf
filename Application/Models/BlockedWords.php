<?php

namespace Application\Models;

use System\Core\Model;

class BlockedWords extends Model
{  
    private $_table = 'blocked_words';

    public function update( $data, $id )
    {
        return $this->_db->update($this->_table, $id, $data);
    }

    public function create( $data ) {
        return $this->_db->insert($this->_table, $data);
    }

    public function all()
    {
        $SQL = "SELECT * FROM `{$this->_table}`";

        return $this->_db->query($SQL)->getAll();
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

    public function getByWord( $word )
    {
        $SQL = "SELECT * FROM `{$this->_table}` WHERE `word` = ?";
        return $this->_db->query($SQL, [$word])->get();
    }

    public function getBlockedWordByWords( $words )
    {
        if ( empty($words) ) return [];

        $words = (array) $words;
        $SQL = "SELECT * FROM `{$this->_table}` 
            WHERE `word` IN ";
        
        $placeholder = array_fill(0, count($words), '?');
        $values = array_values($words);

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