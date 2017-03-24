<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Trancamento */

$this->title = 'Registrar Trancamento';
$this->params['breadcrumbs'][] = ['label' => 'Gerenciar Trancamentos', 'url' => ['index']];
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
                echo 'O aluno "'.$model->aluno->nome.'" já atingiu o limite máximo de trancamentos, não podendo assim, fazer novas solicitações';      
            ?>
        </p>
        <p>
            <?php
                echo 'Deseja <b>ignorar o aviso</b> e criar um novo trancamento mesmo assim?';      
            ?>
        </p>
        <br>
        <p>
            <?= Html::a('<span class="fa fa-exclamation-triangle"></span> Prosseguir mesmo assim', ['create', 'idAluno' => $model->idAluno, 'ignoredWarning' => true], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Ignorando o aviso, você estará registrando mais tempo de trancamento que o permitido!',
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