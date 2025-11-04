<?php

namespace Silpi\CouponManagement\Model;

use Magento\SalesRule\Api\Data\RuleInterfaceFactory;
use Magento\SalesRule\Model\RuleRepository;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Silpi\CouponManagement\Api\CouponManagementInterface;

class CouponManagement implements CouponManagementInterface
{
    protected $ruleFactory;
    protected $ruleRepository;
    protected $date;

    public function __construct(
        RuleInterfaceFactory $ruleFactory,
        RuleRepository $ruleRepository,
        DateTime $date
    ) {
        $this->ruleFactory = $ruleFactory;
        $this->ruleRepository = $ruleRepository;
        $this->date = $date;
    }

    /**
     * @inheritdoc
     */
    public function createCoupon($data)
    {
        try {
            $type = $data['type'] ?? null;
            $details = $data['details'] ?? [];

            if (!$type || empty($details)) {
                throw new LocalizedException(__('Invalid input data.'));
            }

            $threshold = $details['threshold'] ?? 0;
            $discount = $details['discount'] ?? 0;

            $rule = $this->ruleFactory->create();
            $rule->setName('Auto Coupon ' . strtoupper($type) . ' ' . time())
                ->setDescription('Generated via API')
                ->setIsActive(1)
                ->setSimpleAction('by_percent')
                ->setDiscountAmount($discount)
                ->setFromDate($this->date->date('Y-m-d'))
                ->setToDate(null)
                ->setUsesPerCustomer(1)
                ->setUsesPerCoupon(1)
                ->setCouponType(\Magento\SalesRule\Model\Rule::COUPON_TYPE_SPECIFIC)
                ->setCustomerGroupIds([0,1,2,3])
                ->setStopRulesProcessing(0)
                ->setSimpleFreeShipping(0)
                ->setConditionsSerialized($this->getCartCondition($threshold))
                ->setCouponCode('SILPI' . strtoupper(substr(md5(time()), 0, 6)));

            $this->ruleRepository->save($rule);

            return [
                'status' => true,
                'message' => 'Coupon created successfully',
                'coupon_code' => $rule->getCouponCode(),
                'discount' => $discount,
                'threshold' => $threshold
            ];

        } catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    private function getCartCondition($threshold)
    {
        $condition = [
            "type" => "Magento\\SalesRule\\Model\\Rule\\Condition\\Combine",
            "attribute" => null,
            "operator" => null,
            "value" => "1",
            "is_value_processed" => null,
            "aggregator" => "all",
            "conditions" => [
                [
                    "type" => "Magento\\SalesRule\\Model\\Rule\\Condition\\Address",
                    "attribute" => "base_subtotal",
                    "operator" => ">=",
                    "value" => $threshold,
                    "is_value_processed" => false
                ]
            ]
        ];
        return json_encode($condition);
    }
}
