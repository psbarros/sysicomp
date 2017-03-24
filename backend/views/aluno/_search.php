<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AlunoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="aluno-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'nome') ?>

    <?= $form->field($model, 'email') ?>

    <?= $form->field($model, 'senha') ?>

    <?= $form->field($model, 'matricula') ?>

    <?php // echo $form->field($model, 'area') ?>

    <?php // echo $form->field($model, 'curso') ?>

    <?php // echo $form->field($model, 'endereco') ?>

    <?php // echo $form->field($model, 'bairro') ?>

    <?php // echo $form->field($model, 'cidade') ?>

    <?php // echo $form->field($model, 'uf') ?>

    <?php // echo $form->field($model, 'cep') ?>

    <?php // echo $form->field($model, 'datanascimento') ?>

    <?php // echo $form->field($model, 'sexo') ?>

    <?php // echo $form->field($model, 'nacionalidade') ?>

    <?php // echo $form->field($model, 'estadocivil') ?>

    <?php // echo $form->field($model, 'cpf') ?>

    <?php // echo $form->field($model, 'rg') ?>

    <?php // echo $form->field($model, 'orgaoexpeditor') ?>

    <?php // echo $form->field($model, 'dataexpedicao') ?>

    <?php // echo $form->field($model, 'telresidencial') ?>

    <?php // echo $form->field($model, 'telcomercial') ?>

    <?php // echo $form->field($model, 'telcelular') ?>

    <?php // echo $form->field($model, 'nomepai') ?>

    <?php // echo $form->field($model, 'nomemae') ?>

    <?php // echo $form->field($model, 'regime') ?>

    <?php // echo $form->field($model, 'bolsista') ?>

    <?php // echo $form->field($model, 'agencia') ?>

    <?php // echo $form->field($model, 'pais') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'anoingresso') ?>

    <?php // echo $form->field($model, 'idiomaExameProf') ?>

    <?php // echo $form->field($model, 'conceitoExameProf') ?>

    <?php // echo $form->field($model, 'dataExameProf') ?>

    <?php // echo $form->field($model, 'tituloQual2') ?>

    <?php // echo $form->field($model, 'dataQual2') ?>

    <?php // echo $form->field($model, 'conceitoQual2') ?>

    <?php // echo $form->field($model, 'tituloTese') ?>

    <?php // echo $form->field($model, 'dataTese') ?>

    <?php // echo $form->field($model, 'conceitoTese') ?>

    <?php // echo $form->field($model, 'horarioQual2') ?>

    <?php // echo $form->field($model, 'localQual2') ?>

    <?php // echo $form->field($model, 'resumoQual2') ?>

    <?php // echo $form->field($model, 'horarioTese') ?>

    <?php // echo $form->field($model, 'localTese') ?>

    <?php // echo $form->field($model, 'resumoTese') ?>

    <?php // echo $form->field($model, 'tituloQual1') ?>

    <?php // echo $form->field($model, 'numDefesa') ?>

    <?php // echo $form->field($model, 'dataQual1') ?>

    <?php // echo $form->field($model, 'examinadorQual1') ?>

    <?php // echo $form->field($model, 'conceitoQual1') ?>

    <?php // echo $form->field($model, 'cursograd') ?>

    <?php // echo $form->field($model, 'instituicaograd') ?>

    <?php // echo $form->field($model, 'crgrad') ?>

    <?php // echo $form->field($model, 'egressograd') ?>

    <?php // echo $form->field($model, 'dataformaturagrad') ?>

    <?php // echo $form->field($model, 'idUser') ?>

    <?php // echo $form->field($model, 'orientador') ?>

    <?php // echo $form->field($model, 'anoconclusao') ?>

    <?php // echo $form->field($model, 'sede') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
