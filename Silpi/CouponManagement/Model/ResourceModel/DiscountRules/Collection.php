<?php

namespace Silpi\CouponManagement\Model\ResourceModel\DiscountRules;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;


class Collection extends AbstractCollection
{
    /**
     * Primary fieldname
     *
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    /**
     * Load data for preview flag
     *
     * @var bool
     */
    protected $_previewFlag;

    /**
     * Collection Class constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            'Silpi\CouponManagement\Model\DiscountRules',
            'Silpi\CouponManagement\Model\ResourceModel\DiscountRules'
        );
    }

    public function getFields()
    {
        $fields = $this->getConnection()->describeTable($this->getMainTable());
        return $fields;
    }
    
}
