<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\DrawSaveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Image */
/* @var $form yii\widgets\ActiveForm */
/* @var $drawForm \app\models\DrawSaveForm */


$formId = $drawForm->formName();
$canvasId = $formId.'canvas';
?>

<div class="image-form">

    <?php $form = ActiveForm::begin(['id' => $formId]); ?>

    <div class="form-group">
        <label class="control-label" for="<?=$canvasId;?>">Изображение</label>
        <div class="form-group">
<?php /*<canvas id="<?=$canvasId;?>" width="<?=DrawSaveForm::IMAGE_WIDTH?>" height="<?=DrawSaveForm::IMAGE_HEIGHT?>" class="canvas" style="background: url(<?=$drawForm->canvas;?>) no-repeat center center;"></canvas> */?>
<?php /*<canvas id="<?=$canvasId;?>" width="<?=DrawSaveForm::IMAGE_WIDTH?>" height="<?=DrawSaveForm::IMAGE_HEIGHT?>" class="canvas"></canvas> */?>
            <?=
            Html::tag('canvas', '', [
                'id' => $canvasId,
                'width' => DrawSaveForm::IMAGE_WIDTH,
                'height' => DrawSaveForm::IMAGE_HEIGHT,
                'class' => 'canvas',
                'style' => "background: url({$model->body}) no-repeat center center;"
            ])
            ?>
        </div>
    </div>

    <?= $form->field($drawForm, 'canvas', ['template' => "{input}\n{error}"])->hiddenInput() ?>

    <?= $form->field($drawForm, 'password')
        ->passwordInput()
        ->hint($model->isNewRecord ? 'Задайте пароль, что бы защитить изображение' : 'Введите пароль, указанный ранее пароль для этого изображения')
    ?>

    <?php
    if($model->isNewRecord){
        echo $form->field($drawForm, 'password_repeat')->passwordInput();
    }
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<?php

$canvasInputId = Html::getInputId($drawForm, 'canvas');

$this->registerJsFile('js/sketch.min.js', ['depends' => 'yii\web\JqueryAsset']);
$this->registerJs(<<<JS
var saveCanvas = function() {
    var canvas = document.getElementById('{$canvasId}'),
        input = document.getElementById('{$canvasInputId}');
    input.value = canvas.toDataURL('image/png');
};
var loadCanvas = function() {
    $('#{$canvasId}').sketch();
    
    var input = document.getElementById('{$canvasInputId}');
    if(!input.value){
        return;
    }
    
    var canvas = document.getElementById('{$canvasId}');
    var context = canvas.getContext('2d');
    // load image from data url
    var imageObj = new Image();
    imageObj.onload = function() {
      context.drawImage(this, 0, 0);
    };
    imageObj.src = input.value;
};
loadCanvas();

$('#{$formId}').on('beforeValidate', function (event, messages, deferreds) {
    saveCanvas();
});
JS
);

$this->registerCss(<<<CSS
.canvas {
    border: 1px solid #ccc
}
CSS
);