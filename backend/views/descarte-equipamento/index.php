<?php

use yii\helpers\Html;
use yii\grid\GridView;

//teste

/* @var $this yii\web\View */
/* @var $searchModel app\models\DescarteEquipamentoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Descarte Equipamentos';
$this->params['breadcrumbs'][] = $this->title;

if( Yii::$app->user->identity->checarAcesso('secretariar') == 1){
  $action = " {view} {create} {update} {delete}";
}

?>
<div class="descarte-equipamento-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Descarte Equipamento', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'idDescarte',
            'NomeResponsavel',
            'Email:email',
            [
            	'attribute'=>'TelefoneResponsavel',
            	'value' =>'telefoneFormatado',
            ],
            'ObservacoesDescarte',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
