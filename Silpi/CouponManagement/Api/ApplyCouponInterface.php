<?php
namespace Silpi\CouponManagement\Api;

interface ApplyCouponInterface
{
    /**
     * Apply a specific coupon to the given cart
     *
     * @param int $id
     * @param mixed $cart
     * @return mixed
     */
    public function applyCoupon($id, $cart);
}
