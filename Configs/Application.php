<?php

use System\Core\Config;
use Application\Helpers\AppHelper;

$application = Config::get('Application');
$application->set([

    /**
     * Application version
     */
    'version' => $_ENV['APP_VERSION'],

    /**
     * Environment could be prod (Production), dev (Development) and staging
     */
    'environment' => $_ENV['APP_ENV'],

    'base_url' => $_ENV['APP_BASE_URL'],

    'api_base_url' => $_ENV['API_BASE_URL'],

    /**
     * This framework can support
     * Multiple urls remember that, thats why this parameter is taken
     * in an array
     */
    'site_urls' => [
        'brd' => $_ENV['APP_SITE_URL'],
        // 'site1' => 'site1.com',
        // 'site2' => 'site2.com'
        // You can add more web in below.
    ],

    /**
     * Force redirect http to https
     */
    // 'force_https' => false,

    /**
     * Allowed characters to url
     */
    // 'allowed_uri_chars' => 'A-z0-9',

    /**
     * Enable modules globally
     */
    'enable_system_modules' => [
        'brd' => [
            '\System\Models\Session' => [
                'name' => "brd"
            ],

            '\Application\Models\Language' => [
                'default_lang' => 'ar'
            ],

            // Enable user model globally.
            '\Application\Models\User' => null,

            '\Application\Models\Tracker' => null,

            // Enable Hooks
            '\System\Models\Hooks' => [
                'binds' => [                    
                    'book_now.on_click' => [
                        '\Application\Hooks\Service::validateCanBook',
                    ],
                    'auth.on_forget_password_submit' => [
                        '\Application\Hooks\Email::forgetPassword',
                    ],
                    'ping.collect' => [
                        '\Application\Hooks\Feed::checkLatest',
                        '\Application\Hooks\Notification::unreadCount',
                        '\Application\Hooks\Order::pendingCount',
                        '\Application\Hooks\Ping::onComplete',
                        '\Application\Hooks\Workshop::upcomingOrCurrent',
                        '\Application\Hooks\Call::upcomingOrCurrent',
                    ],
                    'feed.create' => [
                        '\Application\Hooks\Feed::create'
                    ],
                    'feed.on_create' => [
                        '\Application\Hooks\Feed::onCreate',
                        '\Application\Hooks\Log::onFeedCreate'
                    ],
                    'order.on_create' => [
                        '\Application\Hooks\Log::orderOnCreate'
                    ],
                    'order.success' => [
                        '\Application\Hooks\Order::success',
                        '\Application\Hooks\Log::orderOnSuccess'
                    ],
                    'order.accept_validation' => [
                        '\Application\Hooks\Order::acceptValidate'
                    ],
                    'order.on_accept' => [
                        '\Application\Hooks\Order::accept',
                        '\Application\Hooks\EarningLog::orderAccept',
                        '\Application\Hooks\Log::orderOnAccept'
                    ],
                    'order.cancel_validation' => [
                        '\Application\Hooks\Order::cancelValidate'
                    ],
                    'order.user_cancel_validate' => [
                        '\Application\Hooks\Order::userCancelValidate'
                    ],
                    'order.on_cancel' => [
                        '\Application\Hooks\Order::cancel',
                        '\Application\Hooks\Notification::orderCancel',
                        '\Application\Hooks\EarningLog::orderCancel',
                        '\Application\Hooks\Email::orderReject',
                        '\Application\Hooks\Log::orderOnReject'
                    ],
                    'order.on_user_cancel' => [
                        '\Application\Hooks\Order::cancel',
                        '\Application\Hooks\Notification::orderCancel',
                        '\Application\Hooks\EarningLog::orderCancel',
                        '\Application\Hooks\Log::orderOnReject'
                    ],
                    'call_slot.on_create' => [
                        '\Application\Hooks\CallSlot::onCreate',
                    ],
                    'call.on_customize_call' => [
                        '\Application\Hooks\Call::callRequest',
                    ],
                    'call_request.on_handle' => [
                        '\Application\Hooks\CallRequest::onHandle',
                    ],
                    'workshop.on_create' => [
                        '\Application\Hooks\Workshop::onCreate',
                        '\Application\Hooks\Log::onWorkshopCreate'
                    ],
                    'workshop.on_delete' => [
                        '\Application\Hooks\Workshop::onDelete',
                    ],
                    'service.start_validation' => [
                        '\Application\Hooks\Service::validateStart'
                    ],
                    'service.join_validation' => [
                        '\Application\Hooks\Service::validateJoin'
                    ],
                    'service.cancel_validation' => [
                        '\Application\Hooks\Service::validateCancel'
                    ],
                    'service.complete_validation' => [
                        '\Application\Hooks\Service::validateComplete'
                    ],
                    'service.on_start' => [
                        "\Application\Hooks\Service::onStart",
                    ],
                    'service.on_cancel' => [
                        '\Application\Hooks\Service::onCancel',
                        '\Application\Hooks\Log::onServiceCancel',
                    ],
                    'service.on_complete' => [
                        '\Application\Hooks\Service::onComplete',
                        '\Application\Hooks\Log::onServiceComplete',
                    ],
                    'service.on_join' => [
                        '\Application\Hooks\Service::onJoin',
                    ],
                    'order.refund' => [
                        '\Application\Hooks\Order::refund'
                    ],
                    'settings.on_submit_support' => [
                        '\Application\Hooks\Email::support'
                    ],
                    'feed.on_like' => [
                        '\Application\Hooks\Notification::onLike'
                    ],
                    'feed.on_comment' => [
                        '\Application\Hooks\Notification::onComment'
                    ],
                    'feed.on_follow' => [
                        '\Application\Hooks\Notification::onFollow'
                    ],
                    // Admin hooks
                    // 'admin.order.on_status_cancel' => [ // I think this hook will not require
                    //     '\Application\Hooks\Order::cancel',
                    //     '\Application\Hooks\EarningLog::orderCancel'
                    // ],
                    'admin.order.on_status_hold' => [],
                ]
            ],
            '\Application\Models\Email' => [
                'use_smtp' => true,
                'debug' => false,
                'smtp_host' => 'smtp.gmail.com',
                'smtp_username' => 'noreply@telein.net',
                'smtp_password' => 'tohg loov qytc zdmv',
                'smtp_port' =>  465,
                'smtp_encryption' => 'ssl',
                'from_email' => 'noreply@telein.net',
                'from_name' => 'Telein'
            ],


            // '\System\Models\Email' => [
            //     'use_smtp' => true,
            //     'debug' => true,
            //     'smtp_host' => '',
            //     'smtp_username' => '',
            //     'smtp_password' => '',
            //     'smtp_port' =>  0,
            //     'smtp_encryption' => 'ssl',
            //     'from_email' => '',
            //     'from_name' => ''
            // ]
            //  Other models you can add here.
        ],

        // 'site2' => [

        //     '\System\Models\Session' => [
        //         'name' => "photo"
        //     ],
        //      Other models you can add here.
        //  ]

    ],

    /**
     * Directory where views are stored.
     */
    'view_directory' => 'Application/Views',

    /**
     * Directory where controllers are stored.
     */
    'controller_directory' => 'Application/Controllers',

    // If you have created any custom config file 
    // You can add it here for auto load.
    // Example is given bellow.
    'extra_configs' => [
        'Configs/Website',
        'Configs/Big',
        'Configs/HyperPay',
    ],

    // Where is composer autoload file?
    'composer_autoload_path' => 'Application/Composer/vendor/autoload.php',
    
    // /**
    //  * Set an 404 error page controller
    //  */
    'page_404' => [
        'action' => 'Error',
        'method' => 'error404'
    ],

    /**
     * Control the application memory limit.
     * Please also use this memory limit if you are using AutoOptimizer for images,
     * If auto optimizer don't work then please increase the limit based on your need first.
     * Then look somewhere else
     */
    'memory_limit' => '512M',

    'enable_auto_optimize' => true,

    'auto_optimizer_storage_directory' => 'Application/Cache',

    'auto_optimizer_route' => 'image-optimize',

    /**
     * Define the commands for cli
     */
    'commands' => [
        // Test commands
        '_notification' => 'Application\Commands\Tests\Notification',
        'queue' => 'Application\Commands\Queue',
        'sessionNotify' => 'Application\Commands\SessionNotify',
        'messageReminder' => 'Application\Commands\MessageReminder',
        'messageCancel' => 'Application\Commands\MessageCancel',
        'workshopCancel' => 'Application\Commands\WorkshopCancel',
        'callCancel' => 'Application\Commands\CallCancel',
        'refund' => 'Application\Commands\Refund',
        'markAsComplete' => 'Application\Commands\MarkAsCompleted',
        'startWorkshop' => 'Application\Commands\StartWorkshop',
    ],

    'Pusher' => [
        'app_id' => $_ENV['PUSHER_APP_ID'],
        'key' => $_ENV['PUSHER_APP_KEY'],
        'secret' => $_ENV['PUSHER_APP_SECRET'],
        'cluster' => $_ENV['PUSHER_APP_CLUSTER']
    ],

    'Whatsapp' => [
        'token' => $_ENV['WHATSAPP_TOKEN'],
        'instance' => $_ENV['WHATSAPP_INSTANCE']
    ],

    'AWS' => [
        'key' => $_ENV['AWS_KEY'],
        'secret' => $_ENV['AWS_SECRET'],
        'region' => $_ENV['AWS_REGION'],
        'bucket' => $_ENV['AWS_BUCKET'],
        'base_url' => $_ENV['AWS_BASE_URL'],
    ],

    'Recaptcha' => [
        'key' => $_ENV['RECAPTCHA_KEY'],
        'secret' => $_ENV['RECAPTCHA_SECRET']
    ],

    'Dyte' => [
        'organization_id' => $_ENV['DYTE_ORGANIZATION_ID'],
        'api_key' => $_ENV['DYTE_API_KEY']
    ],

    'API' => [
        'api_key' => $_ENV['TELEIN_API_KEY']
    ]
]);
