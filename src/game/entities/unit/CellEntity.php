<?php

namespace yii2lab\ai\game\entities\unit;

use yii2lab\ai\game\behaviors\ValidateFilter;
use yii2lab\ai\game\entities\PointEntity;
use yii2lab\ai\game\helpers\Matrix;
use yii2lab\domain\BaseEntity;
use yii2lab\domain\exceptions\ReadOnlyException;

/**
 * Class CellEntity
 *
 * @package yii2lab\ai\game\entities\unit
 *
 * @property $color
 * @property $content
 * @property Matrix $matrix
 * @property PointEntity $point
 */
abstract class CellEntity extends BaseEntity {
	
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
			throw new ReadOnlyException('Matrix already assigned!');
		}
		$this->matrix = $value;
	}
	
}
