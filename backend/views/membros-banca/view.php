<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\MembrosBanca */

$this->title = $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Membros Bancas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="membros-banca-view">
    <p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ', ['index'], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-edit"></span> Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-remove-sign"></span> Excluir', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'nome',
            'email:email',
            'filiacao',
            'telefone',
            'cpf',
            //'dataCadastro',
        ],
    ]) ?>

</div>
