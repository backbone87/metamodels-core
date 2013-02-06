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
 * This is the MetaModel filter interface.
 *
 * @package	   MetaModels
 * @subpackage Interfaces
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Oliver Hoff <oliver@hofff.com>
 */
interface IMetaModelFilter
{

	/**
	 * @return bool True if this filter matches any item; otherwise false
	 */
	public function isWildcard();
	
	/**
	 * @return bool True if this filter does not match any item; otherwise false
	 */
	public function isEmpty();
	
	/**
	 * @param array $arrIds Filters the given array of IMetaModelItem
	 * @return array The IMetaModelItem's that match this filter
	 */
	public function filter(array $arrItems);
	
	/**
	 * @param IMetaModelItem $objItem The item to match
	 * @return bool True if this filter matches the given item; otherwise false
	 */
	public function match(IMetaModelItem $objItem);
	
	/**
	 * Narrow down the list of Ids that match the given filter.
	 *
	 * @return int[]|null all matching Ids or null if all ids did match.
	 */
	public function getMatchingIds();
	
}
