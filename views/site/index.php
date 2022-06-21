<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
/* @var $this yii\web\View */

$this->title = \Yii::$app->name;
?>
<?php if ($qr == null) { ?>
    <?php $form = ActiveForm::begin([
        'options'=>[
            'class' => 'disable-submit-buttons',
        ] 
    ]);?>       
    <div class="row">
        <div class="col-xs-10 col-sm-10 col-md-4 col-lg-4">
            <label for="PDF">PDF</label>
            <?=$form->field($model, 'pdf')->label(false)->widget(FileInput::classname(), [
                'options' => ['multiple' => false, 'accept' => '.pdf'],
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
                <?= Html::submitButton('<i class="glyphicon glyphicon-add"></i> Convertir', ['class' =>'btn btn-round btn-success']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

<?php } ?>


<?php if ($qr != null) { ?>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="text-center">
                <?='<img src="' . $qr . '">'?>
                <?php $form = ActiveForm::begin([
                    'action'=> ['/site/descargar'],
                    'method'=>'post'
                ]);?> 
                    <input type="hidden" name="link" value="<?=$rutaQr?>">
                    <?= Html::submitButton('Descargar', ['class' =>'btn btn-round btn-success']) ?>
                    <br>
                    <?= Html::a('Volver a crear', ['/'], ['class'=>'btn btn-default']);?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
<?php }?>