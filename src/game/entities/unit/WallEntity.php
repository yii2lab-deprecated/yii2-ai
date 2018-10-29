<?php

namespace yii2lab\ai\game\entities\unit;

use yii2lab\ai\game\enums\ColorEnum;

/**
 * Class FoodEntity
 *
 * @package yii2lab\ai\game\entities\unit
 *
 * @property $energy
 */
class WallEntity extends CellEntity {

	public $color = ColorEnum::YELLOW;
	
}
