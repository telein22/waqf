<?php

namespace Application\Models;

use System\Core\Model;

class CallRequest extends Model
{
    private $_table = 'call_requests';

    public const STATUS_ACTIVE = 'active';
    public const STATUS_CLOSED = 'closed';

    public function create( $data )
    {
        return $this->_db->insert($this->_table, $data);
    }

    public function getById( $id )
    {
        $SQL = "SELECT * FROM `{$this->_table}`
            WHERE `id` = ?";

        return $this->_db->query($SQL, [$id])->get();
    }

    public function markRequestsAsClosed(int $advisorId): void
    {
        $SQL = "UPDATE `{$this->_table}` SET `status` = ?
                WHERE `advisor_id` = ? AND `status` = ?";

        $this->_db->query($SQL, [self::STATUS_CLOSED, $advisorId, self::STATUS_ACTIVE]);
    }

    public function markRequestsAsClosedById(int $requestId): void
    {
        $SQL = "UPDATE `{$this->_table}` SET `status` = ?
                WHERE `id` = ? AND `status` = ?";

        $this->_db->query($SQL, [self::STATUS_CLOSED, $requestId, self::STATUS_ACTIVE]);
    }

    public function getActiveCallRequests(int $advisorId)
    {
        $SQL = "SELECT * FROM `{$this->_table}`
            WHERE `advisor_id` = ? AND `status` = ?";

        return $this->_db->query($SQL, [$advisorId, CallRequest::STATUS_ACTIVE])->getAll();
    }
}