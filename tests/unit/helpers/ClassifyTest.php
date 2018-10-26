<?php

namespace tests\unit\helpers;

use yii2lab\ai\domain\entities\TrainEntity;
use yii2lab\ai\domain\helpers\ClassifyHelper;
use yii2lab\extension\yii\helpers\FileHelper;
use yii2lab\test\Test\Unit;

class ClassifyTest extends Unit {
	
	const PACKAGE = 'yii2lab/yii2-ai';
	const DATA_PATH = 'vendor/yii2lab/yii2-ai/tests/data/SMSCollection/';
	const ACTUAL_PATH = 'vendor/yii2lab/yii2-ai/tests/_data/SMSCollection/';
	const EXPECTED_PATH = 'vendor/yii2lab/yii2-ai/tests/_expected/SMSCollection/';
	
	public function testTrainAndSave() {
		FileHelper::remove(self::ACTUAL_PATH . 'model');
		$classify = new ClassifyHelper;
		$trainingCollection = $this->loadData(self::DATA_PATH . 'training');
		$classify->train($trainingCollection);
		$classify->save(self::ACTUAL_PATH . 'model');
		$expected = FileHelper::load(self::EXPECTED_PATH . 'model');
		$actual = FileHelper::load(self::ACTUAL_PATH . 'model');
		$this->tester->assertEquals($expected, $actual);
	}
	
	public function testLoadAndTest() {
		$classify = new ClassifyHelper;
		$classify->load(self::EXPECTED_PATH . 'model');
		$testingCollection = $this->loadData(self::DATA_PATH . 'testing');
		$correct = $this->testCollection($testingCollection, $classify);
		$this->tester->assertEquals(25, $correct);
	}
	
	public function testTrainAndTest() {
		FileHelper::remove(self::ACTUAL_PATH . 'model');
		$classify = new ClassifyHelper;
		$trainingCollection = $this->loadData(self::DATA_PATH . 'training');
		$classify->train($trainingCollection);
		$testingCollection = $this->loadData(self::DATA_PATH . 'testing');
		$correctCount = $this->testCollection($testingCollection, $classify);
		$this->tester->assertEquals(25, $correctCount);
	}
	
	public function testTrainAndPredict() {
		FileHelper::remove(self::ACTUAL_PATH . 'model');
		$classify = new ClassifyHelper;
		$trainingCollection = $this->loadData(self::DATA_PATH . 'training');
		$classify->train($trainingCollection);
		$actual = $classify->predict('This is the 2nd time we have tried 2 contact u. U have won the £750 Pound prize. 2 claim is easy, call 087187272008 NOW1! Only 10p per minute. BT-national-rate.');
		$this->tester->assertEquals('spam', $actual);
		$actual = $classify->predict('Have a safe trip to Nigeria. Wish you happiness and very soon company to share moments with');
		$this->tester->assertEquals('ham', $actual);
	}
	
	private function testCollection($testingCollection, ClassifyHelper $classify) {
		$correctCount = 0;
		/** @var TrainEntity[] $testingCollection */
		foreach($testingCollection as $trainEntity) {
			$prediction = $classify->predict($trainEntity->value);
			if($prediction == $trainEntity->class_id) {
				$correctCount++;
			}
		}
		return $correctCount;
	}
	
	private function loadData($file) {
		$rr = FileHelper::load(ROOT_DIR . DS . $file);
		$rr = trim($rr);
		$rr = explode(NS, $rr);
		$result = [];
		foreach($rr as $value) {
			if(!empty($value)) {
				$rt = explode(TAB, $value);
				$training['value'] = $rt[1];
				$training['class_id'] = $rt[0];
				$result[] = new TrainEntity($training);
			}
		}
		/** @var TrainEntity[] $result */
		return $result;
	}
}
