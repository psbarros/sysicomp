<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Trancamento */

$this->title = 'Gerar Portaria';
$this->params['breadcrumbs'][] = ['label' => 'Detalhes da Defesa', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Defesas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="portaria-form">
            <?php $form = ActiveForm::begin(); ?>
            <br>
            <p>
                <?php
                    echo '<b>Dados da portaria:</b>';   
                ?>
            </p>
            
            <div class="row">
                <?= $form->field($model, 'portariaID', ['options' => ['class' => 'col-md-3']])->textInput() ?>
                <?= $form->field($model, 'portariaAno', ['options' => ['class' => 'col-md-3']])->textInput() ?>
                
            </div>
            
            <div class="form-group">
            <?= Html::submitButton('Gerar Portaria', ['class' => 'btn btn-primary']); ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>