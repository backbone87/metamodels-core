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
 */
class MetaModelFilterAggregate
	extends MetaModelFilter
{

	/**
	 * @var array The aggregated filters
	 */
	protected $arrFilters = array();
	
	protected $arrEvaluated;
	
	protected $arrHashed;

	public function __construct(array $arrFilters)
	{
		$this->arrFilters = $arrFilters;
		$this->passiveEvaluate();
	}
	
	public function isCacheable() {
		foreach($this->arrFilters as $objFilter) {
			if(!$objFilter->isCacheable()) {
				return false;
			}
		}
		return true;
	}
	
	public function getHash() {
		if(!$this->isCacheable()) {
			return null;
		}
		$arrHashes = array();
		foreach($this->arrFilters as $objFilter) {
			$arrHashes[] = $objFilter->getHash();
		}
		return md5(implode('', $arrHashes));
	}
	
	protected function passiveEvaluate() {
		foreach($this->arrFilters as $i => $objFilter) {
			if($objFilter->isEvaluated()) {
				unset($this->arrFilters[$i]);
				if($objFilter->isEvaluate())
				$this->arrEvaluated[$i] = $objFilter->getIdSet();
			}
		}
	}
	
}
