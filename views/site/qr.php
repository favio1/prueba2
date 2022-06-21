<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
/* @var $this yii\web\View */

$this->title = 'Ingrese imagen qr';
?>

<?php $form = ActiveForm::begin([
    'options'=>[
        'class' => 'disable-submit-buttons',
    ] 
]);?>  
    
    <div class="row">
        <div class="col-xs-10 col-sm-10 col-md-4 col-lg-4">
            <label for="Seleccione la imagen QR">Seleccione la imagen QR</label>
            <?=$form->field($model, 'qr')->label(false)->widget(FileInput::classname(), [
                'options' => ['multiple' => false, 'accept' => '.png'],
                'pluginOptions' => [
                    'showPreview' => false,
                    'showCaption' => true,
                    'showRemove' => false,
                    'showUpload' => false,
                ]
            ])?>
        </div>
        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
            <div class="form-group" style="padding-top:30px">
                <?= Html::submitButton('<i class="glyphicon glyphicon-add"></i> Mostrar', ['class' =>'btn btn-round btn-success']) ?>
            </div>
        </div>
    </div>
            
    

<?php ActiveForm::end(); ?>
