<?php

namespace Configs;

use System\Core\Config;

$big  = Config::get("Big");
$big->set([
    'server_url' => 'https://scale.telein.net',
    'create_meeting_url' => 'http://bigapi.telein.net/createMeeting.php',
    'join_meeting_url' => 'http://bigapi.telein.net/joinMeeting.php',
    'end_meeting_url' => 'http://bigapi.telein.net/endMeeting.php',
    'check_meeting_url' => 'http://bigapi.telein.net/checkMeeting.php',
]);