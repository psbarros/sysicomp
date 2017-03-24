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
                echo 'O aluno "'.$model->aluno->nome.'" <b>atingiu o limite máximo de trancamentos</b>';      
            ?>
        </p>
        <p>
            <?php
                echo '<b>À partir de agora, ele não poderá fazer novas solicitações de trancamento</b>';      
            ?>
        </p>
        <br>
        <p>
            <?= Html::a('<span class="fa fa-check"></span> Ok', ['trancamento/view', 'id'=>$model->id], [
                'class' => 'btn btn-primary',
            ]);
            ?>
        </p>
    </div>
</div>