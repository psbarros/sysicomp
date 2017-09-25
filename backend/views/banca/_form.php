<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Banca */
/* @var $form yii\widgets\ActiveForm */


$tipoFuncao = ['P' => 'Presidente', 'I' => 'Membro Interno', 'E' => 'Membro Externo', 'S' => 'Suplente'];


$nomes=$model_membro->nome;
?>






<div class="banca-form">


    <div class="col-lg-8">

         <div class="panel panel-default">


               <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
                <div class="panel-heading">

                    <h3 class="panel-title"><b>Componente Banca</b></h3>
                </div>

                <div class="panel-body">
                    <div class="panel-heading">
                                    <h3 class="panel-title"><b>Presidente da Banca</b></h3>
                    </div>
                          <?= $form->field($model, 'membrosbanca_id',['options' => ['class' => 'col-md-6 col-right']])->dropDownList($items, ['prompt' => 'Selecione um membro...'])->label("<font color='#FF0000'>*</font> <b>Nome Membro:</b>") ?>
                </div> 
    

        </div>

    </div>
     <div class="form-group">
             <?= Html::submitButton('Salvar', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
     </div>
    <?php ActiveForm::end(); ?>

</div>
