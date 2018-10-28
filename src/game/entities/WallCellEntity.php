<?php

namespace yii2lab\ai\game\entities;

use yii2lab\ai\game\enums\ColorEnum;

/**
 * Class FoodCellEntity
 *
 * @package yii2lab\ai\game\entities
 *
 * @property $energy
 */
class WallCellEntity extends CellEntity {

	public $color = ColorEnum::YELLOW;
	
	public function isCanReplace() {
		return false;
	}
	
}
