<?php

namespace yii2lab\ai\game\scenario\step;

use yii2lab\ai\game\events\MoveEvent;
use yii2lab\extension\scenario\base\BaseScenario;
use yii2lab\extension\scenario\exceptions\StopException;

/**
 * Class IsPossibleScenario
 *
 * @package yii2lab\ai\game\scenario\step
 * @property MoveEvent $event
 */
class IsPossibleScenario extends BaseScenario {
	
	public $possibleClasses = [];
	public $notPossibleClasses = [];
	
	public function run() {
		$className = get_class($this->event->toBaseUnitEntity);
		if(in_array($className, $this->possibleClasses)) {
			return true;
		}
		if(in_array($className, $this->notPossibleClasses)) {
			throw new StopException;
		}
	}
	
}
