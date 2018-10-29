<?php

namespace yii2lab\ai\game\scenario\factory\unit;

use yii2lab\ai\game\events\UnitEvent;
use yii2lab\extension\scenario\base\BaseScenario;

/**
 * Class SetUnitEnergyScenario
 *
 * @package yii2lab\ai\game\scenario\factory
 * @property UnitEvent $event
 */
class SetUnitEnergyScenario extends BaseScenario {
	
	public $energy;
	
	public function run() {
		$this->event->BotEntity->energy = $this->energy;
	}
	
}
