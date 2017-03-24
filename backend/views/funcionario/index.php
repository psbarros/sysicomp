<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FuncionarioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Funcionarios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="funcionario-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Funcionario', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'nome',
            'cpf',
            'email:email',
            'endereco',
            // 'bairro',
            // 'cidade',
            // 'uf',
            // 'cep',
            // 'tel_residencial',
            // 'tel_celular',
            // 'data_ingresso',
            // 'siape',
            // 'cargo',
            // 'idUser',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
