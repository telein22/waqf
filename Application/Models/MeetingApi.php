<?php

namespace Application\Models;

use System\Core\Model;

class MeetingApi extends Model
{
    public function index( $data )
    {
        $postdata = http_build_query($data['fields']);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $data['url']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        return curl_exec($ch);
    }
}