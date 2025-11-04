<?php

namespace Silpi\CouponManagement\Api;

interface CouponListInterface
{
    /**
     * Get list of available coupons
     *
     * @return mixed
     */
    public function getList();
}
