<?php
namespace Crankycyclops\DiscountCodeUrl\Plugin;

use Magento\Framework\Registry;
use Magento\Checkout\Model\Session as CheckoutSession;
use Crankycyclops\DiscountCodeUrl\Helper\Cart as CartHelper;

class ApplyCouponOnAddToCart
{
    protected $registry;
    protected $checkoutSession;
    protected $cartHelper;

    public function __construct(
        Registry $registry,
        CheckoutSession $checkoutSession,
        CartHelper $cartHelper
    ) {
        $this->registry = $registry;
        $this->checkoutSession = $checkoutSession;
        $this->cartHelper = $cartHelper;
    }

    /**
     * After product added to cart, apply coupon if needed
     */
    public function afterAddProduct(
        \Magento\Checkout\Model\Cart $subject,
        $result
    ) {
        $coupon = $this->registry->registry('crankycyclops_discounturl_coupon');
        if (!empty($coupon)) {
            $quote = $this->checkoutSession->getQuote();
            if (!$quote->getCouponCode()) {
                $this->cartHelper->applyCoupon($quote, $coupon);
            }
            $this->registry->unregister('crankycyclops_discounturl_coupon');
        }
        return $result;
    }
}
