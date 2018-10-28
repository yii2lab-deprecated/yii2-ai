<?php

namespace tests\unit\helpers;

use yii2lab\ai\game\entities\PointEntity;
use yii2lab\ai\game\enums\ColorEnum;
use yii2lab\ai\game\helpers\Matrix;
use yii2lab\test\Test\Unit;

class GameMatrixTest extends Unit {
	
	const PACKAGE = 'yii2lab/yii2-ai';
	
	public function testMove() {
		$cellEntity = $this->createCell();
		
		$this->tester->assertEquals(15, $cellEntity->point->y);
		$this->tester->assertEquals(15, $cellEntity->point->x);
		
		$cellEntity->moveRight();
		$this->tester->assertEquals(16, $cellEntity->point->y);
		
		$cellEntity->moveLeft(2);
		$this->tester->assertEquals(14, $cellEntity->point->y);
		
		$cellEntity->moveUp();
		$this->tester->assertEquals(14, $cellEntity->point->x);
		
		$cellEntity->moveDown(2);
		$this->tester->assertEquals(16, $cellEntity->point->x);
	}
	
	public function testMoveOverMatrix() {
		$cellEntity = $this->createCell();
		
		$cellEntity->moveLeft(15);
		$this->tester->assertEquals(15, $cellEntity->point->y);
		
		$cellEntity->moveUp(15);
		$this->tester->assertEquals(15, $cellEntity->point->x);
		
		$cellEntity->moveRight(2);
		$this->tester->assertEquals(15, $cellEntity->point->y);
		
		$cellEntity->moveDown(2);
		$this->tester->assertEquals(15, $cellEntity->point->x);
	}
	
	private function createCell($x = 15, $y = 15) {
		$matrix = $this->createMatrix();
		$pointEntity = new PointEntity();
		$pointEntity->y = $y;
		$pointEntity->x = $x;
		$cellEntity = $matrix->getCellByPoint($pointEntity);
		$cellEntity->color = ColorEnum::RED;
		return $cellEntity;
	}
	
	private function createMatrix() {
		$size = 16;
		$matrix = new Matrix();
		$matrix->createMatrix($size, $size);
		return $matrix;
	}
	
}
