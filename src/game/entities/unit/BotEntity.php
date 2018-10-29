<?php

namespace yii2lab\ai\game\entities\unit;

use yii2lab\ai\game\entities\PointEntity;
use yii2lab\ai\game\enums\ColorEnum;
use yii2lab\ai\game\helpers\PossibleHelper;
use yii2lab\ai\game\interfaces\BotLogicInterface;
use yii2lab\domain\exceptions\ReadOnlyException;
use yii2lab\extension\common\helpers\ClassHelper;

/**
 * Class BotEntity
 *
 * @package yii2lab\ai\game\entities\unit
 * @property $energy
 */
class BotEntity extends CellEntity {
	
	const DIR_UP = 1;
	const DIR_RIGHT = 2;
	const DIR_DOWN = 3;
	const DIR_LEFT = 4;
	
	private $energy;
	
	/**
	 * @var BotLogicInterface
	 */
	private $logic;
	
	public function setLogic($definition) {
		$this->logic = ClassHelper::createInstance($definition, [], BotLogicInterface::class);
		$this->logic->setBot($this);
	}
	
	public function getColor() {
		if($this->isDead()) {
			return ColorEnum::BLACK;
		}
		if($this->energy > 100) {
			return ColorEnum::CYAN;
		}
		return ColorEnum::BLUE;
	}
	
	public function getContent() {
		if($this->isDead()) {
			return 'xx';
		}
		return '..';
	}
	
	public function step() {
		if($this->isDead()) {
			return;
		}
		$wantCell = $this->wantCell();
		if($wantCell) {
			$this->matrix->moveCellEntity($this, $wantCell);
		}
	}
	
	public function isDead() {
		return $this->getEnergy() <= 0;
	}
	
	public function kill() {
		return $this->energy = 0;
	}

	public function upEnergy($step = 1) {
		$this->energy = $this->getEnergy() + $step;
	}
	
	public function downEnergy($step = 1) {
		$this->energy = $this->getEnergy() - $step;
	}
	
	public function getEnergy() {
		return $this->energy;
	}
	
	public function setEnergy($value) {
		if(isset($this->energy)) {
			throw new ReadOnlyException('Energy attribute read only!');
		}
		$this->energy = $value;
	}
	
	/**
	 * @return PointEntity|boolean
	 */
	private function wantCell() {
		$possibles = $this->getPossibles();
		return $this->logic->getPoint($possibles);
	}
	
	private function getPossibles() {
		$map = $this->matrix->getCellsByPoint($this->point);
		$possibles = PossibleHelper::getPossibles($map);
		return $possibles;
	}
	
}
