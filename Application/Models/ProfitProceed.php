<?php

namespace Application\Models;

use System\Core\Model;

class ProfitProceed extends Model
{
    private $_table = 'profit_proceed_types';

    const TYPE_PERSONAL = 'personal';
    const TYPE_ENTITY = 'entity';
    const TYPE_SCIENTIFIC_ENDOWMENT = 'scientific_endowment';

    public function create($data)
    {
        return $this->_db->insert($this->_table, $data);
    }

    public function getAll(): array
    {
        $SQL = "SELECT * FROM `{$this->_table}`
                ORDER BY `id` ASC";

        return $this->_db->query($SQL)->getAll();
    }

    public function getById(int $id)
    {
        $SQL = "SELECT * FROM `{$this->_table}`
                WHERE `id` = ?";

        return $this->_db->query($SQL, [$id])->get();
    }
}