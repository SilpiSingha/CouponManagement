<?php

namespace Silpi\CouponManagement\Api;

use Silpi\CouponManagement\Api\Data\DiscountRulesInterface;

interface DiscountRulesRepositoryInterface
{
    /**
     * Save Data
     *
     * @param object $discountRules object
     *
     * @return \Silpi\CouponManagement\Api\Data\DiscountRulesInterface
     **/
    public function save(DiscountRulesInterface $discountRules);

    /**
     * Get Data By Id
     *
     * @param int $id Load Data by Id
     *
     * @return \Silpi\CouponManagement\Api\Data\DiscountRulesInterface
     **/
    public function getById($id);

    /**
     * Delete Object Data
     *
     * @param object $discountRules Object
     *
     * @return \Silpi\CouponManagement\Api\Data\DiscountRulesInterface
     **/
    public function delete(DiscountRulesInterface $discountRules);

    /**
     * Delete Data By ID
     *
     * @param int $id Delete Object By Id
     *
     * @return \Silpi\CouponManagement\Api\Data\DiscountRulesInterface
     **/
    public function deleteById($id);
}
