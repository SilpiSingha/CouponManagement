<?php
namespace Silpi\CouponManagement\Model\ResourceModel;

class DiscountRules extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('discount_rules', 'entity_id');
    }
} 