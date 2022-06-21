<?php 
use yii\helpers\Html;
?>
<div class="form-group">
    <?=Html::a('Volver', ['/site/qr'], ['class'=>'btn btn-default'])?>
</div>
<iframe src="<?=$pdf?>" width="100%"  frameborder="0" style="height:100vh;"></iframe>