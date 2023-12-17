<?php

namespace Application\Models;

use System\Core\Model;

class Notification extends Model
{
    const TYPE_SOCIAL = 'social';
    const TYPE_SERVICE = 'service';

    const ACTION_SYSTEM = 'system';
    const ACTION_FOLLOW = 'follow';
    const ACTION_FEED_LIKE = 'feed.like';
    const ACTION_FEED_COMMENT = 'feed.comment';
    const ACTION_MESSAGE_PENDING =  'message.pending';
    const ACTION_MESSAGE_COMPLETED = 'message.completed';
    const ACTION_WORKSHOP_COMPLETED =  'workshop.completed';    
    const ACTION_WORKSHOP_CANCELED =  'workshop.canceled';
    const ACTION_WORKSHOP_ACCEPTED =  'workshop.accepted';    
    const ACTION_WORKSHOP_REJECTED =  'workshop.rejected';
    const ACTION_WORKSHOP_USER_CANCELED = 'workshop.user_canceled';
    const ACTION_CALL_PENDING =  'call.pending';
    const ACTION_CALL_COMPLETED =  'call.completed';
    const ACTION_CALL_REMINDER =  'call.reminder';
    const ACTION_CALL_CANCELED =  'call.canceled';
    const ACTION_CALL_ACCEPTED =  'call.accepted';
    const ACTION_CALL_REJECTED =  'call.rejected';
    const ACTION_CALL_USER_CANCELED = 'call.user_canceled';
    const ACTION_CALL_REQUEST = 'call.request';
    const ACTION_CALL_REQUEST_RESOLVED = 'call.request.resolved';
    const ACTION_MESSAGE_ACCEPTED =  'message.accepted';
    const ACTION_MESSAGE_REJECTED =  'message.rejected';
    const ACTION_MESSAGE_USER_CANCELED =  'message.user_canceled';
    const ACTION_WORKSHOP_PENDING =  'workshop.pending';
    const ACTION_WORKSHOP_REMINDER =  'workshop.reminder';
    const ACTION_WORKSHOP_INVITED =  'workshop.invited';

    const CRON_MESSAGE_REMINDER = 'message.reminder';
    const CRON_MESSAGE_CANCEL = 'message.canceled';
    const CRON_WORKSHOP_AUTO_CANCELED = 'workshop_auto.canceled';
    const CRON_CALL_AUTO_CANCELLED = 'call_auto.canceled';

    private $_table = 'notifications';

    public function new()
    {
        return new NotificationItem();
    }

    public function create($data)
    {
        return $this->_db->insert($this->_table, $data);
    }

    public function update($id, $data)
    {
        return $this->_db->update($this->_table, $id, $data);
    }

    public function updateSent($userId)
    {
        $SQL = "UPDATE `{$this->_table}` 
                SET `sent` = 1
                WHERE `receiver_id` = ?";

        return $this->_db->query($SQL, [$userId]);
    }

    public function updateRead($userId, $type)
    {
        $SQL = "UPDATE `{$this->_table}` 
                SET `read` = 1
                WHERE `receiver_id` = ?";

        return $this->_db->query($SQL, [$userId]);
    }

    public function countUnseen($userId, $type = null)
    {
        $dbValues = array($userId, 0);

        $SQL = "SELECT
                    COUNT(*) as `count`
                FROM `{$this->_table}`
                WHERE `receiver_id` = ?
                AND `read` = ?";

        if (!empty($type)) 
        {
            $SQL .= " AND `type` = ?";
            $dbValues[] = $type;
        }

        $result = $this->_db->query($SQL, $dbValues)->get();

        return $result['count'];
    }

    public function all($receiverId, $type = null, $fromId = null, $skip = null, $limit = null)
    {
        $SQL = "SELECT * FROM `{$this->_table}`
                WHERE `receiver_id` = ?";

        $dbValues = [$receiverId];

        if ($type) {
            $SQL .= " AND `type` = ? ";
            $dbValues[] = $type;
        }

        if (is_numeric($fromId)) {
            $SQL .= " AND `id` <= ? ";
            $dbValues[] = $fromId;
        }

        $SQL .= "ORDER BY `id` DESC ";

        if (is_numeric($skip) && is_numeric($limit)) {
            $SQL .= " LIMIT ?, ?";
            $dbValues[] = $skip;
            $dbValues[] = $limit;
        }

        return $this->_db->query($SQL, $dbValues)->getAll();
    }
}

class NotificationItem
{
    private $_senderId;

    private $_receiverId;

    private $_type;

    private $_actionType;

    private $_template;

    public function setSenderId($senderId)
    {
        $this->_senderId = $senderId;

        return $this;
    }

    public function setReceiverId($receiverId)
    {
        $this->_receiverId = $receiverId;

        return $this;
    }

    public function setType($type)
    {
        $this->_type = $type;

        return $this;
    }

    public function actionType($actionType)
    {
        $this->_actionType = $actionType;
    }

    public function setTemplate($template)
    {
        $this->_template = $template;
    }
}
