<?php

namespace Silpi\CouponManagement\Api;

interface CouponGetInterface
{
    /**
     * Retrieve coupon details by ID
     *
     * @param int $id
     * @return mixed
     */
    public function getById($id);
}
