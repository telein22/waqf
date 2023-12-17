<?php

namespace Application\Models;

use System\Core\Model;

class Specialty extends Model
{
    private $_table = 'specialties';

    public function getTable()
    {
        return $this->_table;
    }

    public function all()
    {
        $SQL = "SELECT * FROM `{$this->_table}`";

        return $this->_db->query($SQL)->getAll();
    }

    public function getById($id)
    {
        $SQL = "SELECT * FROM `{$this->_table}` WHERE `id` = ?";

        return $this->_db->query($SQL, [$id])->get();
    }

    public function getBySpecialty($slug)
    {
        $SQL = "SELECT * FROM `{$this->_table}` WHERE `specialty_en` = ?";

        return $this->_db->query($SQL, [$slug])->get();
    }

    public function update($id, $data)
    {
        return $this->_db->update($this->_table, $id, $data);
    }

    public function create($data)
    {
        return $this->_db->insert($this->_table, $data);
    }

    public function getByIdList($ids)
    {
        if (empty($ids)) return [];

        $ids = (array) $ids;
        $SQL = "SELECT * FROM `{$this->_table}` 
            WHERE `id` IN ";

        $placeholder = array_fill(0, count($ids), '?');
        $values = array_values($ids);

        $SQL .= " (" . implode(', ', $placeholder) . ")";

        $result = $this->_db->query($SQL, $values)->getAll();

        if (!$result) return [];

        $output = [];
        foreach ($result as $row) {
            $output[$row['id']] = $row;
        }

        return $output;
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
