# Silpi_CouponManegment

This is a custom Magento 2 module for coupon management.

## Module Structure
- registration.php
- etc/module.xml
- composer.json

## Installation
Place this module under `app/code/Silpi/CouponManegment` and run Magento setup upgrade commands.



Future Implimentation

Coupon Type can be managed by using other table where by using API we can save different coupon type and use that id to pass while creating a coupon 
so that in future different coupon type can be create and we can fetch coupon by same type using filter 

We can seggregate by product attributes as well example, product category or brand.

For BxGy coupon 
we can impliment the logic by quantity wise, like Buy2Get1 Buy 2 qty of any sku from the Buy array and get 1 of same sku free

We can keep a usage limit for a coupon to be applied for a customer 
Example:-We can track a new customer and can target a first time purchase coupon so in this case usage limit will be 1.

We can track the number of time the coupon gets use by a customer so we know which coupon is performing better for which customer

We can create a report by location wise using the order address to have an idea which product is getting ordered more because of BxGy coupon or product wise coupon. 

We can impliment to let the customer apply multi coupon like product wise and payment gateway wise. 

We can track a new customer and can target a first time purchase coupon 

We can manage a seperate coupon discount condition structure for API to provide new conditions for new type of coupon


Assumption - every coupon will have Coupon type and Condition details

Currently Implimented:

POST /coupons
Create a coupon with below Columns 
Coupon Type
Coupon Condition Details

If this two filed is not present coupon will not get created.

GET /coupons
retrieve a list of all coupon related data


Apply Coupon 

take coupon id and cart data as input
and generate updated cart as output after applying the discount.

Case 1 
for cart wise total discount shows the discounted value due to the coupon condition being on whole cart

Case 2
For product wise coupon, cart item price is getting updated directly instead of showing a discoount value.
For future implimentation, we can pass two price before price and final price to able to see the discounts

Case 3
For BxGy can be impliment to a different way like 
Addinng a qty parameter - Buying a perticular SKU 1qty get 1 of that same sku free. which can be display at cart like 55% off price reduce.
Or we can add coupon based on other criteria like Category wise or Brand wise.

A status filed can be impliment to make a coupon inactive. A expiry date and a start date filed can be impliment to make a coupon discount available or active for a perticular time period like Monsoon Sale. 
New type of coupon can be implimented like Payment Gateway wise. like Credit card or debit Card wise discount. 

Multiple cound discount apply could be implimented.

If a coupn details gets changed in between or gets deleted 



