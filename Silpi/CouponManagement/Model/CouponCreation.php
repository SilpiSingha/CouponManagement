<?php

namespace Silpi\CouponManagement\Model;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Silpi\CouponManagement\Api\CouponManagementInterface;
use Silpi\CouponManagement\Model\DiscountRulesFactory;

class CouponCreation implements CouponManagementInterface
{
    protected $discountRulesFactory;
    protected $date;

    public function __construct(
        DiscountRulesFactory $discountRulesFactory,
        DateTime $date
    ) {
        $this->discountRulesFactory = $discountRulesFactory;
        $this->date = $date;
    }

    /**
     * @inheritdoc
     */
    public function createCoupon()
    {
        try {
            $payload = @file_get_contents('php://input');
            $request = json_decode($payload, true);

            if (empty($request) || empty($request['type'])) {
                throw new LocalizedException(__('Request data or coupon type is missing.'));
            }

            $array = [];
            $array['type'] = $request['type'];
            $array['condition_details'] = json_encode($request['condition_details'] ?? []);
            $coupon = $this->discountRulesFactory->create()->setData($array)->save();

            return json_encode([
                'status' => true,
                'message' => 'Coupon created successfully',
                'id' => $coupon->getId(),
                'type' => $coupon->getType(),
                'details' => $coupon->getConditionDetails(),
            ]);

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

    /**
     * Update coupon details by ID
     */
    public function updateCoupon($id, $couponData)
    {
        $table = $this->resource->getTableName('discount_rules');

        if (!$this->isCouponExist($id)) {
            throw new LocalizedException(__('Coupon with ID %1 not found.', $id));
        }

        $this->connection->update(
            $table,
            $couponData,
            ['id = ?' => (int)$id]
        );

        return ['success' => true, 'message' => __('Coupon updated successfully.')];
    }

    /**
     * Delete coupon by ID
     */
    public function deleteCoupon($id)
    {
        $table = $this->resource->getTableName('discount_rules');

        if (!$this->isCouponExist($id)) {
            throw new LocalizedException(__('Coupon with ID %1 not found.', $id));
        }

        $this->connection->delete($table, ['id = ?' => (int)$id]);

        return ['success' => true, 'message' => __('Coupon deleted successfully.')];
    }

    /**
     * Check if coupon exists
     */
    private function isCouponExist($id)
    {
        $table = $this->resource->getTableName('discount_rules');
        $select = $this->connection->select()
            ->from($table, ['id'])
            ->where('id = ?', (int)$id);
        return (bool)$this->connection->fetchOne($select);
    }
}
