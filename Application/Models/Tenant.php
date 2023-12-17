<?php

namespace Application\Models;

use Application\Helpers\AppHelper;
use Application\Helpers\TenantHelper;
use System\Core\Model;

class Tenant extends Model
{

    private $_table = 'tenants';

    public function getCurrentTenantInfo()
    {
        $tenantName = TenantHelper::getName();

        $SQL = "SELECT * FROM `{$this->_table}` WHERE `name` = ?";

        return $this->_db->query($SQL, [$tenantName])->get();
    }

}