<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Галерея';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="image-index">

    <h1><?= Html::encode($this->title) ?></h1>

<p>
    <?= Html::a('Создать изображение', ['create'], ['class' => 'btn btn-success']) ?>
</p>
<?= \yii\widgets\ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => '_gallery_image',
    'layout' => "{summary}\n<div class=\"row\">{items}</div>\n{pager}",
]) ?>
</div>