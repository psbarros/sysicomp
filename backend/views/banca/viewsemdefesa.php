<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use app\models\Defesa;

/* @var $this yii\web\View */
/* @var $model app\models\Banca */

$this->title = 'Informações da Banca';
$this->params['breadcrumbs'][] = ['label' => 'Bancas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Bancas', 'url' => ['create']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banca-view">

    

    <p>
       <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ', ['banca/bancasemavaliacao',], ['class' => 'btn btn-warning']) ?>
       
       <div>
             <?= Html::a('<span class="fa fa-check-circle"></span> Deferir Banca', ['aprovar', "banca_id"=>$model->banca_id], [
            'class' => 'btn btn-primary',
            'data' => [
                'confirm' => 'Você tem certeza que deseja APROVAR essa banca?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('<span class="fa fa-remove"></span> Indeferir Banca',['indeferir', "banca_id"=>$model->banca_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Você tem certeza que deseja REPROVAR essa banca?',
                'method' => 'post',
            ],
        ]) ?>

       </div>
      

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'banca_id',
            [ 'attribute' => 'Titulo da Defesa',
               'value' => Defesa::findOne(['banca_id'=>$model->banca_id]) ? $model->defesa->titulo : "não possui defesa" ,

            ],
            [ 'attribute' => 'Aluno',
               'value' => Defesa::findOne(['banca_id'=>$model->banca_id]) ? $model->defesa->nome : "não possui defesa",

            ],


          /*  ['attribute' => 'funcao',
            'label' => "Funcao",
            'format' => "html",
            'value' => function ($model){
                if ($model->funcao === 'P'){
                    return "Presidente";
                }
                else if ($model->funcao === 'I') {
                    return "Membro Interno";
                }
                else if ($model->funcao === 'S') {
                    return "Suplente";
                }else{
                    return "Membro externo";
                }
            },
          ], */
        ],
    ]) ?>

    <h3> Detalhes da Banca </h3>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            "summary" => "",
            'columns' => [
                //['class' => 'yii\grid\SerialColumn'],

                //'banca_id',
                //'membrosbanca_id',
                [
                    'attribute'=>'nome_membro',
                    'label' => "Nome do Membro",
                ],
                [
                    'attribute'=>'membro_filiacao',
                    'label' => "Filiação do Membro",
                ],
                [
                    "attribute" => 'funcaomembro',
                    "label" => "Função",
                ],

            ],
        ]); ?>



</div>
