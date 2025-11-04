<?php

namespace Silpi\CouponManagement\Model;

use Magento\SalesRule\Model\ResourceModel\Rule\CollectionFactory;
use Silpi\CouponManagement\Api\CouponListInterface;

class CouponList implements CouponListInterface
{
    protected $ruleCollectionFactory;

    public function __construct(
        CollectionFactory $ruleCollectionFactory
    ) {
        $this->ruleCollectionFactory = $ruleCollectionFactory;
    }

    /**
     * @inheritdoc
     */
    public function getList()
    {
        try {
            $collection = $this->ruleCollectionFactory->create();
            $collection->addFieldToFilter('is_active', 1);

            $data = [];

            foreach ($collection as $rule) {
                $data[] = [
                    'rule_id'        => $rule->getRuleId(),
                    'name'           => $rule->getName(),
                    'coupon_code'    => $rule->getCouponCode(),
                    'discount_type'  => $rule->getSimpleAction(),
                    'discount_value' => $rule->getDiscountAmount(),
                    'from_date'      => $rule->getFromDate(),
                    'to_date'        => $rule->getToDate(),
                    'uses_per_coupon'=> $rule->getUsesPerCoupon(),
                    'uses_per_customer' => $rule->getUsesPerCustomer(),
                    'is_active'      => (bool)$rule->getIsActive()
                ];
            }

            return [
                'status' => true,
                'count' => count($data),
                'coupons' => $data
            ];

        } catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }
}
