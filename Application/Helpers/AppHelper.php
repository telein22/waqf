<?php

namespace Application\Helpers;

use Application\Models\Tenant;
use System\Core\Application;
use System\Core\Config;
use System\Core\Model;

class AppHelper
{
    public const PRODUCTION = 'prod';
    public const STAGING = 'staging';
    public const DEVELOPMENT = 'dev';

    public const ZOOM_PROVIDER = 'zoom';
    public const BIG_BLUE_BUTTON_PROVIDER = 'bigbluebutton';
    public const DYTE_PROVIDER = 'dyte';

    public static function getEnvironment(): string
    {
        return Config::get('Application')->environment;
    }

    public static function isEnvironment(string $env): bool
    {
        return self::getEnvironment() == $env;
    }

    public static function getBaseUrl(): string
    {
        return Config::get('Application')->base_url;
    }

    public static function isMeetingProvider(string $provider): bool
    {
        return Config::get('Website')->default_meeting_provider == $provider;
    }

    public static function getDefaultMeetingProvider(): string
    {
        return Config::get('Website')->default_meeting_provider;
    }

    public static function getFileFromS3(string $fileName): string
    {
        $config = Application::config();
        $awsBaseUrl = Config::get('Application')->AWS['base_url'];

        return "$awsBaseUrl/{$fileName}?{$config->version}";
    }

    public static function getMinimumWithdrawalAmount(): int
    {
        return Config::get('Website')->minimumWithdrawalAmount;
    }
}