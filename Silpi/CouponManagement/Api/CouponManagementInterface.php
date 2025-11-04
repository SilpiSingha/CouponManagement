<?php

namespace Silpi\CouponManagement\Api;

interface CouponManagementInterface
{
    /**
     * Create a new coupon dynamically
     *
     * @param mixed $data
     * @return mixed
     */
    public function createCoupon($data);
}
