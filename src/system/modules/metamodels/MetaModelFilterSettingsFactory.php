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
 * This is the IMetaModelFilter factory interface.
 *
 * To create a IMetaModelFilter instance, call {@link MetaModelFilter::byId()}
 *
 * @package	   MetaModels
 * @subpackage Interfaces
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Oliver Hoff <oliver@hofff.com>
 */
class MetaModelFilterSettingsFactory implements IMetaModelFilterSettingsFactory
{
	/**
	 * Keeps track of all filter settings instances to save DB lookup queries.
	 *
	 * @var IMetaModelFilterSettings[]
	 */
	protected static $arrInstances = array();

	/**
	 * Create a IMetaModelFilter instance from the id.
	 *
	 * @param int $intId the id of the IMetaModelFilter.
	 *
	 * @return IMetaModelFilterSettings the instance of the IMetaModelFilterSettings or null if not found.
	 */
	public static function byId($intId)
	{
		if (!self::$arrInstances[$intId])
		{
			$objDB = Database::getInstance();

			$arrSettings = $objDB->prepare('SELECT * FROM tl_metamodel_filter WHERE id=?')
								 ->execute($intId)
								 ->row();
			if (!empty($arrSettings))
			{
				$objSetting = new MetaModelFilterSettings($arrSettings);
				$objSetting->collectRules();
			} else {
				$objSetting = new MetaModelFilterSettings(array());
			}
			self::$arrInstances[$intId] = $objSetting;
		} else {
			$objSetting = self::$arrInstances[$intId];
		}
		return $objSetting;
	}
	
	public function getById($intId) {
		$objSetting = Database::getInstance()->prepare(
			'SELECT p.*,
					GROUP_CONCAT(c.id ORDER BY c.sorting SEPARATOR \',\') AS aggregates
			FROM	tl_metamodel_filter AS p
			LEFT JOIN tl_metamodel_filter AS c ON c.pid = p.id AND c.mm_id = p.mm_id
			WHERE	p.id = ?
			GROUP BY p.id
		')->execute($intId);
		return $objSetting->numRows ? $this->createSetting($objSetting->row()) : null;
	}
	
	public function getDefaultEmptySetting() {
		return new MetaModelFilterSettingConditionAnd(array());
	}
	
	protected function createSetting(array $arrRow) {
		return self::getClassByType($arrRow['type'])->newInstance($arrRow);
	}
	
	const FILTER_SETTING_INTERFACE = 'IMetaModelFilterSetting';
	
	protected static $arrClasses = array();
	
	protected static function getClassByType($strType) {
		if(!isset(static::arrClasses[$strType])) {
			$strClass = $GLOBALS['METAMODELS']['filters'][$strType]['class'];
			$objClass = new ReflectionClass($strClass);
			if(!$objClass->isSubclassOf(static::FILTER_SETTING_INTERFACE)) {
				throw new LogicException(
					sprintf('Filter configuration: Configured class "%s" for type "%s" must implement "%s"',
						$strClass,
						$strType,
						static::FILTER_SETTING_INTERFACE
					),
					1 // TODO exception codes
				);
			}
			static::arrClasses[$strType] = $objClass;
		}
		return static::arrClasses[$strType];
	}

	/**
	 * Query for all known MetaModel database tables.
	 *
	 * @return string[] all MetaModel table names as string array.
	 */
	// public static function getAllFor();
}

