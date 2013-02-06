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
 * Filter setting implementation of a static list of matching ids.
 *
 * @package	   MetaModels
 * @subpackage Core
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Oliver Hoff <oliver@hofff.com>
 */
class MetaModelFilterSettingIdList extends MetaModelFilterSetting
{
	
	public function addRules(IMetaModelFilter $objFilter, $arrFilterUrl)
	{
		$arrItems = strval($this->get('items'));
		$arrItems = strlen($arrItems) ? explode(',', $arrItems) : array();
		$objFilter->addFilterRule(new MetaModelFilterRuleStaticIdList($arrItems));
	}
	
}
