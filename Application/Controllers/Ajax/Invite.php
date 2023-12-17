<?php

namespace Application\Controllers\Ajax;

use Application\Main\AuthController;
use Application\Main\ResponseJSON;
use System\Core\Model;
use System\Core\Request;
use System\Helpers\Strings;

class Invite extends AuthController
{
    public function index( Request $request )
    {
        $lang = $this->language;
        $invite = $request->post('invite');
        $eId = $request->post('eId');
        $eType = $request->post('eType');
        $type = $request->post('type');

        if ( empty($invite) ) throw new ResponseJSON('error', $lang("fill_form"));

        // else process the invite
        $invites = explode(',', $invite);

        $finalInvites = [];
        foreach ( $invites as $invite )
        {
            if ( empty($invite) ) continue;

            $finalInvites[] = trim(str_replace('@', '', $invite));
        }

        $userInfo = $this->user->getInfo();
        $users = $this->user->getInfoByUsernames($finalInvites);

        // Find the id
        if ( isset($users[$userInfo['username']]) ) throw new ResponseJSON('error', $lang('invite_error_self'));
        if ( count($finalInvites) !== count($users) ) throw new ResponseJSON('error', $lang('invite_error_not_found'));

        // Invite 
        $inviteM = Model::get('\Application\Models\Invite');

        foreach ( $users as $user )
        {
            $isInvited = $inviteM->isInvited($user['id'], $eId, $eType);
            if ( $isInvited ) continue;

            $inviteM->create(array(
                'user_id' => $user['id'],
                'entity_id' => $eId,
                'entity_type' => $eType,
                'type' => $type,
                'invited_at' => time()
            ));
        }

        throw new ResponseJSON('success');
        
    }
}