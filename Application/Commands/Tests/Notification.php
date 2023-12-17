<?php

namespace Application\Commands\Tests;

use Application\Models\Notification as ModelsNotification;
use System\Core\CLICommand;
use System\Core\Model;

class Notification extends CLICommand
{
    const SEND_NOTIFICATION = 1;

    public function run($params)
    {
        $choose = $this->read(<<<'HTML'
What do you want to do?
1. Send Notification
2. Delete notification

HTML
);

        switch( $choose )
        {
            case self::SEND_NOTIFICATION:
                $this->_sendNotification();
                break;
        }

    }

    private function _sendNotification()
    {
        $this->write("Send notification\n-----------------------");
        $sender = $this->read("Sender Id");
        $receiver = $this->read("Receiver Id");
        $type = $this->read("Do you want to send social notification (y/n)?", function( $input ){
            return in_array($input, ['y', 'n']);
        });

        $type = $type === 'y' ? ModelsNotification::TYPE_SOCIAL : ModelsNotification::TYPE_SERVICE;
        
        /**
         * @var \Application\Models\Notification
         */
        $notifiM = Model::get('\Application\Models\Notification');
        $notifiM->create([
            'sender_id' => $sender,
            'receiver_id' => $receiver,
            'type' => $type,
            'action_type' => "system",
            'data' => json_encode([ 'text' => "system_test_notification" ]),
            'read' => 0,
            'sent' => 0,
            'created_at' => time()
        ]);

        $this->write("\n\nNotification sent successfully");
    }

}