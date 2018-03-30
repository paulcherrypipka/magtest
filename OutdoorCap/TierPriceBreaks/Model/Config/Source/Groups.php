<?php

namespace OutdoorCap\TierPriceBreaks\Model\Config\Source;


class Groups
{
	/**
	 * Returns groups for multiselect
	 *
	 * @return array
	 */

	public function toOptionArray(){
		$objectManager =  \Magento\Framework\App\ObjectManager::getInstance();

		$customerGroupsCollection = $objectManager->get('\Magento\Customer\Model\ResourceModel\Group\Collection');
		$customerGroups = $customerGroupsCollection->toOptionArray();

		return $customerGroups;
	}
}
