<?php

namespace yii2lab\ai\domain\helpers;

use yii\helpers\ArrayHelper;
use yii2lab\ai\domain\entities\TrainEntity;
use NlpTools\Tokenizers\WhitespaceTokenizer;
use NlpTools\Documents\TrainingSet;
use NlpTools\Documents\TokensDocument;
use NlpTools\FeatureFactories\DataAsFeatures;
use NlpTools\Classifiers\MultinomialNBClassifier;
use yii2lab\ai\domain\models\FeatureBasedNB;
use yii2lab\extension\yii\helpers\FileHelper;

class ClassifyHelper {
	
	/**
	 * @var FeatureBasedNB
	 */
	protected $model;
	
	/**
	 * @var MultinomialNBClassifier
	 */
	protected $classifier;
	
	/**
	 * @var array
	 */
	protected $classes = [];
	
	/**
	 * @var TrainingSet
	 */
	protected $trainingSet;
	
	public function save($file) {
		FileHelper::save(ROOT_DIR . DS . $file, serialize($this->model));
	}
	
	public function load($file) {
		$serialized = FileHelper::load(ROOT_DIR . DS . $file);
		$this->model = unserialize($serialized);
		$this->loadModel();
	}
	
	public function train($training) {
		$this->trainingSet = new TrainingSet();
		/** @var TrainEntity[] $training */
		foreach($training as $trainEntity) {
			$tokensDocument = $this->tokensDocument($trainEntity->sample);
			$this->trainingSet->addDocument($trainEntity->label, $tokensDocument);
		}
		$dataAsFeatures = new DataAsFeatures();
		$this->model = new FeatureBasedNB();
		$this->model->train($dataAsFeatures, $this->trainingSet);
		$this->loadModel();
		$classes = ArrayHelper::getColumn($training, 'label');
		$this->classes = array_unique($classes);
	}
	
	public function predict($sample, $classes = null) {
		$classes = $classes ? $classes : $this->classes;
		$tokensDocument = $this->tokensDocument($sample);
		$prediction = $this->classifier->classify($classes, $tokensDocument);
		return $prediction;
	}
	
	private function loadModel() {
		$this->classifier = $this->createClassifier();
		$this->classes = $this->model->getClasses();
	}
	
	private function createClassifier() {
		$dataAsFeatures = new DataAsFeatures();
		$this->classifier = new MultinomialNBClassifier($dataAsFeatures, $this->model);
		return $this->classifier;
	}
	
	private function tokensDocument($sample) {
		$whitespaceTokenizer = new WhitespaceTokenizer(); // will split into tokens
		return new TokensDocument($whitespaceTokenizer->tokenize($sample));
	}
	
}
