<?php

namespace Configs;

use Application\Helpers\AppHelper;
use System\Core\Config;

$website  = Config::get("Website");
$website->set([
    'expire_verification_token' => 24 * 60 * 60,

    'images_support' => [
        'image/jpg',
        'image/png',
        'image/jpeg',
    ],
    
    'max_image' => 10485760,
    
    'service_start_padding' => [ 0, 180 ],
    
    'admin_email' => 'ceo@telein.net',

    'conversation_timeout' => 48 * 60 * 60,

    'user_cancel_padding' => 24 * 60 * 60,

    'message_reminder_time' => 2,

    'message_expiry_time' => 172800,

    'order_final_percent_cut' => 2,

    'mada_percent_cut' => 1,

    'visa_percent_cut' => 2.5,

    'stc_percent_cut' => 1.7,

    'american_express_percent_cut' => 2.6,

    'advisor_charity_percent' => 80,

    'admin_percent' => 20,

    // This is for starting any service padding
    'join_padding' => 3, // in minutes

    // Whats app number for admin support
    'whatsapp_number'  => $_ENV['ADMIN_WHATSAPP_NUMBER'],

    // Max allowed live call count
    // use this to limit the users
    'max_allowed_concurrent_session' => 150,

    // Call service duration set
    'call_duration' => 15, // IN minutes
    
    // 'admin_email' => 'Abdulaziz-alhassan@hotmail.com',

    // Waiting room limit for the sessions
    'session_waiting_room_limit' => 15, // IN minutes

    // Waiting room limit for the calls
    'call_waiting_room_limit' => 5, // IN minutes

    // email reminder limit for the sessions
    'session_email_reminder_limit' => 900, // IN second

    // email reminder limit for the calls
    'call_email_reminder_limit' => 300, // IN second

    'telein_registration_number' => 1010787629,

    'default_meeting_provider' => $_ENV['DEFAULT_MEETING_PROVIDER'],

    'zoom_provider_base_url' => $_ENV['ZOOM_PROVIDER_BASE_URL'],
    'dyte_provider_base_url' => $_ENV['DYTE_PROVIDER_BASE_URL'],

    'items_per_page' => 20,
    'meeting_link_expiry' => $_ENV['MEETING_LINK_EXPIRY'],
    'minimumWithdrawalAmount' => $_ENV['MINIMUM_WITHDRAWAL_AMOUNT']
]);