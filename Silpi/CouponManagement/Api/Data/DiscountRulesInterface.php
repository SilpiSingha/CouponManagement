<?php
namespace Silpi\CouponManagement\Api\Data;


interface DiscountRulesInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ID  = 'id';

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Set ID
     *
     * @param int $id set discountRules id
     *
     * @return \Silpi\CouponManagement\Api\Data\DiscountRulesInterface
     */
    public function setId($id);

    /**
     * Get Stores
     *
     * @return array
     */
    public function getStores();
}
