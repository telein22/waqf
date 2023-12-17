<?php

namespace Application\Helpers;

use Application\Models\Call;
use Application\Models\Conversation;
use Application\Models\Workshop;

class ServiceHelper
{
    public static function generateRef( $userId, $id, $type  )
    {
        switch( $type )
        {
            case Workshop::ENTITY_TYPE:
                $type = 1;
                break;
            case Call::ENTITY_TYPE:
                $type = 2;
                break;
            case Conversation::ENTITY_TYPE:
                $type = 3;
                break;
        }

        return str_pad($userId, 4, '0', STR_PAD_LEFT) . '' . str_pad($id, 4, '0', STR_PAD_LEFT) . '' . str_pad($type, 4, '0', STR_PAD_LEFT);
    }

}