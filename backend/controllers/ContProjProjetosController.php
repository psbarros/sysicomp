<?php

namespace backend\controllers;

use app\models\User;
use backend\models\ContProjAgencias;
use backend\models\ContProjBancos;
use backend\models\ContProjDespesas;
use backend\models\ContProjDespesasSearch;
use backend\models\ContProjReceitas;
use backend\models\ContProjReceitasSearch;
use backend\models\ContProjRubricas;
use backend\models\ContProjRubricasdeProjetos;
use backend\models\ContProjRubricasdeProjetosSearch;
use backend\models\ContProjTransferenciasSaldoRubricasSearch;
use PhpOffice\PhpWord\PhpWord;
use Yii;
use backend\models\ContProjProjetos;
use backend\models\ContProjProjetosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use NumberFormatter;
use yii\helpers\ArrayHelper;
use yii\web\Exception;
use yii\web\UploadedFile;

/**
 * ContProjProjetosController implements the CRUD actions for ContProjProjetos model.
 */
class ContProjProjetosController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ContProjProjetos models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ContProjProjetosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ContProjProjetos model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    public function actionDetalhado($idProjeto)
    {
        $searchModel = new ContProjReceitasSearch();
        $searchModel2 = new ContProjDespesasSearch();
        $rubricas = ContProjRubricasdeProjetos::find()->select("j17_contproj_rubricasdeprojetos.*, j17_contproj_rubricas.nome as nomerubrica")
            ->leftJoin("j17_contproj_rubricas", "j17_contproj_rubricas.id = rubrica_id")->where("projeto_id = $idProjeto")->all();
        $data=[];
        $data2=[];
        for($i=0;$i<count($rubricas);$i++) {

            $dataProvider = $searchModel->searchByRubrica(Yii::$app->request->queryParams,$rubricas[$i]->id);
            $dataProvider2= $searchModel2->searchByRubrica(Yii::$app->request->queryParams,$rubricas[$i]->id);
            $data[]=$dataProvider;
            $data2[]=$dataProvider2;
        }
        return $this->render('detalhado', [
            'rubricas'=>$rubricas,
            'data' => $data,
            'data2' => $data2,
        ]);
    }

    public function actionRelatorio()
    {
        $searchModel = new ContProjRubricasdeProjetosSearch();
        $dataProviderCapital = $searchModel->searchCapital(Yii::$app->request->queryParams);
        $dataProviderCusteio = $searchModel->searchCusteio(Yii::$app->request->queryParams);
        $searchModelTransferencias = new ContProjTransferenciasSaldoRubricasSearch();
        $dataProviderTransferencias = $searchModelTransferencias->search(Yii::$app->request->queryParams);
        return $this->render('relatorio', [
            'searchModel' => $searchModel,
            'dataProviderCapital' => $dataProviderCapital,
            'dataProviderCusteio' => $dataProviderCusteio,
            'searchModelTransferencias' => $searchModelTransferencias,
            'dataProviderTransferencias' => $dataProviderTransferencias,
        ]);
    }

    public function actionCancelar()
    {
        $this->mensagens('error', 'Cadastro de Projeto', 'O cadastro do projeto foi cancelado!');
        return $this->redirect(['index']);
    }

    public function actionCancelarr($id)
    {
        //$this->mensagens('error', ' de Projeto',  'O cadastro do projeto foi cancelado!');
        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Creates a new ContProjProjetos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ContProjProjetos();
        $coordenadores = ArrayHelper::map(User::find()->orderBy('nome')->where('professor=1')->all(), 'id', 'nome');
        $agencias = ArrayHelper::map(ContProjAgencias::find()->orderBy('nome')->all(), 'id', 'nome');
        $bancos = ArrayHelper::map(ContProjBancos::find()->orderBy('nome')->all(), 'id', 'nome');
        $model->editalArquivo = UploadedFile::getInstance($model, 'editalArquivo');
        if ($model->editalArquivo) {
            $model->edital = "uploads/" . date('dmYhms') . "_" . $model->editalArquivo->name;
            $model->editalArquivo->saveAs($model->edital);
        }
        $model->propostaArquivo = UploadedFile::getInstance($model, 'propostaArquivo');
        if ($model->propostaArquivo) {
            $model->proposta = "uploads/" . date('dmYhms') . "_" . $model->propostaArquivo->name;
            $model->propostaArquivo->saveAs($model->proposta);
        }
        $model->saldo = 0;
        $model->status = "Cadastrado";
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->data_inicio = date('Y-m-d', strtotime($model->data_inicio));
            $model->data_fim = date('Y-m-d', strtotime($model->data_fim));
            $model->data_fim_alterada = $model->data_fim;
            $model->save();
            $this->mensagens('success', 'Cadastro de Projeto', 'Projeto cadastrado com sucesso!');
            return $this->redirect(['index', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'coordenadores' => $coordenadores,
                'agencias' => $agencias,
                'bancos' => $bancos,
            ]);
        }

    }

    /**
     * Updates an existing ContProjProjetos model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $coordenadores = ArrayHelper::map(User::find()->orderBy('nome')->where('professor=1')->all(), 'id', 'nome');
        $agencias = ArrayHelper::map(ContProjAgencias::find()->orderBy('nome')->all(), 'id', 'nome');
        $bancos = ArrayHelper::map(ContProjBancos::find()->orderBy('nome')->all(), 'id', 'nome');
        $model->editalArquivo = UploadedFile::getInstance($model, 'editalArquivo');
        if ($model->editalArquivo) {
            $model->edital = "uploads/" . date('dmYhms') . "_" . $model->editalArquivo->name;
            $model->editalArquivo->saveAs($model->edital);
        }
        $model->propostaArquivo = UploadedFile::getInstance($model, 'propostaArquivo');
        if ($model->propostaArquivo) {
            $model->proposta = "uploads/" . date('dmYhms') . "_" . $model->propostaArquivo->name;
            $model->propostaArquivo->saveAs($model->proposta);
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->data_inicio = date('Y-m-d', strtotime($model->data_inicio));
            $model->data_fim = date('Y-m-d', strtotime($model->data_fim));
            $model->save();
            $this->mensagens('success', 'Projeto', 'Dados atualizados com sucesso!');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $model->data_inicio = date("d-m-Y", strtotime($model->data_inicio));
            $model->data_fim = date("d-m-Y", strtotime($model->data_fim));
            return $this->render('update', [
                'model' => $model,
                'coordenadores' => $coordenadores,
                'agencias' => $agencias,
                'bancos' => $bancos,
            ]);
        }
    }


    public function actionProrrogar($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->validate(false)) {
            $model->data_fim_alterada = date('Y-m-d', strtotime($model->data_fim_alterada));
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $model->data_fim_alterada = null;
            return $this->render('prorrogar', [
                'model' => $model,
            ]);
        }
    }

    public function actionForm2($id)
    {
        $projeto = ContProjProjetos::find()->select("*")->where("id=$id")->one();
        $coordenador = \app\models\User::find()->select("*")->where("id=$projeto->coordenador_id")->one();
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('uploads/Form2.docx');
        $templateProcessor->setValue('outorgado', $coordenador->nome);
        $templateProcessor->setValue('saldo', "R$ ".number_format($projeto->saldo, 2) );
        $templateProcessor->setValue('reais', 'R$');
        $receitas = ContProjReceitas::find()->select(["*,j17_contproj_receitas.ordem_bancaria AS ordem, 
            SUM(j17_contproj_receitas.valor_receita) AS total"])
            ->leftJoin("j17_contproj_rubricasdeprojetos", "j17_contproj_receitas.rubricasdeprojetos_id = j17_contproj_rubricasdeprojetos.id")
            ->where("j17_contproj_rubricasdeprojetos.projeto_id = $id")
            ->groupBy("j17_contproj_receitas.ordem_bancaria")
            ->orderBy("data")->all();

        $total = 0;
        $tamanho = count($receitas);
        $templateProcessor->cloneRow('datReceita', $tamanho);
        for ($i = 0; $i < $tamanho; $i++) {
            $templateProcessor->setValue('datReceita#' . ($i + 1), date("d/m/Y", strtotime($receitas[$i]->data)));
            $templateProcessor->setValue('ordem#' . ($i + 1), $receitas[$i]->ordem_bancaria);
            $templateProcessor->setValue('valorReceita#' . ($i + 1), "R$ ".number_format($receitas[$i]->total, 2));
            $total = $total + $receitas[$i]->total;
        }
        $templateProcessor->setValue('valorTotal', "R$ ".number_format($total, 2) );
        $despesas = ContProjDespesas::find()->select(["*"])
            ->leftJoin("j17_contproj_rubricasdeprojetos", "j17_contproj_despesas.rubricasdeprojetos_id = j17_contproj_rubricasdeprojetos.id")
            ->where("j17_contproj_rubricasdeprojetos.projeto_id = $id")->all();
        $tamanho = count($despesas);
        $templateProcessor->cloneRow('num', $tamanho);
        for ($i = 0; $i < $tamanho; $i++) {
            $templateProcessor->setValue('num#' . ($i + 1), $despesas[$i]->ident_cheque);
            $templateProcessor->setValue('dat#' . ($i + 1), date("d/m/Y", strtotime($despesas[$i]->data_emissao)));
            $templateProcessor->setValue('numero#' . ($i + 1), $despesas[$i]->nf);
            $templateProcessor->setValue('favorecido#' . ($i + 1), $despesas[$i]->favorecido);
            $templateProcessor->setValue('valorDespesa#' . ($i + 1), "R$ ".number_format($despesas[$i]->valor_despesa, 2) );
        }

        header('Content-Disposition: attachment; filename="Form2.docx"');
        $templateProcessor->saveAs('php://output');
    }

    public function actionForm8($id)
    {
        $projeto = ContProjProjetos::find()->select("*")->where("id=$id")->one();
        $coordenador = \app\models\User::find()->select("*")->where("id=$projeto->coordenador_id")->one();
        $rubricas = ContProjRubricasdeProjetos::find()->select("j17_contproj_rubricas.nome as nomerubrica,valor_total,valor_gasto")
            ->leftJoin("j17_contproj_rubricas", "rubrica_id=j17_contproj_rubricas.id")
            ->where("projeto_id=$id")->all();
        $tamanho = count($rubricas);
        $totalSolicitado = 0;
        $totalGasto = 0;
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('uploads/Form8.docx');
        $templateProcessor->cloneRow('rubrica', $tamanho);
        $templateProcessor->setValue('outorgado', $coordenador->nome);
        for ($i = 0; $i < $tamanho; $i++) {
            $templateProcessor->setValue('rubrica#' . ($i + 1), $rubricas[$i]->nomerubrica);
            $templateProcessor->setValue('valorSolicitado#' . ($i + 1), "R$ " . number_format($rubricas[$i]->valor_total, 2));
            $templateProcessor->setValue('valorGasto#' . ($i + 1), "R$ " . number_format($rubricas[$i]->valor_gasto, 2));
            $totalSolicitado = $totalSolicitado + $rubricas[$i]->valor_total;
            $totalGasto = $totalGasto + $rubricas[$i]->valor_gasto;
        }
        $templateProcessor->setValue('totalSolicitado', "R$ " . number_format($totalSolicitado, 2));
        $templateProcessor->setValue('totalGasto', "R$ " . number_format($totalGasto, 2));
        header('Content-Disposition: attachment; filename="Form8-Tabela-de-custo-8.docx"');
        $templateProcessor->saveAs('php://output');
    }

    public function actionForm3($id)
    {
        $projeto = ContProjProjetos::find()->select("*")->where("id=$id")->one();
        $coordenador = \app\models\User::find()->select("*")->where("id=$projeto->coordenador_id")->one();
        $despesas = ContProjDespesas::find()->select("*,j17_contproj_despesas.descricao as descricao")
            ->leftJoin("j17_contproj_rubricasdeprojetos", "j17_contproj_despesas.rubricasdeprojetos_id = j17_contproj_rubricasdeprojetos.id")
            ->leftJoin("j17_contproj_rubricas", "j17_contproj_rubricas.id = j17_contproj_rubricasdeprojetos.rubrica_id")
            ->where("j17_contproj_rubricasdeprojetos.projeto_id = $id AND j17_contproj_rubricas.tipo = 'Capital'")->all();
        $tamanho = count($despesas);
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('uploads/Form3.docx');
        $templateProcessor->setValue('reais', 'R$');
        $templateProcessor->cloneRow('qte', $tamanho);
        $templateProcessor->setValue('outorgado', $coordenador->nome);
        for ($i = 0; $i < $tamanho; $i++) {
            $templateProcessor->setValue('qte#' . ($i + 1), $despesas[$i]->quantidade);
            $templateProcessor->setValue('numero#' . ($i + 1), $despesas[$i]->nf);
            $templateProcessor->setValue('dat#' . ($i + 1), date("d/m/Y", strtotime($despesas[$i]->data_emissao)));
            $templateProcessor->setValue('numero#' . ($i + 1), $despesas[$i]->nf);
            $templateProcessor->setValue('descricao#' . ($i + 1), $despesas[$i]->descricao);
            $templateProcessor->setValue('unidade#' . ($i + 1), $despesas[$i]->quantidade);
            $templateProcessor->setValue('total#' . ($i + 1), "R$ ".number_format($despesas[$i]->valor_despesa, 2) );
            //${unidade#1}	${total#1} ${unidade#1}	${total#1}

        }
        header('Content-Disposition: attachment; filename="Form3-RelBensdeCapital-4.docx"');
        $templateProcessor->saveAs('php://output');
    }


    public function actionForm1($id)
    {
        $projeto = ContProjProjetos::find()->select("*")->where("id=$id")->one();
        $coordenador = \app\models\User::find()->select("*")->where("id=$projeto->coordenador_id")->one();
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('uploads/Form1.docx');
        $templateProcessor->setValue('orcamento', 'R$ ' . number_format($projeto->orcamento, 2));
        $templateProcessor->setValue('outorgado', $coordenador->nome);
        header('Content-Disposition: attachment; filename="Form1.docx"');
        $templateProcessor->saveAs('php://output');
    }

    public function actionExcel($id)
    {

        $rubricas = ContProjRubricasdeProjetos::find()->select("j17_contproj_rubricasdeprojetos.*,
                    j17_contproj_rubricas.nome as nomerubrica,
                    j17_contproj_rubricas.codigo as codigo,
                    j17_contproj_rubricas.tipo as tipo")
                    ->leftJoin("j17_contproj_rubricas", "j17_contproj_rubricas.id = j17_contproj_rubricasdeprojetos.rubrica_id")
                    ->where("projeto_id=$id")->all();

        $despesas = ContProjDespesas::find()->select("j17_contproj_despesas.*,j17_contproj_rubricas.codigo as codigo,j17_contproj_rubricas.nome as nomeRubrica")
                    ->leftJoin("j17_contproj_rubricasdeprojetos", "j17_contproj_despesas.rubricasdeprojetos_id = j17_contproj_rubricasdeprojetos.id")
                    ->leftJoin("j17_contproj_rubricas","j17_contproj_rubricas.id = j17_contproj_rubricasdeprojetos.rubrica_id")
                    ->where("j17_contproj_rubricasdeprojetos.projeto_id=$id")->all();
					
        // Create new PHPExcel object
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->createSheet(1);

        $default_border = array(
            'style' => \PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb'=>\PHPExcel_Style_Color::COLOR_BLACK)
        );

        $grey = array(
            'fill' => array(
                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('argb'=>'FFd3d3d3'),
            )
        );
		
		 $header = array(
            'font'  => array(
                'bold'  => true,
                'color' => array('argb' => \PHPExcel_Style_Color::COLOR_BLACK),
                'size'  => 24,
                'name'  => 'Calibri'
            ),
			'alignment' => array(
				'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
        );

        $styleArray = array(
            'font'  => array(
                'bold'  => true,
                'color' => array('argb' => \PHPExcel_Style_Color::COLOR_WHITE),
                'size'  => 12,
                'name'  => 'Calibri'
            ),
            'fill' => array(
                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('argb'=>\PHPExcel_Style_Color::COLOR_BLACK),
            ),
			'alignment' => array(
				'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
        );

        $style =array(
            'fill' => array(
                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('argb'=>'26fc8b85'),
            ));

        $styleRecebido =array(
            'fill' => array(
                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('argb'=>'2695c9dd'),
            ),
        );

        $styleGasto =array(
            'fill' => array(
                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('argb'=>'0Dfc8b85'),
        ));

        $styleDisponivel =array(
            'fill' => array(
                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('argb'=>'4D95eb59'),
            ));

        $border = array(
            'borders' => array(
                'bottom' => $default_border,
                'left' => $default_border,
                'top' => $default_border,
                'right' => $default_border,
            ),
        );

        $style = array(
            'alignment' => array(
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );

        $objPHPExcel->setActiveSheetIndex(1);

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(70);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(70);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);


        $objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('A1', 'Movimentação Geral')
			->setCellValue('A3', 'Natureza Despesa')
			->setCellValue('B3', 'CNPJ/CPF')
			->setCellValue('C3', 'Favorecido')
			->setCellValue('D3', 'Número Cheque')
			->setCellValue('E3', 'Data Emissão Cheque')
			->setCellValue('F3', 'Nota Fiscal')
			->setCellValue('G3', 'Data Emissão')
            ->setCellValue('H3', 'Especificação')
            ->setCellValue('I3', 'Valor Total');
        
		$objPHPExcel->getActiveSheet()->mergeCells('A1:I1');
		$objPHPExcel->getActiveSheet()->getStyle('A1:I1')->applyFromArray($header);
		
		$objPHPExcel->getActiveSheet()->getStyle('A3:I3')->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('A3:I3')->getAlignment()->setWrapText(true); 

        $objPHPExcel->getActiveSheet()
            ->getStyle('I4:I'.(count($despesas)+3))
            ->getNumberFormat()
            ->setFormatCode(
                '"RS "_-#,##0.00'
            );

        $objPHPExcel->getActiveSheet()->getStyle('A4:I'.(count($despesas)+4))->applyFromArray($style);
        for($i=0;$i<count($despesas);$i++){
            $data = date('d/m/Y', strtotime($despesas[$i]->data_emissao));
            $objPHPExcel->setActiveSheetIndex(1)->setCellValue('A'.($i+4).'', $despesas[$i]->nomeRubrica);
			$objPHPExcel->setActiveSheetIndex(1)->setCellValue('B'.($i+4).'', $despesas[$i]->cnpj_cpf);
			$objPHPExcel->setActiveSheetIndex(1)->setCellValue('C'.($i+4).'', $despesas[$i]->favorecido);
			$objPHPExcel->setActiveSheetIndex(1)->setCellValue('D'.($i+4).'', $despesas[$i]->ident_cheque);
			$objPHPExcel->setActiveSheetIndex(1)->setCellValue('E'.($i+4).'', date('d/m/Y', strtotime($despesas[$i]->data_emissao_cheque)));
			$objPHPExcel->setActiveSheetIndex(1)->setCellValue('F'.($i+4).'', $despesas[$i]->nf);
			$objPHPExcel->setActiveSheetIndex(1)->setCellValue('G'.($i+4).'', $data);
            $objPHPExcel->setActiveSheetIndex(1)->setCellValue('H'.($i+4).'', $despesas[$i]->descricao);
            $objPHPExcel->setActiveSheetIndex(1)->setCellValue('I'.($i+4).'', $despesas[$i]->quantidade * $despesas[$i]->valor_unitario); //'=(D'.($i+3).')*(E'.($i+3).')');
            if(($i+4) % 2 == 1 ){
                $objPHPExcel->getActiveSheet()->getStyle('A'.($i+4).':I'.($i+4).'')->applyFromArray($grey);
            }
        }
		
		$i++;
		
        //if(($i+4)%2 == 1 ){
            $objPHPExcel->getActiveSheet()->getStyle('A'.($i+4).':I'.($i+4).'')->applyFromArray($grey);
        //}

        $objPHPExcel->setActiveSheetIndex(1)
            ->setCellValue('A'.($i+4), '')
            ->setCellValue('B'.($i+4), '')
            ->setCellValue('C'.($i+4), '')
            ->setCellValue('D'.($i+4), '')
			->setCellValue('E'.($i+4), '')
			->setCellValue('F'.($i+4), '')
			->setCellValue('G'.($i+4), '')
            ->setCellValue('H'.($i+4), 'Total Gasto')
            ->setCellValue('I'.($i+4).'', '=SUM(I4:I'.($i+3).')');

        $objPHPExcel->getActiveSheet()->getStyle('A'.($i+4).':I'.($i+4))->getFont()->setBold(true);

        $objPHPExcel->getActiveSheet()->setTitle('Movimentação das Despesas');

        $objPHPExcel->setActiveSheetIndex(0);

        //$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(50);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getStyle('A3:D3')->applyFromArray($styleArray);


        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Movimentação Financeira')
			//->setCellValue('A3', 'Código')
            ->setCellValue('A3', 'Rubrica')
            ->setCellValue('B3', 'Valor Recebido')
            ->setCellValue('C3', 'Valor Gasto')
            ->setCellValue('D3', 'Valor Disponível');

        $objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
		$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->applyFromArray($header);
		
		$objPHPExcel->getActiveSheet()
            ->getStyle('B4:D'.(count($rubricas)+6))
            ->getNumberFormat()
            ->setFormatCode(
                '"RS "_-#,##0.00'
            );
        for($i=0;$i<count($rubricas);$i++){
            //$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.($i+4).'', $rubricas[$i]->codigo);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.($i+4).'', $rubricas[$i]->nomerubrica);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.($i+4).'', $rubricas[$i]->valor_total);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.($i+4).'', $rubricas[$i]->valor_gasto);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.($i+4).'', '=(B'.($i+4).'- C'.($i+4).')');
            $objPHPExcel->getActiveSheet()->getStyle('A'.($i+4).':D'.($i+4).'')->applyFromArray($border);
            if(($i+4)%2 ==0 ){
                $objPHPExcel->getActiveSheet()->getStyle('A'.($i+4).':D'.($i+4).'')->applyFromArray($grey);
            }
        }
        if(($i+4)%2 ==0 ){
            $objPHPExcel->getActiveSheet()->getStyle('A'.($i+4).':D'.($i+4).'')->applyFromArray($grey);
        }
        if(($i+5)%2 ==0 ){
            $objPHPExcel->getActiveSheet()->getStyle('A'.($i+4).':D'.($i+4).'')->applyFromArray($grey);
        }

        $objPHPExcel->getActiveSheet()->getStyle('A'.($i+4).':D'.($i+4).'')->applyFromArray($border);
        $objPHPExcel->getActiveSheet()->getStyle('A'.($i+5).':D'.($i+5).'')->applyFromArray($border);
        //$objPHPExcel->getActiveSheet()->getStyle('C4:C'.($i+5).'')->applyFromArray($styleRecebido);
        $objPHPExcel->getActiveSheet()->getStyle('B4:B'.($i+5).'')->applyFromArray($styleRecebido);
        $objPHPExcel->getActiveSheet()->getStyle('C4:C'.($i+5).'')->applyFromArray($styleGasto);
        $objPHPExcel->getActiveSheet()->getStyle('D4:D'.($i+5).'')->applyFromArray($styleDisponivel);


        $objPHPExcel->setActiveSheetIndex(0)
         //   ->setCellValue('A'.($i+4), 'TAR_BAN')
            ->setCellValue('A'.($i+4), 'Tarifa Bancária')
            ->setCellValue('B'.($i+4), '0')
            ->setCellValue('C'.($i+4), '0')
            ->setCellValue('D'.($i+4).'', '0');

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.($i+5), 'Total')
            //->setCellValue('B'.($i+5), '')
            ->setCellValue('B'.($i+5), '=SUM(B4:B'.($i+4).')')
            ->setCellValue('C'.($i+5), '=SUM(C4:C'.($i+4).')')
            ->setCellValue('D'.($i+5).'', '=SUM(D4:D'.($i+4).')');


        $objPHPExcel->getActiveSheet()->getStyle('A'.($i+5).':D'.($i+5))->getFont()->setBold(true);

        $objPHPExcel->getActiveSheet()->getStyle('A3:D'.($i+5).'')->applyFromArray($border);


        $j = $i + 8;

        $objPHPExcel->getActiveSheet()
            ->getStyle('B'.($j+1).':D'.($j+4))
            ->getNumberFormat()
            ->setFormatCode(
                '"RS "_-#,##0.00'
            );

        $objPHPExcel->getActiveSheet()->getStyle('A'.$j.':D'.$j)->applyFromArray($styleArray);
        $objPHPExcel->setActiveSheetIndex(0)
            //->setCellValue('A'.$j, 'Código')
            ->setCellValue('A'.$j, 'Rubrica')
            ->setCellValue('B'.$j, 'Valor Recebido')
            ->setCellValue('C'.$j, 'Valor Gasto')
            ->setCellValue('D'.$j, 'Valor Disponível');


        $capitalRecebido = ContProjRubricasdeProjetos::find()->select(["*"])
            ->leftJoin("j17_contproj_rubricas", "j17_contproj_rubricas.id = j17_contproj_rubricasdeprojetos.rubrica_id")
            ->where("projeto_id=$id AND j17_contproj_rubricas.tipo = 'Capital' ")->sum("j17_contproj_rubricasdeprojetos.valor_total");
        $capitalGasto = ContProjRubricasdeProjetos::find()->select(["*"])
            ->leftJoin("j17_contproj_rubricas", "j17_contproj_rubricas.id = j17_contproj_rubricasdeprojetos.rubrica_id")
            ->where("projeto_id=$id AND j17_contproj_rubricas.tipo = 'Capital' ")->sum("j17_contproj_rubricasdeprojetos.valor_gasto");
        $capitalDisponivel = ContProjRubricasdeProjetos::find()->select(["*"])
            ->leftJoin("j17_contproj_rubricas", "j17_contproj_rubricas.id = j17_contproj_rubricasdeprojetos.rubrica_id")
            ->where("projeto_id=$id AND j17_contproj_rubricas.tipo = 'Capital' ")->sum("j17_contproj_rubricasdeprojetos.valor_disponivel");

        $objPHPExcel->setActiveSheetIndex(0)
            //->setCellValue('A'.($j+1), 'CAP')
            ->setCellValue('A'.($j+1), 'Capital')
            ->setCellValue('B'.($j+1), $capitalRecebido)
            ->setCellValue('C'.($j+1), $capitalGasto)
            ->setCellValue('D'.($j+1).'', $capitalDisponivel);


        $custeioRecebido = ContProjRubricasdeProjetos::find()->select(["*"])
            ->leftJoin("j17_contproj_rubricas", "j17_contproj_rubricas.id = j17_contproj_rubricasdeprojetos.rubrica_id")
            ->where("projeto_id=$id AND j17_contproj_rubricas.tipo = 'Custeio' ")->sum("j17_contproj_rubricasdeprojetos.valor_total");
        $custeioGasto = ContProjRubricasdeProjetos::find()->select(["*"])
            ->leftJoin("j17_contproj_rubricas", "j17_contproj_rubricas.id = j17_contproj_rubricasdeprojetos.rubrica_id")
            ->where("projeto_id=$id AND j17_contproj_rubricas.tipo = 'Custeio' ")->sum("j17_contproj_rubricasdeprojetos.valor_gasto");
        $custeioDisponivel = ContProjRubricasdeProjetos::find()->select(["*"])
            ->leftJoin("j17_contproj_rubricas", "j17_contproj_rubricas.id = j17_contproj_rubricasdeprojetos.rubrica_id")
            ->where("projeto_id=$id AND j17_contproj_rubricas.tipo = 'Custeio' ")->sum("j17_contproj_rubricasdeprojetos.valor_disponivel");

        $objPHPExcel->setActiveSheetIndex(0)
           // ->setCellValue('A'.($j+2), 'CUS')
            ->setCellValue('A'.($j+2), 'Custeio')
            ->setCellValue('B'.($j+2), $custeioRecebido)
            ->setCellValue('C'.($j+2), $custeioGasto)
            ->setCellValue('D'.($j+2).'', $custeioDisponivel);

        $objPHPExcel->setActiveSheetIndex(0)
            //->setCellValue('A'.($j+3), 'TAR')
            ->setCellValue('A'.($j+3), 'Tarifas Bancárias')
            ->setCellValue('B'.($j+3), '0')
            ->setCellValue('C'.($j+3), '0')
            ->setCellValue('D'.($j+3).'', '0');

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.($j+4), 'Total')
            //->setCellValue('B'.($j+4), '')
            ->setCellValue('B'.($j+4), '=SUM(B'.($j+1).': B'.($j+3).')')
            ->setCellValue('C'.($j+4), '=SUM(C'.($j+1).': C'.($j+3).')')
            ->setCellValue('D'.($j+4).'', '=SUM(D'.($j+1).': D'.($j+3).')');
			
		$objPHPExcel->getActiveSheet()->getStyle('A'.$j.':D'.($j+1).'')->applyFromArray($border);
		$objPHPExcel->getActiveSheet()->getStyle('A'.($j+1).':D'.($j+2).'')->applyFromArray($border);
		$objPHPExcel->getActiveSheet()->getStyle('A'.($j+2).':D'.($j+3).'')->applyFromArray($border);
		$objPHPExcel->getActiveSheet()->getStyle('A'.($j+3).':D'.($j+4).'')->applyFromArray($border);



        $objPHPExcel->getActiveSheet()->getStyle('B'.($j+1).':B'.($j+4).'')->applyFromArray($styleRecebido);
        $objPHPExcel->getActiveSheet()->getStyle('C'.($j+1).':C'.($j+4).'')->applyFromArray($styleGasto);
        $objPHPExcel->getActiveSheet()->getStyle('D'.($j+1).':D'.($j+4).'')->applyFromArray($styleDisponivel);
        $objPHPExcel->getActiveSheet()->getStyle('A'.($j+4).':D'.($j+4))->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->setTitle('Movimentação Financeira');


        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Planilha.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }

    public static function view($id)
    {
        $model = ContProjProjetosController::findModel($id);
        return ContProjProjetosController::render('cont-proj-projetos/view', [
            'model' => $model,
        ]);

    }

    /**
     * Deletes an existing ContProjProjetos model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id, $detalhe = false)
    {
        $model = $this->findModel($id);
        try {
            $model->delete();
            $this->mensagens('success', 'Excluir Projeto', 'Projeto excluido com sucesso!');
        } catch (\yii\base\Exception $e) {
            $this->mensagens('error', 'Excluir Projeto', 'Projeto não pode ser excluido pois existem rubricas associadas ao projeto!');
            if ($detalhe) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->redirect(['index']);
            }
        }
        return $this->redirect(['index']);
        //return $this->redirect(['index']);
    }

    /**
     * Finds the ContProjProjetos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ContProjProjetos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ContProjProjetos::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function mensagens($tipo, $titulo, $mensagem)
    {
        Yii::$app->session->setFlash($tipo, [
            'type' => $tipo,
            'icon' => 'home',
            'duration' => 5000,
            'message' => $mensagem,
            'title' => $titulo,
            'positonY' => 'top',
            'positonX' => 'center',
            'showProgressbar' => true,
        ]);
    }

}
