<?php

use Application\Controllers\Auth;
use System\Core\Config;

$routes = Config::get('Routes');
$routes->set(array(

    'brd' => [

        '/image-optimize' => 'Optimize::index',

        // Test url
        '/' => 'Home::index',
        '/specialties/(:string)' => 'Home::index',

        '/login' => 'Auth::login',
        '/login/(:num)/(:string)' => 'Auth::login',
        '/logout' => 'Auth::logout',
        '/register' => 'Auth::register',
        '/forgot-password' => 'Auth::forgotPassword',
        '/change-password/(:string)' => 'Auth::changePassword',
        '/terms' => 'StaticPage::terms',
        '/faq' => 'StaticPage::faq',
        '/blogs/(:string)' => 'Blog::index',

        '/search' => 'Search::index',
        '/search/(:string)' => 'Search::index',

        // Liked Feeds
        '/liked-feeds' => 'LikedFeeds::index',

        // Invite
        '/invite' => 'Invite::index',

        // Dashboard
        '/dashboard' => 'Dashboard::index',
        '/dashboard/(:string)' => 'Dashboard::index',
        '/dashboard/entities/(:string)' => 'Dashboard::index',

        '/verify-account' => 'Auth::verify',
        '/account-blocked' => 'Auth::Blocked',

        // Profile
        '/profile' => 'Profile::index',
        '/profile/(:num)' => 'Profile::index',
        '/profile/(:num)/(:string)' => 'Profile::index',
        '/profile/edit' => 'Profile::edit',
        '/profile/edit/social' => 'Profile::editSocial',
        '/profile/edit/bank' => 'Profile::editBank',
        '/profile/edit/change-password' => 'Profile::editPwd',

        // Outer profile
        '/outer-profile/(:num)' => 'OuterProfile::index',
        '/outer-profile/(:num)/(:string)' => 'OuterProfile::index',

        // Feeds
        '/feeds' => 'Feed::index',

        '/all-feeds' => 'FeedSingle::allFeeds',

        // Single feed
        '/feed/(:num)' => 'FeedSingle::index',

        // Workshops
        '/workshops/checkout' => "Workshops::checkout",
        '/workshops/find' => "Workshops::find",
        '/workshops/find/(:num)' => "Workshops::find",
        '/workshops/(:string)' => 'Workshops::index',

        // Language
        '/language/change' => "Language::change",

        // Settings
        '/settings' => "Settings::index",

        // Calls
        '/calls/find/(:num)' => "Calls::find",
//        '/calls/request/(:num)' => "Calls::request",
        '/calls/request/(:num)' => "CallRequest::handle",
        '/calls/manage' => "Calls::manage",
        '/calls/add' => "Calls::addSlot",
        '/calls/checkout' => "Calls::checkout",
        '/calls/(:string)' => 'Calls::index',

        // Waiting room
        '/waiting-room/sessions/(:num)' => "WaitingRoom::session",
        '/waiting-room/calls/(:num)' => "WaitingRoom::call",

        '/hashtags' => 'HashTag::index',
        '/categories' => 'Category::index',

        // Business Card
        '/business-card' => "Cards\BusinessCard::generate",
        '/workshop-poster' => "Cards\WorkshopPoster::generate",
        '/social-media-card' => "Cards\SocialMediaCard::generate",


        // Messaging
        '/messaging/manage' => 'Messaging::manage',
        '/messaging/view/(:num)' => 'Messaging::view',
        '/messaging/submit-answer' => 'Messaging::submitAnswer',
        '/messaging/(:string)' => 'Messaging::index',

        // Reviews
        '/review/(:num)/(:string)' => 'Review::index',
        '/review/(:num)/(:string)/(:num)/(:num)' => 'Review::submit',

        // Checkout
        '/checkout' => "Checkout::index",
        '/checkout/success' => "Checkout::success",
        '/checkout/fail' => "Checkout::fail",
        '/checkout/pay' => "Checkout::pay",

        // Earnings
        '/earnings/' => "Earning::index",
        '/earnings/freelance-document/' => "Earning::freelanceDocument",
        '/earnings/wallet-transactions-csv' => "Earning::walletTransactionsCSV",
        '/earnings/withdrawal-requests-csv' => "Earning::withdrawalRequestsCSV",

        // Orders
        // '/order/requests' => "Order::requests", // Don't need is as client request auto approve
        '/order/complete' => "Order::complete",
        '/order/cancel/(:num)' => "Order::cancel",
        '/order/my' => "Order::my",
        '/order/view/(:num)' => "Order::view",

        // Notifications
        '/notification/(:string)' => "Notification::index",

        // Share
        '/share-on-twitter' => "SocialMediaShare::twitter",
        '/share-on-linkedin' => "SocialMediaShare::linkedin",

        //Entity
        '/entities' => 'Admin\Entity\Dashboard::index',
        '/entities/users' => 'Admin\Entity\User::index',
        '/entities/users-csv' => 'Admin\Entity\Users::csv',
        '/entities/cancellation-membership/(:num)' => 'Admin\Entity\User::cancellationMembership',
        '/entities/workshops' => 'Admin\Entity\Workshops::index',
        '/entities/calls' => 'Admin\Entity\Calls::slots',
        '/entities/service-log/(:string)/(:num)' => "Admin\Entity\ServiceLog::index",
        '/entities/billings' => 'Admin\Entity\Billings::index',
        '/entities/billing-csv' => 'Admin\Entity\Billings::csv',
        '/entities/donations' => 'Admin\Entity\Billings::index',
        '/entities/donation-csv' => 'Admin\Entity\Billings::csv',
        '/entities/coupons' => 'Admin\Entity\Coupons::index',
        '/entities/add-coupon' => 'Admin\Entity\Coupons::addCoupon',
        '/entities/coupon-edit/(:num)' => 'Admin\Entity\Coupons::editCoupon',
        '/entities/coupon-uses/(:string)' => 'Admin\Entity\Coupons::uses',


        // Admin
        '/admin' => 'Admin\Dashboard::index',
        '/admin/billings' => 'Admin\Billings::index',
        '/admin/billing-csv' => 'Admin\Billings::csv',
        '/admin/wallets' => 'Admin\Wallets::index',
        '/admin/wallets/(:num)' => 'Admin\Wallets::details',
        '/admin/wallets-csv' => 'Admin\Wallets::csv',
        '/admin/wallets/(:num)/csv' => 'Admin\Wallets::detailsToCsv',
        '/admin/withdrawal-requests' => 'Admin\WithdrawalRequests::index',
        '/admin/withdrawal-requests-csv' => 'Admin\WithdrawalRequests::csv',
        '/admin/withdrawal-requests/(:num)/freelance-document' => 'Admin\WithdrawalRequests::freelanceDocument',
        '/admin/withdrawal-requests/change-status' => 'Admin\WithdrawalRequests::changeStatus',
        '/admin/users' => 'Admin\Users::index',
        '/admin/users-csv' => 'Admin\Users::csv',
        '/admin/feeds' => 'Admin\Feeds::index',
        '/admin/settings' => 'Admin\Settings::index',
        '/admin/coupons' => 'Admin\Coupons::index',
        '/admin/add-coupon' => 'Admin\Coupons::addCoupon',
        '/admin/coupon-edit/(:num)' => 'Admin\Coupons::editCoupon',
        '/admin/coupon-uses/(:string)' => 'Admin\Coupons::uses',
        '/admin/edit-user/(:num)' => 'Admin\Users::editUser',
        '/admin/charities' => 'Admin\Charities::index',
        '/admin/add-charity' => 'Admin\Charities::addCharity',
        '/admin/edit-charity/(:num)' => 'Admin\Charities::editCharity',
        '/admin/import-charity' => 'Admin\Charities::import',
        '/admin/entities' => 'Admin\Entities::index',
        '/admin/add-entity' => 'Admin\Entities::addEntity',
        '/admin/entities/(:num)/associates' => 'Admin\Entities::showAssociates',
        '/admin/tracker' => 'Admin\Tracker::index',
        '/admin/blocked-words' => 'Admin\BlockedWords::index',
        '/admin/add-blocked-words' => 'Admin\BlockedWords::addBlocked',
        '/admin/edit-blocked-words/(:num)' => 'Admin\BlockedWords::editBlocked',
        '/admin/specialties' => 'Admin\Specialists::index',
        '/admin/add-specialists' => 'Admin\Specialists::addSpecialty',
        '/admin/edit-specialist/(:num)' => 'Admin\Specialists::editSpecialty',
        '/admin/sub-specialties' => 'Admin\SubSpecialists::index',
        '/admin/add-sub-specialists' => 'Admin\SubSpecialists::addSpecialty',
        '/admin/edit-sub-specialist/(:num)' => 'Admin\SubSpecialists::editSpecialty',
        '/admin/search-history' => 'Admin\SearchHistory::index',
        '/admin/feed-with-blocked' => 'Admin\BlockedWords::feeds',
        '/admin/messages' => 'Admin\Messages::index',
        '/admin/transfers/(:num)' => 'Admin\Billings::transfers',
        '/admin/workshops' => 'Admin\Workshops::index',
        '/admin/calls' => 'Admin\Calls::slots',
        '/admin/logs' => 'Admin\Logs::index',
        '/admin/live-session' => "Admin\LiveSession::index",
        '/admin/service-log/(:string)/(:num)' => "Admin\ServiceLog::index",
        '/admin/commissions' => "Admin\Commissions::index",
        '/admin/edit-commission/(:num)' => "Admin\Commissions::edit",
        '/admin/add-commission' => "Admin\Commissions::add",

        '/admin/profit-proceeds' => "Admin\ProfitProceeds::index",


        // Admin reviews
        '/admin/reviews/(:string)' => 'Admin\Reviews::index',
        '/admin/reviews/view/(:num)/(:string)' => 'Admin\Reviews::view',

        //Ajax
        '/ajax' => [

            // Auth
            'post:/auth/resend-otp' => "Ajax\Auth::resendOTP",


            // User
            'post:/user/search' => "Ajax\User::search",

            'post:/ping' => 'Ajax\Ping::index',

            'post:/user/search-by-spec' => 'Ajax\User::searchBySpec',

            // Feed
            'post:/feed-post' => 'Ajax\Feed::post',
            'post:/feed-get' => 'Ajax\Feed::take',
            'post:/feed-delete' => 'Ajax\Feed::delete',
            'post:/feed-more' => 'Ajax\Feed::more',
            'post:/feed-more-liked' => 'Ajax\Feed::moreLiked',
            'post:/feed-more-comment' => 'Ajax\Feed::moreComment',
            'post:/feed-more-media' => 'Ajax\Feed::moreMedia',
            'post:/feed-more-profile' => 'Ajax\Feed::moreProfile',

            // Feed Outer
            'post:/outer-feed-more-liked' => 'Ajax\OuterFeed::moreLiked',
            'post:/outer-feed-more-comment' => 'Ajax\OuterFeed::moreComment',
            'post:/outer-feed-more-media' => 'Ajax\OuterFeed::moreMedia',
            'post:/outer-feed-more-profile' => 'Ajax\OuterFeed::moreProfile',

            // Notification
            'post:/noti-more' => 'Ajax\Notification::more',

            // Comments
            'post:/comment-post' => 'Ajax\Comment::post',
            'post:/comment-get' => 'Ajax\Comment::getComment',
            'post:/comment-load' => 'Ajax\Comment::load',
            'post:/comment-delete' => 'Ajax\Comment::delete',

            // Expression
            'post:/expression-toggle' => 'Ajax\Expression::toggle',

            // Follow
            'post:/follow-toggle' => 'Ajax\Follow::toggle',
            'post:/follow-more-follower' => 'Ajax\Follow::moreFollower',
            'post:/follow-more-folllowing' => 'Ajax\Follow::moreFollowing',

            // Profile
            'post:/profile-upload-cover' => 'Ajax\Profile::uploadCover',
            'post:/profile-upload-avatar' => 'Ajax\Profile::uploadAvatar',

            //Location
            'post:/location/cities' => 'Ajax\Location::getCities',
            'post:/user/advisors-by-entities' => 'Ajax\Commissions::getAdvisorsByEntity',

            // Sub Specialty
            'post:/sub-specialty' => 'Ajax\SubSpecialty::getBySpecialty',


            // Workshop
            'post:/workshop/create' => 'Ajax\Workshop::create',
            'post:/workshop/search' => 'Ajax\Workshop::search',
            'post:/workshop/more' => 'Ajax\Workshop::more',
            'post:/workshop/find-more' => 'Ajax\Workshop::findMore',
            'post:/workshop/find/search' => 'Ajax\Workshop::findSearch',
            'post:/workshop/search-profile-workshop' => 'Ajax\Workshop::profileWorkshop',
            'post:/workshop/start' => 'Ajax\Workshop::start',
            'post:/workshop/startAndJoin' => 'Ajax\Workshop::startAndJoin',
            'post:/workshop/delete' => 'Ajax\Workshop::delete',
            'post:/workshop/join' => 'Ajax\Workshop::join',
            'post:/workshop/cancel' => 'Ajax\Workshop::cancel',
            'post:/workshop/complete' => 'Ajax\Workshop::complete',

            // Calls
            'post:/calls/slot/delete' => 'Ajax\Calls::deleteSlot',
            'post:/calls/slot/search' => 'Ajax\Calls::searchSlot',
            'post:/calls/start' => 'Ajax\Calls::start',
            'post:/calls/startAndJoin' => 'Ajax\Calls::startAndJoin',
            'post:/calls/complete' => 'Ajax\Calls::complete',
            'post:/calls/cancel' => 'Ajax\Calls::cancel',
            'post:/calls/search' => 'Ajax\Calls::search',
            'post:/calls/more' => 'Ajax\Calls::more',
            'post:/calls/join' => 'Ajax\Calls::join',

            // Messaging
            'post:/messaging/book' => 'Ajax\Messaging::book',
            'post:/messaging/search' => 'Ajax\Messaging::search',
            'post:/messaging/more' => 'Ajax\Messaging::more',

            // Participant
            'post:/participant-list' => 'Ajax\Participant::list',

            // Invite
            'post:/invite' => 'Ajax\Invite::index',

            // Language
            'post:/change-lang' => 'Ajax\Language::changeLang',
            'post:/change-lang-cookie' => 'Ajax\Language::changeLangCookie',

            // Instruction
            'post:/skip-instruction' => 'Ajax\Instruction::skipInstruction',


            // Checkout
            'post:/checkout/prepare' => 'Ajax\Checkout::prepare',
            'post:/checkout/coupon/apply' => 'Ajax\Checkout::applyCoupon',
            'post:/checkout/coupon/remove' => 'Ajax\Checkout::removeCoupon',

            // Order
            'post:/order/create' => 'Ajax\Order::create',
            'post:/order/more-request' => 'Ajax\Order::moreRequest',
            'post:/order/request/accept' => 'Ajax\Order::accept',
            'post:/order/request/decline' => 'Ajax\Order::decline',
            'post:/order/more-orders' => 'Ajax\Order::moreOrders',
            'post:/earningLogs/more-logs' => 'Ajax\EarningLog::more',

            // Search
            'post:/search' => 'Ajax\Search::index',
            'post:/dashboard/search' => 'Ajax\Suggestion::dashboardSearch',

            // Suggestions
            'post:/suggestions/more' => 'Ajax\Suggestion::more',

            'post:/business-card/send' => 'Ajax\Cards\BusinessCard::send',
            'post:/workshop-poster/send' => 'Ajax\Cards\WorkshopPoster::send',
            'post:/social-media-card/send' => 'Ajax\Cards\SocialMediaCard::send',

            // Contact with us
            'post:/contact-with-us' => 'Ajax\Support::send',

            // Check username availability
            'post:/check-username' => 'Ajax\User::checkUsernameAvailability',

            // Withdrawal request
            'get:/withdrawal-requests' => 'Ajax\WithdrawalRequest::index',
            'post:/add-withdrawal-requests' => 'Ajax\WithdrawalRequest::create',

            // Admin Ajax
            '/admin' => [
                'post:/user-verification' => 'Ajax\Admin\UserVerification::index',
                'get:/data-table/(:string)' => 'Ajax\Admin\DataTable::index',
                'post:/delete-coupon' => 'Ajax\Admin\Coupon::delete',
                'post:/delete-blocked-words' => 'Ajax\Admin\Blocked::delete',
                'post:/hide-blocked-feed-word' => 'Ajax\Admin\BlockedFeedWord::hide',
                'post:/show-blocked-feed-word' => 'Ajax\Admin\BlockedFeedWord::show',
                'post:/block-user' => 'Ajax\Admin\User::block',
                'post:/unblock-user' => 'Ajax\Admin\User::unblock',
                'post:/show-conversation' => 'Ajax\Admin\Conversations::show',
                'post:/show-billings-details' => 'Ajax\Admin\Order::showDetails',
                'post:/show-transfer-info' => 'Ajax\Admin\Order::transferInfo',
                'post:/show-charities-list' => 'Ajax\Admin\Order::charityList',
                'post:/order/hold' => 'Ajax\Admin\Order::hold',
            ],
        ],

    ],

));
