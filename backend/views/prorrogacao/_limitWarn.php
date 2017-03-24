<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Prorrogacao */

$this->title = 'Registrar Prorrogação';
$this->params['breadcrumbs'][] = ['label' => 'Gerenciar Prorrogações', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="panel panel-default">
    <div class="panel-body">
        <br>
        <p>
            <?php
                echo '<b>Atenção!</b>';      
            ?>
        </p>
        <p>
            <?php
                echo 'O aluno "'.$model->aluno->nome.'" já atingiu o limite máximo de prorrogações, não podendo assim, fazer novas solicitações';      
            ?>
        </p>
        <p>
            <?php
                echo 'Deseja <b>ignorar o aviso</b> e criar uma nova prorrogação mesmo assim?';      
            ?>
        </p>
        <br>
        <p>
            <?= Html::a('<span class="fa fa-exclamation-triangle"></span> Prosseguir mesmo assim', ['create', 'idAluno' => $model->idAluno, 'ignoredWarning' => true], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Ignorando o aviso, você estará registrando mais tempo de prorrogação que o permitido!',
                    'method' => 'post',
                ],
            ]);
            ?>
            <?= Html::a('<span class="fa fa-close"></span> Cancelar', ['index'], [
                'class' => 'btn btn-success',
            ]);
            ?>
        </p>
    </div>
</div>