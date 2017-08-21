<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Image */
/* @var $drawForm app\models\DrawSaveForm */

$this->title = 'Редактирование изображения: ' . $model->image_id;
$this->params['breadcrumbs'][] = ['label' => 'Галерея', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->image_id, 'url' => ['view', 'id' => $model->image_id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="image-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'drawForm' => $drawForm,
    ]) ?>

</div>

<?php

$authUrl = \yii\helpers\Url::to(['auth', 'id' => $model->image_id]);
$passwordInputId = Html::getInputId($drawForm, 'password');

$this->registerJs(<<<JS
bootbox.prompt({
    title: 'Введите пароль, что бы получить доступ к редактированию',
    inputType: 'password',
    onEscape: false,
    closeButton: false,
    callback: function(result) {
        if(result === null){
            return false;
        }
        $.post('{$authUrl}', {password: result}, function(json) {
            if(json.success){
                $('#{$passwordInputId}').val(result);
                bootbox.hideAll();
            }else{
                bootbox.alert({
                    size: "small",
                    message: json.message
                });
            }
        });
        return false;
    } 
});  
JS
);
