<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Image */

$this->title = $model->image_id;
$this->params['breadcrumbs'][] = ['label' => 'Изображения', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="image-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->image_id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'image_id',
            'body' => [
                'attribute' => 'body',
                'label' => 'Изображение',
                'format' => 'raw',
                'value' => Html::img($model->getUrl()),
            ],
            'created:datetime',
            'updated:datetime',
        ],
    ]) ?>

</div>
