<?php

namespace Application\Helpers;

use Application\Models\Tenant;
use System\Core\Config;
use System\Core\Model;

class TenantHelper
{
    public static function getName(): string
    {
        if (!isset($_SERVER['HTTP_HOST'])) {
            return Config::get('Application')->tenant_name;
        }

        $host = $_SERVER['HTTP_HOST'];
        $hostParts = explode('.', $host);

        return $hostParts[0];
    }

    public static function getId(): ?int
    {
        $tenantM = Model::get(Tenant::class);
        $tenant = $tenantM->getCurrentTenantInfo();

        return $tenant['id'] ?? null;
    }
}