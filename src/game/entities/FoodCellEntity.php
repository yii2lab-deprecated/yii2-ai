<?php

namespace yii2lab\ai\game\entities;

use yii2lab\ai\game\enums\ColorEnum;

/**
 * Class FoodCellEntity
 *
 * @package yii2lab\ai\game\entities
 *
 * @property-read $energy
 */
class FoodCellEntity extends CellEntity {

	public $color = ColorEnum::GREEN;
	private $energy = 2;
	
	public function getEnergy() {
		return $this->energy;
	}
	
	/*public function getContent() {
		return $this->getEnergy();
	}*/
	
}
