<?php

namespace Application\Controllers\Ajax\Admin;

use Application\Main\AuthController;
use Application\Main\ResponseJSON;
use Application\Models\Feed;
use System\Core\Model;
use System\Core\Request;

class BlockedFeedWord extends AuthController
{
    public function hide( Request $request )
    {
        $id = $request->post('id');

        $data = array(
            'entity_id' => $id,
            'entity_type' => Feed::ENTITY_TYPE
        );

        $hiddenM = Model::get('\Application\Models\HiddenEntities');
        $hiddenM->create( $data );

        throw new ResponseJSON('success', 'Successfully hidden');
    }

    public function show( Request $request )
    {
        $id = $request->post('id');

        $hiddenM = Model::get('\Application\Models\HiddenEntities');
        $hiddenM->delete( $id );

        throw new ResponseJSON('success', 'Success');
    }
}