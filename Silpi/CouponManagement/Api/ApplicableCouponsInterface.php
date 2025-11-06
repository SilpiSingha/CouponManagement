<?php
namespace Silpi\CouponManagement\Api;

interface ApplicableCouponsInterface
{
    /**
     * Fetch all applicable coupons for a given cart
     *
     * @param mixed $cartData
     * @return mixed
     */
    public function getApplicableCoupons($cartData);
}
