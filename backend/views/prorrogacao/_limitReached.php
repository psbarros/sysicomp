<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Prorrogacao */

$this->title = 'Registrar Prorrogação';
$this->params['breadcrumbs'][] = ['label' => 'Gerenciar prorrogações', 'url' => ['index']];
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
                echo 'O aluno "'.$model->aluno->nome.'" <b>atingiu o limite máximo de prorrogações</b>';      
            ?>
        </p>
        <p>
            <?php
                echo '<b>À partir de agora, ele não poderá fazer novas solicitações de prorrogações</b>';      
            ?>
        </p>
        <br>
        <p>
            <?= Html::a('<span class="fa fa-check"></span> Ok', ['prorrogacao/view', 'id'=>$model->id], [
                'class' => 'btn btn-primary',
            ]);
            ?>
        </p>
    </div>
</div>