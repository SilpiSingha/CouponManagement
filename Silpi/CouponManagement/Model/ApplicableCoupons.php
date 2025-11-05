<?php
namespace Silpi\CouponManagement\Model;

use Silpi\CouponManagement\Api\ApplicableCouponsInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\LocalizedException;

class ApplicableCoupons implements ApplicableCouponsInterface
{
    protected $resource;

    public function __construct(ResourceConnection $resource)
    {
        $this->resource = $resource;
    }

    public function getApplicableCoupons($cartData)
    {
        $connection = $this->resource->getConnection();
        $table = $this->resource->getTableName('discount_rules');

        $cartItems = $cartData['items'] ?? [];
        if (empty($cartItems)) {
            throw new LocalizedException(__('Cart is empty.'));
        }

        // Calculate total cart amount
        $cartTotal = 0;
        $cartSkus = [];
        foreach ($cartItems as $item) {
            $cartTotal += $item['price'] * $item['qty'];
            $cartSkus[] = $item['sku'];
        }

        // Fetch active coupons
        $coupons = $connection->fetchAll(
            $connection->select()->from($table)->where('is_active = ?', 1)
        );

        $applicableCoupons = [];

        foreach ($coupons as $coupon) {
            $condition = json_decode($coupon['condition_details'], true);
            $discountValue = 0;
            $isApplicable = false;

            switch ($coupon['coupon_type']) {
                case 'cart-wise':
                    if ($cartTotal >= ($condition['threshold'] ?? 0)) {
                        $discountValue = ($cartTotal * ($condition['discount'] ?? 0)) / 100;
                        $isApplicable = true;
                    }
                    break;

                case 'product-wise':
                    $discountValue = 0;
                    foreach ($cartItems as $item) {
                        if (in_array($item['sku'], $condition['applicable_skus'] ?? [])) {
                            $discountValue += ($item['price'] * ($condition['discount_value'] ?? 0)) / 100;
                            $isApplicable = true;
                        }
                    }
                    break;

                case 'bxgy':
                    $buyProducts = $condition['buy_products'] ?? [];
                    $buyQty = $condition['buy_quantity'] ?? 0;
                    $getProducts = $condition['get_products'] ?? [];
                    $getQty = $condition['get_quantity'] ?? 0;
                    $limit = $condition['repetition_limit'] ?? 1;

                    $buyCount = 0;
                    foreach ($cartItems as $item) {
                        if (in_array($item['sku'], $buyProducts)) {
                            $buyCount += $item['qty'];
                        }
                    }

                    $eligibleRepeats = min(floor($buyCount / $buyQty), $limit);
                    if ($eligibleRepeats > 0) {
                        $isApplicable = true;
                        foreach ($cartItems as $item) {
                            if (in_array($item['sku'], $getProducts)) {
                                $discountValue += $item['price'] * $getQty * $eligibleRepeats;
                            }
                        }
                    }
                    break;
            }

            if ($isApplicable && $discountValue > 0) {
                $applicableCoupons[] = [
                    'coupon_id' => $coupon['coupon_id'],
                    'coupon_code' => $coupon['coupon_code'],
                    'coupon_name' => $coupon['coupon_name'],
                    'coupon_type' => $coupon['coupon_type'],
                    'estimated_discount' => round($discountValue, 2)
                ];
            }
        }

        return [
            'total_applicable_coupons' => count($applicableCoupons),
            'coupons' => $applicableCoupons
        ];
    }
}
