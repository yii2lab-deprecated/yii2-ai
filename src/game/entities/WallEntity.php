<?php

namespace yii2lab\ai\game\entities;

use yii2lab\ai\game\enums\ColorEnum;

/**
 * Class FoodEntity
 *
 * @package yii2lab\ai\game\entities
 *
 * @property $energy
 */
class WallEntity extends CellEntity {

	public $color = ColorEnum::YELLOW;
	
}
