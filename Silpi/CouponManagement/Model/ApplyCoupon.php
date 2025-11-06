<?php
namespace Silpi\CouponManagement\Model;

use Silpi\CouponManagement\Api\DiscountRulesRepositoryInterface;
use Silpi\CouponManagement\Model\DiscountRulesFactory;
use Magento\Framework\Exception\LocalizedException;
use Silpi\CouponManagement\Api\ApplyCouponInterface;

class ApplyCoupon implements ApplyCouponInterface
{
    protected $discountRulesRepository;

    public function __construct(DiscountRulesRepositoryInterface $discountRulesRepository)
    {
        $this->discountRulesRepository = $discountRulesRepository;
    }

    public function applyCoupon($id, $cart)
    {
        $couponModel = $this->discountRulesRepository->getById($id);

        if (!$couponModel->getId()) {
            throw new LocalizedException(__('Invalid coupon ID.'));
        }

        $type = $couponModel->getType();
        $condition = json_decode($couponModel->getConditionDetails(), true);
        $discount = 0;
        $updatedItems = [];
        $updatedCart = [];

        $cartItems = $cart['items'] ?? [];

        $cartTotal = array_sum(array_column($cartItems, 'price'));


        switch ($type) {
            case 'cart-wise':
                if ($cartTotal >= $condition['threshold']) {
                    $discount = ($cartTotal * $condition['discount']) / 100;
                    $response = [
                        [
                            "updated_cart" => [
                                "items" => [$cart['items']],
                                "total_price" => $cartTotal,
                                "total_discount" => $discount,
                                "final_price" => $cartTotal - $discount
                            ]
                        ]
                    ];

                }
                break;

            case 'product-wise':
                $cartProducts = array_column($cartItems, 'product_id');
                // foreach ($cartItems as $item) {
                if (in_array($condition['product_id'], $cartProducts)) {
                    // Get a column of product_ids
                    $productIds = array_column($cartItems, 'product_id');

                    // Find the index where product_id = 2
                    $index = array_search($condition['product_id'], $productIds);

                    if ($index !== false) {
                        // Update the price at that index
                        $price = $cartItems[$index]['price'];
                        $cartItems[$index]['price'] = $price - ($price * $condition['discount']) / 100;
                    }

                    $newCartTotal = array_sum(array_column($cartItems, 'price'));
                    $response = [
                        [
                            "updated_cart" => [
                                "items" => [$cartItems],
                                "total_price" => $cartTotal,
                                "total_discount" => $discount,
                                "final_price" => $newCartTotal
                            ]
                        ]
                    ];

                }

                break;

            case 'bxgy':
                // $buyProducts = array_column($coupon['condition_details']['buy_products'], 'product_id');
                // $getProducts = array_column($coupon['condition_details']['get_products'], 'product_id');
                // $buyQtyRequired = $coupon['condition_details']['buy_products'][0]['quantity']; // 3
                // $getQtyFree = $coupon['condition_details']['get_products'][0]['quantity'];     // 1
                // $repetitionLimit = $coupon['condition_details']['repition_limit'];

                // $totalBuyQty = 0;
                // $totalGetQty = 0;
                // $totalPrice = 0;
                // $totalDiscount = 0;

                // // Count total eligible quantities
                // foreach ($cart['items'] as $item) {
                //     $totalPrice += $item['quantity'] * $item['price'];

                //     if (in_array($item['product_id'], $buyProducts)) {
                //         $totalBuyQty += $item['quantity'];
                //     }

                //     if (in_array($item['product_id'], $getProducts)) {
                //         $totalGetQty += $item['quantity'];
                //     }
                // }

                // // Determine how many times the offer applies
                // $maxPossibleRepetitions = min(
                //     floor($totalBuyQty / $buyQtyRequired),
                //     floor($totalGetQty / $getQtyFree)
                // );

                // $repetitions = min($maxPossibleRepetitions, $repetitionLimit);

                // // Apply discount — free products from "get" array
                // foreach ($cart['items'] as &$item) {
                //     if (in_array($item['product_id'], $getProducts) && $repetitions > 0) {
                //         $discountQty = min($item['quantity'], $repetitions * $getQtyFree);
                //         $discount = $discountQty * $item['price'];
                //         $totalDiscount += $discount;
                //         $item['price'] = $item['price']; // keep original for display
                //     }
                // }
                // unset($item);

                // $finalPrice = $totalPrice - $totalDiscount;

                // --- Prepare Output ---
                // $response = [
                //     [
                //         "updated_cart" => [
                //             "items" => [$cart['items']],
                //             "total_price" => $totalPrice,
                //             "total_discount" => $totalDiscount,
                //             "final_price" => $finalPrice
                //         ]
                //     ]
                // ];

                // Simplified logic for BxGy (Buy X Get Y)
                // $buyProducts = $condition['buy_products'];
                // $buyQty = $condition['buy_quantity'];
                // $getProducts = $condition['get_products'];
                // $getQty = $condition['get_quantity'];
                // $limit = $condition['repetition_limit'];

                // $buyCount = 0;
                // foreach ($cartItems as $item) {
                //     if (in_array($item['sku'], $buyProducts)) {
                //         $buyCount += $item['qty'];
                //     }
                // }

                // $eligibleRepeats = min(floor($buyCount / $buyQty), $limit);
                // $freeItems = [];

                // if ($eligibleRepeats > 0) {
                //     foreach ($cartItems as $item) {
                //         if (in_array($item['sku'], $getProducts)) {
                //             $item['discount_applied'] = $item['price'] * $eligibleRepeats * $getQty;
                //             $discount += $item['discount_applied'];
                //         }
                //         $updatedItems[] = $item;
                //     }
                // } else {
                //     $updatedItems = $cartItems;
                // }
                break;

            default:
                throw new LocalizedException(__('Unsupported coupon type.'));
        }

        return $response;
    }
}
