<?php
/**
 * The MetaModels extension allows the creation of multiple collections of custom items,
 * each with its own unique set of selectable attributes, with attribute extendability.
 * The Front-End modules allow you to build powerful listing and filtering of the
 * data in each collection.
 *
 * PHP version 5
 * @package	   MetaModels
 * @subpackage Interfaces
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  The MetaModels team.
 * @license    LGPL.
 * @filesource
 */

/**
 * This filter condition generates a "OR" condition from all child filter settings.
 * The generated rule will return ids that are mentioned in ANY of the child rules.
 *
 * @package	   MetaModels
 * @subpackage Core
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Oliver Hoff <oliver@hofff.com>
 */
class MetaModelFilterSettingConditionOr extends MetaModelFilterSettingAggregate
{
	
	/**
	 * Generates the filter rules based upon the given fulter url.
	 *
	 * {@inheritdoc}
	 *
	 * @see MetaModelFilterSetting::prepareRules()
	 */
	public function addRules(IMetaModelFilter $objFilter, $arrFilterUrl)
	{
		$objFilterRule = new MetaModelFilterRuleOR($this->get('stop_after_match'));
		foreach ($this as $objSetting)
		{
			$objSubFilter = new MetaModelFilter($this->getMetaModel());
			$objSetting->addRules($objSubFilter, $arrFilterUrl);
			$objFilterRule->addChild($objSubFilter);
		}
		$objFilter->addFilterRule($objFilterRule);
	}
	
}
