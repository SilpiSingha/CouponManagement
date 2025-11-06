# Silpi_CouponManegment

This is a custom Magento 2 module for coupon management.

## Module Structure
- registration.php
- etc/module.xml
- composer.json

## Installation
Place this module under `app/code/Silpi/CouponManegment` and run Magento setup upgrade commands.


Apply Coupon – Functional Overview

The Apply Coupon functionality is designed to apply a given coupon to a shopping cart, update the cart totals accordingly, and return the updated cart with the applied discount details. The feature accepts the coupon ID and cart data as input, processes applicable rules based on the coupon type, and outputs the updated cart response.

Use Cases and Implementation Details
Case 1: Cart-wise Coupons

For cart-level coupons, the discount applies to the total cart value.

The system validates the coupon condition (e.g., minimum threshold amount).

Once eligible, the total discount is reflected in the cart summary.

Example: Get 10% off on orders above ₹1000.

Output: The cart shows the discounted grand total with a single discount entry.

Case 2: Product-wise Coupons

For product-level coupons, the discount is applied directly to the specific product price.

Currently, the item price in the cart is updated directly (final price reflects the discount).

Future enhancement: Introduce dual pricing fields — original_price and final_price — to display both the base price and discounted price for better transparency.

Example: Get ₹100 off on selected SKUs.

Output: Each eligible item shows an updated price reflecting the coupon benefit.

Case 3: Buy X Get Y (BxGy) Coupons

The BxGy (Buy X Get Y) type can be implemented in multiple ways:

Quantity-based Offer – e.g., “Buy 1, Get 1 Free” for a specific SKU.

The cart dynamically adds the free product (same SKU) or adjusts pricing to reflect the equivalent discount (e.g., 50% off when buying 2).

Category/Brand-based Offer – Discounts triggered based on product category or brand.

Example: Buy any 2 T-shirts, get 1 free from the same brand.

Additional Enhancements

Coupon Status Management
Introduce a status field to activate or deactivate coupons dynamically without deletion.

Validity Period
Add start_date and expiry_date fields to enable time-bound offers such as Monsoon Sale or Festival Offers.

Payment Gateway–based Coupons
Support coupons applicable only for specific payment methods (e.g., Credit Card, Debit Card, or Wallet discounts).

Multiple Coupon Application
Enable combining multiple coupons where business rules allow cumulative discounts.

Coupon Modification Handling
Define rules for cases where a coupon’s configuration changes or is deleted after being applied:

Option 1: Retain previously applied discounts.

Option 2: Recalculate and remove invalid discounts during checkout validation.

Expected Output

The API or service returns the updated cart object, including:

Updated product prices or total cart discount

Coupon details (type, discount value, and description)

Final payable amount


