<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ContProjDespesasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$idProjeto = Yii::$app->request->get('idProjeto');
$this->title = 'Despesas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cont-proj-despesas-index">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ',
            ['cont-proj-projetos/view', 'id' => $idProjeto], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('Cadastrar despesa', ['create', 'idProjeto' => $idProjeto], ['class' => 'btn btn-success']) ?>
    </p>

    <?= $this->render('..\cont-proj-projetos\dados.php', [
        'idProjeto' => $idProjeto,
    ]) ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><b>Itens de Capital</b></h3>
        </div>
        <div class="panel-body">
            <?= GridView::widget([
                'dataProvider' => $dataProviderCapital,
                //'filterModel' =>  $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'favorecido',
                    'nomeRubrica',
					'nf',
                    [
                        'attribute'=>'data_emissao',
                        'format' => ['date', 'php:d/m/Y'],
                    ],
					'descricao',
                    'valor_despesa:currency',
                    'ident_cheque',
                    // 'data_emissao',
                    // 'nf',
                    // 'ident_cheque',
                    // 'data_emissao_cheque',
                    // 'valor_cheque',
                    // 'favorecido',
                    // 'cnpj_cpf',
                    // 'comprovante',

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'buttons' => [
                            'view' => function ($url, $model) use ($idProjeto) {
                                $url .= '&idProjeto=' . $idProjeto;
                                return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url);
                            },
                            'update' => function ($url, $model) use ($idProjeto) {
                                $url .= '&idProjeto=' . $idProjeto;
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url);
                                //return false;
                            },
                            'delete' => function ($url, $model) use ($idProjeto) {
                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->id, 'idProjeto' => $idProjeto], [
                                    'data' => [
                                        'confirm' => 'Deseja realmente remover esta receita?',
                                        'method' => 'post',
                                    ],
                                    'title' => Yii::t('yii', 'Remover Receita'),
                                ]);
                            }
                        ],
                    ],
                    //['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><b>Itens de Custeio</b></h3>
        </div>
        <div class="panel-body">
            <?= GridView::widget([
                'dataProvider' => $dataProviderCusteio,
                //8'filterModel' => $searchModelCusteio,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'favorecido',
                    'nomeRubrica',
					'nf',
                    [
                        'attribute'=>'data_emissao',
                        'format' => ['date', 'php:d/m/Y'],
                    ],
                    'descricao',
                    'valor_despesa:currency',
                    'ident_cheque',
                    // 'data_emissao',
                    // 'nf',
                    // 'ident_cheque',
                    // 'data_emissao_cheque',
                    // 'valor_cheque',
                    // 'favorecido',
                    // 'cnpj_cpf',
                    // 'comprovante',

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'buttons' => [
                            'view' => function ($url, $model) use ($idProjeto) {
                                $url .= '&idProjeto=' . $idProjeto;
                                return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url);
                            },
                            'update' => function ($url, $model) use ($idProjeto) {
                                $url .= '&idProjeto=' . $idProjeto;
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url);
                                //return false;
                            },
                            'delete' => function ($url, $model) use ($idProjeto) {
                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->id, 'idProjeto' => $idProjeto], [
                                    'data' => [
                                        'confirm' => 'Deseja realmente remover esta receita?',
                                        'method' => 'post',
                                    ],
                                    'title' => Yii::t('yii', 'Remover Receita'),
                                ]);
                            }
                        ],
                    ],
                    //['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>


        </div>
    </div>
</div>