<?php

namespace yii2lab\ai\game\entities;

use yii2lab\ai\game\behaviors\ValidateFilter;
use yii2lab\ai\game\exceptions\DeadUnitException;
use yii2lab\ai\game\exceptions\PointOverMatrixException;
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
 * @property-read $energy
 * @property Matrix $matrix
 * @property PointEntity $point
 */
class CellEntity extends BaseEntity {
	
	const DIR_UP = 1;
	const DIR_RIGHT = 2;
	const DIR_DOWN = 3;
	const DIR_LEFT = 4;
	
	protected $color;
	protected $content;
	protected $matrix;
	protected $point;
	private $energy = 10;
	
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
	
	public function getEnergy() {
		return $this->energy;
	}
	
	private function setEnergy($value) {
		$this->energy = $value;
		if($this->energy == 0) {
			$this->kill('Low energy');
		}
		if($this->energy == 50) {
			$this->kill('Higth energy');
		}
	}
	
	public function upEnergy($step = 1) {
		$energy = $this->getEnergy() + $step;
		$this->setEnergy($energy);
	}
	
	public function downEnergy($step = 1) {
		$energy = $this->getEnergy() - $step;
		$this->setEnergy($energy);
	}
	
	public function kill($message) {
		$this->matrix->removeCellByPoint($this->point);
		//unset($this);
		throw new DeadUnitException($message);
	}
	
	public function setMatrix($value) {
		if(isset($this->matrix)) {
			throw new ReadOnlyException('Matrix already assigned!');
		}
		$this->matrix = $value;
	}
	
	public function step($dir, $count = 1) {
		if($dir == self::DIR_UP) {
			$this->moveUp($count);
		} elseif($dir == self::DIR_LEFT) {
			$this->moveLeft($count);
		} elseif($dir == self::DIR_DOWN) {
			$this->moveDown($count);
		} elseif($dir == self::DIR_RIGHT) {
			$this->moveRight($count);
		}
	}
	
	public function moveTo(PointEntity $toPointEntity) {
		$this->matrix->moveCellEntity($this, $toPointEntity);
		try {
		
		} catch(PointOverMatrixException $e) {
		
		}
	}
	
	public function moveUp($stepCount = 1) {
		$toPointEntity = clone $this->point;
		$toPointEntity->x = $toPointEntity->x - $stepCount;
		$this->moveTo($toPointEntity);
	}
	
	public function moveDown($stepCount = 1) {
		$toPointEntity = clone $this->point;
		$toPointEntity->x = $toPointEntity->x + $stepCount;
		$this->moveTo($toPointEntity);
	}
	
	public function moveLeft($stepCount = 1) {
		$toPointEntity = clone $this->point;
		$toPointEntity->y = $toPointEntity->y - $stepCount;
		$this->moveTo($toPointEntity);
	}
	
	public function moveRight($stepCount = 1) {
		$toPointEntity = clone $this->point;
		$toPointEntity->y = $toPointEntity->y + $stepCount;
		$this->moveTo($toPointEntity);
	}
	
}
