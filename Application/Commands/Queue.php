<?php

namespace Application\Commands;

use Application\Models\Queue as ModelsQueue;
use Application\ThirdParties\AWS\S3;
use Aws\S3\S3Client;
use Exception;
use System\Core\CLICommand;
use System\Core\Model;
use System\Helpers\URL;
use Application\Models\Email as ModelEmail;
use Application\Models\Language;

class Queue extends CLICommand
{
    public function run( $params )
    {
        unset($params[1]);

        foreach ( $params as $param )
        {
            switch($param)
            {
                case ModelsQueue::TYPE_NOTIFICATION:                    
                case ModelsQueue::TYPE_EMAIL:
                case ModelsQueue::TYPE_TRANSFER:
                    break;
                default:
                    throw new Exception("Unknown parameter is provided");
            }
        }

        // IF params are found fetch the matched params
        // else fetch from all
        /**
         * @var \Application\Models\Queue
         */
        $queueM = Model::get('\Application\Models\Queue');
        $queues = $queueM->fetch($params);

        $ids = array();
        foreach ( $queues as $queue )
        {
            $ids[] = $queue['id'];
            $data = json_decode($queue['data'], true);

            switch( $queue['type'] )
            {
                case ModelsQueue::TYPE_NOTIFICATION:
                    $this->_handleNotification($queue['id'], $data);
                    break;
                case ModelsQueue::TYPE_EMAIL:
                    $this->_handleEmail($queue['id'], $data);
                    break;
                case ModelsQueue::TYPE_TRANSFER:
                    $this->_handleTransfer($queue['id'], $data);
                    break;
            }
        }

        $queueM->delete( $ids );
    }

    private function _handleTransfer( $id, $data )
    {
        $transferM = Model::get('\Application\Models\Transfer');
        $transferM->update( $data['transfer_id'], array(
            'status' => 'transferred'
        ) );

        $queueM = Model::get('\Application\Models\Queue');
        // $queueM->delete( $id );
    }

    private function _handleNotification( $id, $data )
    {
        $notiM = Model::get('\Application\Models\Notification');
        $data = array_merge($data, [ 'created_at' => time() ]);
        $notiM->create($data);

        $queueM = Model::get('\Application\Models\Queue');
        // $queueM->delete( $id );
    }

    private function _handleEmail( $id, $data )
    {
        if( !empty($data['user_id']) )
        {

            /**
             * @var \Application\Models\User
             */
            $userM = Model::get('\Application\Models\User');
            $userInfo = $userM->getUser( $data['user_id'] );
            /**
             * @var Language
             */
            $language = Model::get(Language::class, 'brd');
            $lang = $language->getUserLang($userInfo['id']);
    
            /**
             * @var ModelEmail
             */
            $emailM = Model::get(ModelEmail::class, 'brd');
            $mail = $emailM->new(); 

            
        
            $mail->to([$userInfo['email'], $userInfo['name']]);
            $mail->body('Emails/' . $data['view'], [
                'info' => $data['entity_info'],
                'name' => $userInfo['name'],
                'url' => URL::full('')
            ], $lang);
            $mail->subject($data['subject'], null , $lang);

            

            if (isset($data['attachment_path']) && !empty($data['attachment_path'])) {
                $mail->addAttachment($data['attachment_path']);
            }

            $mail->send();
    
            $queueM = Model::get('\Application\Models\Queue');
            // $queueM->delete( $id );
        }
    }
}