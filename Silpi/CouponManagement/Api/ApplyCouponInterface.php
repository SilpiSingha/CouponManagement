<?php
namespace Silpi\CouponManagement\Api;

interface ApplyCouponInterface
{
    /**
     * Apply a specific coupon to the given cart
     *
     * @param int $id
     * @param mixed $cartData
     * @return mixed
     */
    public function applyCoupon($id, $cartData);
}
