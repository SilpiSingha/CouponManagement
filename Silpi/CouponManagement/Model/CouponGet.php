<?php

namespace Silpi\CouponManagement\Model;

use Silpi\CouponManagement\Api\DiscountRulesRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Silpi\CouponManagement\Api\CouponGetInterface;

class CouponGet implements CouponGetInterface
{
    protected $discountRulesRepository;

    public function __construct(
        DiscountRulesRepositoryInterface $discountRulesRepository
    ) {
        $this->discountRulesRepository = $discountRulesRepository;
    }

    /**
     * @inheritdoc
     */
    public function getById($id)
    {
        try {
            $rule = $this->discountRulesRepository->getById($id);

            return [
                'status' => true,
                'coupon' => [
                    'rule_id'          => $rule->getEntityId(),
                    // 'name'             => $rule->getName(),
                    // 'description'      => $rule->getDescription(),
                    'coupon_code'      => $rule->getCode(),
                    // 'discount_type'    => $rule->getSimpleAction(),
                    // 'discount_value'   => $rule->getDiscountAmount(),
                    // 'from_date'        => $rule->getFromDate(),
                    // 'to_date'          => $rule->getToDate(),
                    // 'uses_per_coupon'  => $rule->getUsesPerCoupon(),
                    // 'uses_per_customer'=> $rule->getUsesPerCustomer(),
                    // 'is_active'        => (bool)$rule->getIsActive(),
                    // 'customer_group_ids' => $rule->getCustomerGroupIds(),
                    // 'conditions_serialized' => $rule->getConditionsSerialized()
                ]
            ];

        } catch (NoSuchEntityException $e) {
            return [
                'status' => false,
                'message' => __('No coupon found with ID %1', $id)
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
