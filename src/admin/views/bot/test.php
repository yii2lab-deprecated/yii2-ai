<?php

/* @var $this yii\web\View
 * @var $entity \yii2lab\ai\domain\entities\BotEntity
 */

use yii2lab\extension\yii\widgets\DetailView;

$this->title = $entity->title;

?>

<div>
	<?= DetailView::widget([
		'labelWidth' => '200px',
		'model' => $result,
		'attributes' => [
			[
				'attribute' => 'total_all',
				'label' => ['ai/test', 'total_all'],
			],
			[
				'attribute' => 'total_train',
				'label' => ['ai/test', 'total_train'],
			],
			[
				'attribute' => 'total_test',
				'label' => ['ai/test', 'total_test'],
			],
			[
				'attribute' => 'total_success',
				'label' => ['ai/test', 'total_success'],
			],
			[
				'attribute' => 'total_error',
				'label' => ['ai/test', 'total_error'],
			],
			[
				'attribute' => 'percent_error',
				'label' => ['ai/test', 'percent_error'],
			],
		],
	]) ?>

</div>
