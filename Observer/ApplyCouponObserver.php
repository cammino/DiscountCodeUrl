<?php

namespace Crankycyclops\DiscountCodeUrl\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class ApplyCouponObserver implements ObserverInterface
{
    public function execute(Observer $observer)
    {
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$logger = $objectManager->get(\Psr\Log\LoggerInterface::class);
		$cookieHelper = $objectManager->get(\Crankycyclops\DiscountCodeUrl\Helper\Cookie::class);
		$config = $objectManager->get(\Crankycyclops\DiscountCodeUrl\Helper\Config::class);
		$registry = $objectManager->get(\Magento\Framework\Registry::class);
        $request = $observer->getEvent()->getRequest();
        $uri = $request->getRequestUri();
        if (strpos($uri, '/page_cache/') !== false) {
            return;
        }
        $queryParameter = $config->getUrlParameter();
        $couponCode = $request->getParam($queryParameter);
        if ($couponCode) {
			if (!empty($coupon)) {
				$cookieHelper->setCookie($coupon);
				$registry->register('crankycyclops_discounturl_coupon_noquote', $coupon);
			}
        }
    }
}
