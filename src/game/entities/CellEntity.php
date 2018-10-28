<?php

namespace yii2lab\ai\game\entities;

use yii2lab\ai\game\behaviors\ValidateFilter;
use yii2lab\ai\game\helpers\Matrix;
use yii2lab\domain\BaseEntity;
use yii2lab\domain\exceptions\ReadOnlyException;

/**
 * Class CellEntity
 *
 * @package yii2lab\ai\game\entities
 *
 * @property $color
 * @property $content
 * @property Matrix $matrix
 * @property PointEntity $point
 */
abstract class CellEntity extends BaseEntity {
	
	const DIR_UP = 1;
	const DIR_RIGHT = 2;
	const DIR_DOWN = 3;
	const DIR_LEFT = 4;
	
	protected $color;
	protected $content;
	private $matrix;
	protected $point;
	
	public function behaviors() {
		return [
			ValidateFilter::class,
		];
	}
	
	public function fieldType() {
		return [
			'color' => 'integer',
			'matrix' => Matrix::class,
			'point' => PointEntity::class,
		];
	}
	
	public function rules() {
		return [
			[['matrix', 'point'], 'required'],
		];
	}
	
	public function getMatrix() {
		return $this->matrix;
	}
	
	public function setMatrix($value) {
		if(isset($this->matrix)) {
			//throw new ReadOnlyException('Matrix already assigned!');
		}
		$this->matrix = $value;
	}
	
}
