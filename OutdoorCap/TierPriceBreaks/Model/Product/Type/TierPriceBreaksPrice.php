<?php

namespace OutdoorCap\TierPriceBreaks\Model\Product\Type;

use Magento\Catalog\Model\Product\Type\Price;
use Magento\Catalog\Model\Product;
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

        $tierPrice = $product->getTierPrice($qty);
        if (is_numeric($tierPrice)) {
            $finalPrice = min($finalPrice, $tierPrice);
        }

        return $finalPrice;
    }

}