<?php

namespace yii2lab\ai\game\entities\unit;

use yii2lab\ai\game\entities\PointEntity;
use yii2lab\ai\game\interfaces\BotLogicInterface;
use yii2lab\extension\common\helpers\ClassHelper;

/**
 * Class BotEntity
 *
 * @package yii2lab\ai\game\entities\unit
 * @property $energy
 */
class BotEntity extends BaseEnergyEntity {
	
	const DIR_UP = 1;
	const DIR_RIGHT = 2;
	const DIR_DOWN = 3;
	const DIR_LEFT = 4;
	
	/**
	 * @var BotLogicInterface
	 */
	private $logic;
	
	public function setLogic($definition) {
		$this->logic = ClassHelper::createInstance($definition, [], BotLogicInterface::class);
		$this->logic->setBot($this);
	}
	
	public function step() {
		if($this->isDead()) {
			return;
		}
		$wantCell = $this->wantCell();
		if($wantCell) {
			$this->matrix->moveBaseUnitEntity($this, $wantCell);
		}
	}
	
	/**
	 * @return PointEntity|boolean
	 */
	private function wantCell() {
		$possibles = $this->matrix->getPossibleCollection($this->point);
		return $this->logic->getPoint($possibles);
	}
	
}
