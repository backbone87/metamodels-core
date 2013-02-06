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
 * Base implementation for settings that can contain childs.
 *
 * @see
 * @package	   MetaModels
 * @subpackage Interfaces
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Oliver Hoff <oliver@hofff.com>
 */
abstract class MetaModelFilterSettingAggregate
extends MetaModelFilterSetting
implements IMetaModelFilterSettingAggregate
{
	/**
	 * all child settings embedded in this setting.
	 * @var IMetaModelFilterSetting[]
	 */
	protected $arrAggregates = array();
	
	protected $blnLoaded;

	///////////////////////////////////////////////////////////////////////////////
	// Interface IMetaModelFilterSettingWithChilds
	///////////////////////////////////////////////////////////////////////////////

	/**
	 *
	 * {@inheritdoc}
	 *
	 */
	public function addSetting(IMetaModelFilterSetting $objSetting)
	{
		$this->arrAggregates[] = $objSetting;
	}
	
	public function getAggregates() {
		$this->loadAggregates();
		return $this->arrAggregates;
	}
		
	protected function loadAggregates() {
		if($this->blnLoaded) {
			return;
		}
		$arrAggregates = $this->get('aggregates');
		if(!is_array($arrAggregates)) {
			$arrAggregates = strlen($arrAggregates) ? explode(',', $arrAggregates) : array();
		}
		foreach($arrAggregates as $intId) {
			// OH: static factory sux coxx
			$objSetting = MetaModelFilterSettingsFactory::byId($intId);
			$objSetting && $this->addSetting($objSetting);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function generateFilterUrlFrom(IMetaModelItem $objItem, IMetaModelRenderSettings $objRenderSetting)
	{
		$arrFilterUrl = array();
		foreach ($this as $objSetting)
		{
			$arrFilterUrl[] = $objSetting->generateFilterUrlFrom($objItem, $objRenderSetting);
		}
		return call_user_func_array('array_merge', $arrFilterUrl);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParameters()
	{
		$arrParams = array();
		foreach ($this as $objSetting)
		{
			$arrParams[] = $objSetting->getParameters();
		}
		return call_user_func_array('array_merge', $arrParams);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParameterDCA()
	{
		$arrDCA = array();
		foreach ($this as $objSetting)
		{
			$arrDCA[] = $objSetting->getParameterDCA();
		}
		return call_user_func_array('array_merge', $arrDCA);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParameterFilterNames()
	{
		$arrNames = array();
		foreach ($this as $objSetting)
		{
			$arrNames[] = $objSetting->getParameters();
		}
		return call_user_func_array('array_merge', $arrNames);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParameterFilterWidgets($arrIds, $arrFilterUrl, $arrJumpTo, $blnAutoSubmit)
	{
		$arrWidgets = array();
		foreach ($this as $objSetting)
		{
			$arrWidgets[] = $objSetting->getParameterFilterWidgets($arrIds, $arrFilterUrl, $arrJumpTo, $blnAutoSubmit);
		}
		return call_user_func_array('array_merge', $arrWidgets);
	}
	
	public function getIterator() {
		return new ArrayIterator($this->getAggregates());
	}
	
}
