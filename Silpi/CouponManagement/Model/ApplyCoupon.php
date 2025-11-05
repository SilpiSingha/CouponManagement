<?php
namespace Silpi\CouponManagement\Model;

use Silpi\CouponManagement\Api\ApplyCouponInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\LocalizedException;

class ApplyCoupon implements ApplyCouponInterface
{
    protected $resource;

    public function __construct(ResourceConnection $resource)
    {
        $this->resource = $resource;
    }

    public function applyCoupon($id, $cartData)
    {
        $connection = $this->resource->getConnection();
        $table = $this->resource->getTableName('discount_rules');

        // Fetch coupon details
        $coupon = $connection->fetchRow(
            $connection->select()->from($table)->where('coupon_id = ?', (int) $id)
        );

        if (!$coupon) {
            throw new LocalizedException(__('Invalid coupon ID.'));
        }

        $type = $coupon['coupon_type'];
        $condition = json_decode($coupon['condition_details'], true);
        $discount = 0;
        $updatedItems = [];

        $cartItems = $cartData['items'] ?? [];
        $cartTotal = array_sum(array_column($cartItems, 'price'));

        switch ($type) {
            case 'cart-wise':
                if ($cartTotal >= $condition['threshold']) {
                    $discount = ($cartTotal * $condition['discount']) / 100;
                    foreach ($cartItems as $item) {
                        $updatedItems[] = [
                            'sku' => $item['sku'],
                            'price' => $item['price'],
                            'discount_applied' => round(($item['price'] / $cartTotal) * $discount, 2)
                        ];
                    }
                }
                break;

            case 'product-wise':
                foreach ($cartItems as $item) {
                    if (in_array($item['sku'], $condition['applicable_skus'])) {
                        $itemDiscount = ($item['price'] * $condition['discount_value']) / 100;
                        $discount += $itemDiscount;
                        $item['discount_applied'] = $itemDiscount;
                    }
                    $updatedItems[] = $item;
                }
                break;

            case 'bxgy':
                // Simplified logic for BxGy (Buy X Get Y)
                $buyProducts = $condition['buy_products'];
                $buyQty = $condition['buy_quantity'];
                $getProducts = $condition['get_products'];
                $getQty = $condition['get_quantity'];
                $limit = $condition['repetition_limit'];

                $buyCount = 0;
                foreach ($cartItems as $item) {
                    if (in_array($item['sku'], $buyProducts)) {
                        $buyCount += $item['qty'];
                    }
                }

                $eligibleRepeats = min(floor($buyCount / $buyQty), $limit);
                $freeItems = [];

                if ($eligibleRepeats > 0) {
                    foreach ($cartItems as $item) {
                        if (in_array($item['sku'], $getProducts)) {
                            $item['discount_applied'] = $item['price'] * $eligibleRepeats * $getQty;
                            $discount += $item['discount_applied'];
                        }
                        $updatedItems[] = $item;
                    }
                } else {
                    $updatedItems = $cartItems;
                }
                break;

            default:
                throw new LocalizedException(__('Unsupported coupon type.'));
        }

        $response = [
            'coupon_id' => $id,
            'coupon_type' => $type,
            'total_discount' => round($discount, 2),
            'cart_items' => $updatedItems,
            'final_total' => round($cartTotal - $discount, 2)
        ];

        return $response;
    }
}
