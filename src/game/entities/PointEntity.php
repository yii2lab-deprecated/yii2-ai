<?php

namespace yii2lab\ai\game\entities;

use yii2lab\domain\BaseEntity;
use yii2lab\domain\exceptions\ReadOnlyException;

/**
 * Class CellEntity
 *
 * @package yii2lab\ai\game\entities\unit
 *
 * @property $x
 * @property $y
 */
class PointEntity extends BaseEntity {
	
	protected $x;
	protected $y;

	public function fieldType() {
		return [
			'x' => 'integer',
			'y' => 'integer',
		];
	}
	
	public function rules() {
		return [
			[['x', 'y'], 'required'],
			[['x', 'y'], 'integer'],
		];
	}
	
	/*protected function getX() {
		return $this->x;
	}
	
	public function setX($value) {
		if(isset($this->x)) {
			throw new ReadOnlyException('X already assigned!');
		}
		$this->x = $value;
	}
	
	protected function getY() {
		return $this->x;
	}
	
	public function setY($value) {
		if(isset($this->y)) {
			throw new ReadOnlyException('Y already assigned!');
		}
		$this->y = $value;
	}*/
	
}
