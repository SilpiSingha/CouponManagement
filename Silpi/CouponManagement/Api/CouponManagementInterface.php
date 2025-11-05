<?php

namespace Silpi\CouponManagement\Api;

interface CouponManagementInterface
{
    /**
     * Create a new coupon dynamically
     *
     * 
     * @return mixed
     */
    public function createCoupon();

     /**
     * Update a specific coupon by ID
     *
     * @param int $id
     * @param mixed $couponData
     * @return array
     */
    public function updateCoupon($id, $couponData);

    /**
     * Delete a specific coupon by ID
     *
     * @param int $id
     * @return array
     */
    public function deleteCoupon($id);
}
