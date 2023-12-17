<?php

namespace Application\Models;

use Application\Values\Coupon as CouponValue;
use System\Core\Model;

class Coupons extends Model
{
    const TYPE_FIXED = 0;
    const TYPE_PERCENT = 1;

    private $_table = 'coupons';

    public function all(array $where = null)
    {
        $SQL = "SELECT coupons.*, users.name as username, workshops.name as entity_name, creator.name as created_by FROM `{$this->_table}`
                LEFT OUTER JOIN `users` ON (`users`.`id` = `coupons`.`user_id`)
                LEFT OUTER JOIN `workshops` ON (`workshops`.`id` = `coupons`.`entity_id` AND `coupons`.`entity_type` = 'workshop')
                LEFT OUTER JOIN `users` as creator ON (`creator`.`id` = `coupons`.`created_by`)
                WHERE `coupons`.`deleted` <> 1";

        if (!empty($where)) {
            foreach ($where as $key => $value) {
                if (is_array($value)) {
                    $ids = implode($value, ',');
                    $SQL .= " AND {$key} in ({$ids})";
                } else {
                    $SQL .= " AND {$key} = {$value}";
                }
            }
        }

        $SQL .= " ORDER BY `coupons`.`id` DESC";

        return $this->_db->query($SQL)->getAll();
    }

    public function getCoupon( $id )
    {
        $SQL = "SELECT * FROM `{$this->_table}`
                WHERE `id` = ?";

        return $this->_db->query($SQL, [$id])->get();
    }

    public function getCouponByCode( $code )
    {
        $SQL = "SELECT * FROM `{$this->_table}`
                WHERE `code` = ?";

        return $this->_db->query($SQL, [$code])->get();
    }

    public function deleteById( $id )
    {
        $SQL = "UPDATE `{$this->_table}` SET `deleted` = 1 WHERE `id` = ?";

        return $this->_db->query($SQL, [$id]) ;
    }

    public function update( $data, $id )
    {
        return $this->_db->update($this->_table, $id, $data);
    }

    public function create( $data )
    {
        return $this->_db->insert($this->_table, $data);
    }

    public function getAmountAfterCoupon(float $amount, CouponValue $couponInfo): float
    {
        if ($amount == 0 || $couponInfo->isFullDiscount($amount)) {
            return 0;
        }

        return $couponInfo->type == self::TYPE_FIXED ? ($amount - $couponInfo->amount)
            : ($amount - ($couponInfo->amount / 100 * $amount));
    }

    public function IsUserHasRightToUseTheCoupon(CouponValue $coupon, string $entityType, string $entityId, int $loggedUserId): bool
    {
        if (is_null($coupon->userId) && is_null($coupon->entityId) && is_null($coupon->entityType)) {
            return true;
        }

        $result = true;
        if ($coupon->userId && $coupon->userId != $loggedUserId) {
            $result = false;
        }

        if ($coupon->entityType && $coupon->entityId && ($entityId != $coupon->entityId || $entityType != $coupon->entityType)) {
            $result = false;
        }

        return $result;
    }

}