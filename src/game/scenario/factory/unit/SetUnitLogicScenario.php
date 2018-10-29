<?php

namespace yii2lab\ai\game\scenario\factory\unit;

use yii2lab\ai\game\events\UnitEvent;
use yii2lab\extension\scenario\base\BaseScenario;

/**
 * Class SetUnitLogicScenario
 *
 * @package yii2lab\ai\game\scenario\factory
 * @property UnitEvent $event
 */
class SetUnitLogicScenario extends BaseScenario {
	
	public $logicClass;
	
	public function run() {
		$this->event->unitCellEntity->setLogicInstance($this->logicClass);
	}
	
}
