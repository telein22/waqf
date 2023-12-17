<?php

namespace Application\Models;

use System\Core\Model;

class Commission extends Model
{
    private $_table = 'commissions';

    public function create($data)
    {
        return $this->_db->insert($this->_table, $data);
    }

    public function update(int $id, int $entityCommission, int $advisorCommission)
    {
        $SQL = "UPDATE `{$this->_table}` SET `entity_commission` = ? , `advisor_commission` = ?
                WHERE `id` = ?";

        $this->_db->query($SQL, [$entityCommission, $advisorCommission, $id]);
    }

    public function getAll(): array
    {
        $SQL = "SELECT * FROM `{$this->_table}`
                ORDER BY `id` DESC";

        return $this->_db->query($SQL)->getAll();
    }

    public function getById(int $id)
    {
        $SQL = "SELECT * FROM `{$this->_table}`
                WHERE `id` = ?";

        return $this->_db->query($SQL, [$id])->get();
    }

    public function getByEntityAndAdvisor(int $entityId, int $advisorId)
    {
        $SQL = "SELECT * FROM `{$this->_table}`
                WHERE `entity_id` = ? AND `advisor_id` = ?";

        return $this->_db->query($SQL, [$entityId, $advisorId])->get();
    }
}