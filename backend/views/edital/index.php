<?php

use yii\helpers\Html;
use yii\grid\GridView;
use xj\bootbox\BootboxAsset;

BootboxAsset::register($this);
BootboxAsset::registerWithOverride($this);

/* @var $this yii\web\View */
/* @var $searchModel app\models\SearchEdital */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Editais';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="edital-index">

    <table style="border:solid 1px; float:right; width: 20%;">
        <tr style="border:solid 1px;">
            <td colspan="2" style = "width: 20%; background-color: #C0C0C0; text-align:center;">
                Legenda
            </td>
        </tr>
        <tr style="background-color:lightblue" align="center">
            <td style="border:solid 1px;">
            REG
            </td>
            <td align="center">
            Vagas Regulares
            </td>
        </tr>
        <tr style="background-color: lightgreen; border:solid 1px;" align="center">
            <td style="border:solid 1px;">
            SUP
            </td>
            <td align="center">
            Vagas Suplementares
            </td>
        </tr>
    </table>


    <?= Html::a('<span class="fa fa-plus"></span> Novo Edital', ['create'], ['class' => 'btn btn-success']) ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'numero',
            [
                'label' => 'Início inscrições',
                'attribute' => 'datainicio',
                'value' => function ($model) {
                        return date("d-m-Y", strtotime($model->datainicio));
                },
            ],
            [
                'label' => 'Término inscrições',
                'attribute' => 'datafim',
                'value' => function ($model) {
                        return date("d-m-Y", strtotime($model->datafim));
                },
            ],
            [
                'label' => '#Inscrições Iniciadas',
                'attribute' => 'quantidadeinscritos',
                'value' => function ($model) {
                            return $model->getQuantidadeInscritos($model->numero);
                },
            ],
            [
                'label' => '#Inscrições Encerradas',
                'attribute' => 'quantidadeinscritosfinalizados',
                'value' => function ($model) {
                            return $model->getQuantidadeInscritosFinalizados($model->numero);
                },
            ],
            [
                'label' => 'Vagas Mestrado',
                'attribute' => 'vagasmestrado' ,
            ],
            [
                'label' => 'Vagas Doutorado',
                'attribute' => 'vagasdoutorado' ,

            ],
        
            ['class' => 'yii\grid\ActionColumn',
              'template'=>'{download} {view} {delete} {update}',
                'buttons'=>[
                  'download' => function ($url, $model) { 
                    return Html::a('<span class="glyphicon glyphicon-download"></span>', $model->documento, [
                            'target' => '_blank','title' => Yii::t('yii', 'Download do Edital'),
                    ]);
                  },
                  'delete' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->numero], [
                            'data' => [
                                'confirm' => 'Remover o edital \''.$model->numero.'\'?',
                                'method' => 'post',
                            ],
                            'title' => Yii::t('yii', 'Remover Edital'),
                    ]);   
                  }
              ]                            
                ],
        ],
    ]); ?>
</div>


