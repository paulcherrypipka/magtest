<?php

namespace OutdoorCap\TierPriceBreaks\Model\Product\Type;

use Magento\Catalog\Model\Product\Type\Price;
use Magento\Catalog\Model\Product;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Customer\Api\GroupManagementInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Store\Model\Store;
use Magento\Catalog\Api\Data\ProductTierPriceExtensionFactory;
use Magento\Framework\App\ObjectManager;

/**
 * Class Price
 * @package OutdoorCap\TierPriceBreaksPrice\Model\Product\Type
 *
 */
class TierPriceBreaksPrice extends Price
{

    /**
     * Customizing of "Apply tier price for product if not return price that was before"
     *
     * @param   Product $product
     * @param   float $qty
     * @param   float $finalPrice
     * @return  float
     */
    protected function _applyTierPrice($product, $qty, $finalPrice)
    {
        if ($qty === null) {
            return $finalPrice;
        }

        // Obtaining of config values
        $customer_groups = $this->config->getValue('outdoorcap_tierpricebreaks/general/group', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        // Get current customer group
        $current_cust_group = $this
            ->_customerSession
            ->getCustomer()
            ->getGroupId();
        $customer_groups = explode(',', $customer_groups);

        if (in_array($current_cust_group, $customer_groups)) {

            // Get count of items in cart
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $quoteId = $objectManager
                ->create('Magento\Checkout\Model\Session')
                ->getQuoteId();

            $qty = $objectManager
                ->create('Magento\Quote\Model\QuoteRepository')
                ->get($quoteId)
                ->getItemsQty();
        }

        $tierPrice = $product->getTierPrice($qty);
        if (is_numeric($tierPrice)) {
            $finalPrice = min($finalPrice, $tierPrice);
        }

        return $finalPrice;
    }

}