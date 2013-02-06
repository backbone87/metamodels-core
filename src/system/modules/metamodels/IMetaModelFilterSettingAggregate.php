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
 *
 * A filter setting the aggregates attached settings with a implementation specific
 * aggregate function.
 *
 * @see
 * @package	   MetaModels
 * @subpackage Interfaces
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Oliver Hoff <oliver@hofff.com>
 */
interface IMetaModelFilterSettingAggregate extends IMetaModelFilterSetting, IteratorAggregate
{
	
	/**
	 * Adds a setting aggregate.
	 *
	 * @param IMetaModelFilterSetting $objFilterSetting the setting that shall be added as child.
	 * @return void
	 */
	public function addSetting(IMetaModelFilterSetting $setting);
	
}
