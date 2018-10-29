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
class FoodEntity extends BaseEnergyEntity {

	public $color = ColorEnum::GREEN;
	
}
