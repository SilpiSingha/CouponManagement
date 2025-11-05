<?php
namespace Silpi\CouponManagement\Model;

class DiscountRules extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'silpi_couponmanagement_discountrules';

    protected $_cacheTag = 'silpi_couponmanagement_discountrules';

    protected $_eventPrefix = 'silpi_couponmanagement_discountrules';

    protected function _construct()
    {
        $this->_init('Silpi\CouponManagement\Model\ResourceModel\DiscountRules');
    }
    

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }
}