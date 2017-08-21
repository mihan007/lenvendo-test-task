    <?php

use yii\helpers\Html;

/**
 * @var $model \app\models\Image
 * @var $key
 * @var $index
 * @var $widget
 */
?>

<div id="image<?=$key;?>" class="col-sm-12 col-xs-12 col-md-4 col-lg-3">
    <div class="thumbnail">
        <div class="pull-right">
            <?= Html::a('<i class="glyphicon glyphicon-pencil"></i>', \yii\helpers\Url::to(['update', 'id' => $key], ['class' => 'btn'])) ?>
        </div>
        <?= Html::a(Html::img($model->getUrl(), ['width' => 300]), \yii\helpers\Url::to(['update', 'id' => $key])) ?>
        <div class="caption">
            Добавлено
            <?= Yii::$app->getFormatter()->asDatetime($model->created, 'dd.MM.yyyy HH:mm')?>
        </div>
    </div>
</div>