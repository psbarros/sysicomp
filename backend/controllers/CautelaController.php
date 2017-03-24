<?php





//use app\models\CautelaSearch;
//use yii\web\Controller;
//use yii\web\NotFoundHttpException;
//use yii\filters\VerbFilter;


namespace backend\controllers;

use Yii;
use backend\models\Cautela;
//use app\models\Cautela;
use common\models\User;
use app\models\AfastamentosSearch;
use backend\models\SignupForm;
use app\models\Afastamentos;
use backend\models\Equipamento;
use app\models\CautelaSearch;
use app\models\EquipamentoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;
use mPDF;
use yii\web\UploadedFile;
use yii\helpers\Html;

/**
 * CautelaController implements the CRUD actions for Cautela model.
 */
class CautelaController extends Controller
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

    public function actionTeste(){

    	$modelCautela = new Cautela();
    	$modelCautela->load(Yii::$app->request->post());
    	ECHO $modelCautela->idsmulticautela."<br></br>";
    	foreach (explode(",",$modelCautela->idsmulticautela) as $id){
    		$modelCautela2 = Cautela::findOne($id);
    		$modelEquipamento = Equipamento::findOne($modelCautela2->idEquipamento);
    		
            //echo $modelCautela2->NomeResponsavel."<br>----Equipamento: $modelEquipamento->NomeEquipamento..........Nota Fiscal: $modelEquipamento->NotaFiscal</br></br>";
    	}

    }

    /**
     * Lists all Cautela models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CautelaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Cautela model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Cautela model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Cautela();


        if($model->StatusCautela == "Em aberto"){
            $StatusCautela = 1;
        }
        else if($model->StatusCautela= "Concluída"){
            $StatusCautela = 2;
        }
        else if ($model->StatusCautela = "Em atraso"){
            $StatusCautela = 3;
        }


        /*if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->idCautela]);
        } */
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	
        	$equipamento = Equipamento::findOne($model->idEquipamento);
      		$equipamento->StatusEquipamento = Equipamento::getStatusEmUso();
        	$equipamento->save();
        	
        	$arq = UploadedFile::getInstance($model, 'ImagemCautela');

        	if($arq!==null){
        		$arquivo = $model->idCautela.'-'.$arq->baseName;
        		$arquivo = 'repositorio/cautelas/'.$arquivo.'.'.$arq->extension;
        		$model ->ImagemCautela = $arquivo;
        		$arq->saveAs($arquivo);
        		
        		$model->save();
        	}
        
        /*	if (!$model->save()) {
        		print_r($model->getErrors());
        		//echo Yii::$app->user->id;
        		return;
        	}
        */
        	return $this->redirect(['view', 'id' => $model->idCautela]);        
        
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Cautela model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
    	$model = $this->findModel($id);
    	$oldImage = $model->ImagemCautela;
    	if ($model->load(Yii::$app->request->post())) {
    		
    		$equipamento = Equipamento::findOne($model->idEquipamento);
    		$equipamento->StatusEquipamento = Equipamento::getStatusEmUso();
    		$equipamento->save();
    		 
    		$arq = UploadedFile::getInstance($model, 'ImagemCautela');
    		
    		if($arq!==null){
    			$arquivo = $model->idCautela.'-'.$arq->baseName;
    			$arquivo = 'repositorio/cautelas/'.$arquivo.'.'.$arq->extension;
    			$model ->ImagemCautela = $arquivo;
    			$arq->saveAs($arquivo);
    		}else{
        		$model ->ImagemCautela = $oldImage;
        	}
    		
    		if (!$model->save()) {
    			print_r($model->getErrors());
    			echo Yii::$app->user->id;
    			return;
    		}
    		
    		return $this->redirect(['view', 'id' => $model->idCautela]);
    	} else {
    		return $this->render('update', [
    				'model' => $model,
    		]);
    	}
    }

    function actionProdutos($id) {
    	setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');

        $model = $this->findModel($id);
        $idUsuario = Yii::$app->user->identity->id;
	
		$dadosBaixa = "";
		if($model->cautelaTemBaixa!==false){
			$dadosBaixa = '<p><b>Dados da Baixa:</b></p>
                    <table style=" margin-right: auto; width: 200px;" border="2">
                        <tbody>
                            <tr>
                                <td style="width: 30px;">
                                    <p><b>Recebedor:</b></p>
                                </td>
        		                <td style="width: 30px;">
                                    <p>'.$model->cautelaTemBaixa->Recebedor.'</p>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30px;">
                                    <p><b>Devolução:</b></p>
                                </td>
        		                <td style="width: 30px;">
                                    <p>'.$model->cautelaTemBaixa->DataDevolucao.'</p>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30px;">
                                    <p><b>Observação:</b></p>
                                </td>
        		                <td style="width: 30px;">
                                    <p>'.$model->cautelaTemBaixa->ObservacaoBaixaCautela.'</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>';
		}
		
        $modelEquipamento2 = Equipamento::findOne($model->idEquipamento);


        $pdf = new mPDF('utf-8','A4','','','15','15','42','30');




        $pdf->SetHTMLHeader('
                <table style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;">
                    <tr>
                        <td width="20%" align="center" style="font-family: serif;font-weight: bold; font-size: 175%;"> <img src = "img/logo-brasil.jpg" height="90px" width="90px"> </td>
                        <td width="60%" align="center" style="font-family: serif;font-weight: bold; font-size: 135%;">  PODER EXECUTIVO <br> MINISTÉRIO DA EDUCAÇÃO <br> UNIVERSIDADE FEDERAL DO AMAZONAS <br> INSTITUTO DE COMPUTAÇÃO </td>
                        <td width="20%" align="center" style="font-family: serif;font-weight: bold; font-size: 175%;"> <img src = "img/ufam.jpg" height="90px" width="70px"> </td>
                    </tr>
                </table>
                <hr>
        ');

        $pdf->SetHTMLFooter('

                <table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;">
                    <tr>
                        <td  colspan = "3" align="center" ><span style="font-weight: bold"> Av. Rodrigo Otávio, 6.200 - Campus Universitário Senador Arthur VirgÃ­lio Filho - CEP 69077-000 - Manaus, AM, Brasil </span></td>
                    </tr>
                    <tr>
                        <td width="33%" align="center" style="font-weight: bold; font-style: italic;">  Tel. (092) 3305-1193/2808/2809</td>
                        <td width="33%" align="center" style="font-weight: bold; font-style: italic;">  E-mail: secretaria@icomp.ufam.edu.br</td>
                        <td width="33%" align="center" style="font-weight: bold; font-style: italic;">  http://www.icomp.ufam.edu.br </td>
                    </tr>
                </table>
        ');
	
        $pdf->WriteHTML (' <br>
                    <table style= "margin-top:0px;" width="100%;">
                    <tr>
                        <td style="text-align:center;">
                            <b> CAUTELA DE SOLICITAÇÃO DE EQUIPAMENTO </b>
                        </td>

                    </tr>
                    </table>

                    <table width="100%" style="border-top: solid 1px; ">

                    <tr><td colspan="2"><br></td><tr>

                    <tr>
                        <td>
                            Os dados da cautela estão descritos a seguir:
                        </td>

                    </tr>
                    <h1> <br></br></h1>
                    </table>

                    <table style="margin-left: auto; margin-right: auto;" border="2">
                        <tbody>
                            <tr>
                                <td>
                                    <p><b>Respons&aacute;vel:</b></p>
                                </td>
                                <td colspan="3" width="612">
                                <p>'.$model->NomeResponsavel.'</p>
                                </td>
                            </tr>

                            <tr>
                                <td width="165">
                                    <p><b>Contato:</b></p>
                                </td>
                                <td width="185">
                                    <p>'.$model->TelefoneResponsavel.'</p>
                                </td>
                                <td width="66">
                                    <p><b>Email:</b></p>
                                </td>
                                <td width="331">
                                    <p>'.$model->Email.'</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    

                    <table style="margin-left: auto; margin-right: auto; width: 600px;" border="2">
                        <tbody>
                            <tr>
                                <td style="width: 147.5px;">
                                    <p><b>Origem:</b></p>
                                </td>
                                <td style="width: 179.5px;">
                                    <p><b>Nota Fiscal:</b></p>
                                </td>
                                <td style="width: 225px;">
                                    <p><b>Equipamento:</b></p>
                                </td>
                                <td style="width: 152px;">
                                    <p><b>N&ordm;. de S&eacute;rie:</b></p>
                                </td>
                            </tr>

                            <tr>
                                <td style="width: 147.5px;">
                                    <p>'.$model->OrigemCautela.'</p>
                                </td>
                                <td style="width: 179.5px;">
                                    <p>'.$modelEquipamento2->NotaFiscal.'</p>
                                </td>
                                <td style="width: 225px;">
                                    <p>'.$modelEquipamento2->NomeEquipamento.'</p>
                                </td>
                                <td style="width: 152px;">
                                    <p>'.$modelEquipamento2->Nserie.'</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <br>



                    <p style="text-align: justify;">Declaro assumir total responsabilidade por extravio ou danos verificados ap&oacute;s a retirada do equipamento; neste caso, providenciarei o reparo ou a reposi&ccedil;&atilde;o do item emprestado em prazo de 30 dias a contar da data de devolu&ccedil;&atilde;o. Afirmo ter verificado, antes da retirada, que o equipamento encontrava-se:&nbsp;</p>
                    <p style="text-align: left;">(&nbsp;&nbsp; ) em perfeitas condi&ccedil;&otilde;es de uso e bom estado de conserva&ccedil;&atilde;o&nbsp;</p>
                    <p>(&nbsp;&nbsp; ) com os seguintes problemas e/ou danos (descrev&ecirc;-los):</p>
                    <p>______________________________________________________________________________________________________</p>
                    <p>______________________________________________________________________________________________________</p>
                    <p>Validade da cautela: '.$model->Validade.' dias</p>

                    <p style="text-align: center;">Manaus, '.strftime('%d de %B de %Y', strtotime($model->dataInicial)).'</p>
					'.(($model->cautelaTemBaixa !== false)?'':
                    '<p style="text-align: center;">_________________________&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_________________________</p>
                    <p style="text-align: center;">Respons&aacute;vel&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;Coordenador</p>
					').'
        			'.$dadosBaixa.'
        			<p style="text-align: center;">&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; _________________________</p>
                    <p style="text-align: center;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Recebedor</p>


                    ');



        $pdf->Output('');

        $pdfcode = $pdf->output();
    }

    function actionProdutos2() {
    	setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
    	
        $model = new Cautela();
        $model->load(Yii::$app->request->post());

        //$model = $this->findModel($id);
        $idUsuario = Yii::$app->user->identity->id;
        $dados = "";
        $flagTemBaixa = false;
        foreach (explode(",",$model->idsmulticautela) as $id){
            $modelCautela2 = Cautela::findOne($id);
            $flagTemBaixa = $flagTemBaixa || ($modelCautela2->cautelaTemBaixa!==false);
            $modelEquipamento = Equipamento::findOne($modelCautela2->idEquipamento);
            $baixaLocal = "";
            if($modelCautela2->cautelaTemBaixa!==false){
            	$baixaLocal = '<b>Dados da Baixa:</b>
                    <table style=" margin-right: auto; width: 200px;" border="2">
                        <tbody>
                            <tr>
                                <td style="width: 145px;">
                                    <p><b>Recebedor:</b></p>
                                </td>
                                <td style="width: 175.5px;">
                                    <p><b>Devolução:</b></p>
                                </td>
                                <td style="width: 180px;">
                                    <p><b>Observação:</b></p>
                                </td>
                            </tr>
                            <tr>
        		                <td style="width: 145px;">
                                    <p>'.$modelCautela2->cautelaTemBaixa->Recebedor.'</p>
                                </td>
        		                <td style="width: 175.5px;">
                                    <p>'.$modelCautela2->cautelaTemBaixa->DataDevolucao.'</p>
                                </td>
        		                <td style="width: 180px;">
                                    <p>'.$modelCautela2->cautelaTemBaixa->ObservacaoBaixaCautela.'</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>';
            }
            	
            $dados = $dados
                    .
                    '<table style="margin-left: auto; margin-right: auto;" border="2">
                        <tbody>
                            <tr>
                                <td>
                                    <p><b>Respons&aacute;vel:</b></p>
                                </td>
                                <td colspan="3" width="612">
                                    <p><b>'.$modelCautela2->NomeResponsavel.'</b></p>
                                </td>
                            </tr>

                            <tr>
                                <td width="160px">
                                    <p><b>Contato:</b></p>
                                </td>
                                <td width="190px">
                                    <p>'.$modelCautela2->TelefoneResponsavel.'</p>
                                </td>
                                <td width="66px">
                                    <p><b>Email:</b></p>
                                </td>
                                <td width="331">
                                    <p>'.$modelCautela2->Email.'</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    

                    <table style="margin-left: auto; margin-right: auto; width: 600px;" border="2">
                        <tbody>
                            <tr>
                                <td style="width: 147.5px;">
                                    <p><b>Origem:</b></p>
                                </td>
                                <td style="width: 179.5px;">
                                    <p><b>Nota Fiscal:</b></p>
                                </td>
                                <td style="width: 225px;">
                                    <p><b>Equipamento:</b></p>
                                </td>
                                <td style="width: 152px;">
                                    <p><b>N&ordm;. de S&eacute;rie:</b></p>
                                </td>
                            </tr>

                            <tr>
                                <td style="width: 147.5px;">
                                    <p>'.$modelCautela2->OrigemCautela.'</p>
                                </td>
                                <td style="width: 179.5px;">
                                    <p>'.$modelEquipamento->NotaFiscal.'</p>
                                </td>
                                <td style="width: 225px;">
                                    <p>'.$modelEquipamento->NomeEquipamento.'</p>
                                </td>
                                <td style="width: 152px;">
                                    <p>'.$modelEquipamento->Nserie.'</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                                    		
                    <table style="margin-left: auto; margin-right: auto; width: 600px;" border="2">
                        <tbody>
                            <tr>
                                <td style="width: 147.5px;">
                                    <p><b>Data Inicial:</b></p>
                                </td>
                                <td style="width: 179.5px;">
                                    <p><b>Data Prevista:</b></p>
                                </td>
                                <td style="width: 230px;">
                                    <p><b>Validade:</b></p>
                                </td>
                            </tr>

                            <tr>
                                <td style="width: 147.5px;">
                                    <p>'.$modelCautela2->dataInicial.'</p>
                                </td>
                                <td style="width: 163px;">
                                    <p>'.$modelCautela2->DataDevolucao.'</p>
                                </td>
                                <td style="width: 373px;">
                                    <p>'.$modelCautela2->Validade.' dias</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    '.$baixaLocal.'
                    <hr>
                    <br>
                    ';

            //echo $modelCautela2->NomeResponsavel."<br>----Equipamento: $modelEquipamento->NomeEquipamento..........Nota Fiscal: $modelEquipamento->NotaFiscal</br></br>";
            $baixas = "";
           if($modelCautela2->cautelaTemBaixa !== false){ 
           	$baixas = $baixas
           					.'
           					 ';
           }
        }
		
        $assinaturas = "";
        if(!$flagTemBaixa)
			$assinaturas = '
							<p style="text-align: center;">_________________________&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_________________________</p>
		                    <p style="text-align: center;">Respons&aacute;vel&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;Coordenador</p>
							';
        
        $pdf = new mPDF('utf-8','A4','','','15','15','42','30');




        $pdf->SetHTMLHeader('
                <table style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;">
                    <tr>
                        <td width="20%" align="center" style="font-family: serif;font-weight: bold; font-size: 175%;"> <img src = "img/logo-brasil.jpg" height="90px" width="90px"> </td>
                        <td width="60%" align="center" style="font-family: serif;font-weight: bold; font-size: 135%;">  PODER EXECUTIVO <br> MINISTÉRIO DA EDUCAÇÃO <br> UNIVERSIDADE FEDERAL DO AMAZONAS <br> INSTITUTO DE COMPUTAÇÃO </td>
                        <td width="20%" align="center" style="font-family: serif;font-weight: bold; font-size: 175%;"> <img src = "img/ufam.jpg" height="90px" width="70px"> </td>
                    </tr>
                </table>
                <hr>
        ');

        $pdf->SetHTMLFooter('

                <table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;">
                    <tr>
                        <td  colspan = "3" align="center" ><span style="font-weight: bold"> Av. Rodrigo Otávio, 6.200 - Campus Universitário Senador Arthur VirgÃ­lio Filho - CEP 69077-000 - Manaus, AM, Brasil </span></td>
                    </tr>
                    <tr>
                        <td width="33%" align="center" style="font-weight: bold; font-style: italic;">  Tel. (092) 3305-1193/2808/2809</td>
                        <td width="33%" align="center" style="font-weight: bold; font-style: italic;">  E-mail: secretaria@icomp.ufam.edu.br</td>
                        <td width="33%" align="center" style="font-weight: bold; font-style: italic;">  http://www.icomp.ufam.edu.br </td>
                    </tr>
                </table>
        ');

        $pdf->WriteHTML (' <br>
                    <table style= "margin-top:0px;" width="100%;">
                    <tr>
                        <td style="text-align:center;">
                            <b> CAUTELA DE SOLICITAÇÃO DE EQUIPAMENTO </b>
                        </td>

                    </tr>
                    </table>

                    <table width="100%" style="border-top: solid 1px; ">

                    <tr><td colspan="2"><br></td><tr>

                    <tr>
                        <td>
        					<b>
                            Os dados da cautela estão descritos a seguir:
        					</b>
                        </td>

                    </tr>
                    <h1> <br></br></h1>
                    </table>

                    '.$dados.'



                    <p style="text-align: justify;">Declaro assumir total responsabilidade por extravio ou danos verificados ap&oacute;s a retirada do equipamento; neste caso, providenciarei o reparo ou a reposi&ccedil;&atilde;o do item emprestado em prazo de 30 dias a contar da data de devolu&ccedil;&atilde;o. Afirmo ter verificado, antes da retirada, que o equipamento encontrava-se:&nbsp;</p>
                    <p style="text-align: left;">(&nbsp;&nbsp; ) em perfeitas condi&ccedil;&otilde;es de uso e bom estado de conserva&ccedil;&atilde;o&nbsp;</p>
                    <p>(&nbsp;&nbsp; ) com os seguintes problemas e/ou danos (descrev&ecirc;-los):</p>
                    <p>______________________________________________________________________________________________________</p>
                    <p>______________________________________________________________________________________________________</p>

                    <p style="text-align: center;">Manaus, '.strftime('%d de %B de %Y', strtotime('today')).'</p>

                    '.$assinaturas.'

                    <p style="text-align: center;">&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; _________________________</p>
                    <p style="text-align: center;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Recebedor</p>


                    ');



        $pdf->Output('');

        $pdfcode = $pdf->output();
    }

    /**
     * Deletes an existing Cautela model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {	
    	$cautela = $this->findModel($id);
    	//Quando cautela possui apenas a Baixa
    	if($cautela->StatusCautela === Cautela::getStatusConcluida()){
    		if($cautela->cautelaTemBaixa->delete()===false  || $cautela->delete()===false)
    			$this->mensagens('danger', 'Erro na deleção', 'Contate o administrador.');
    		else 
    			$this->mensagens('success', 'Cautela Deletada', 'Cautela deletada com sucesso.');
    	}else{
    		//Quando a cautela não tem baixa ('Em uso' ou 'Em atraso'), mas tá prendendo um equipamento.
    		$cautela->cautelatemequipamento->StatusEquipamento = Equipamento::getStatusDisponivel();
    		$cautela->cautelatemequipamento->save();
    		$imagemPath = $cautela->ImagemCautela;
    		if($cautela->delete()===false){
    			$this->mensagens('danger', 'Erro na deleção', 'Contate o administrador.');
    			//Reverte a liberação do equipamento.
    			$cautela->cautelatemequipamento->StatusEquipamento = Equipamento::getStatusEmUso();
    			$cautela->cautelatemequipamento->save();
    		}else{
    			$this->mensagens('success', 'Cautela Deletada', 'Cautela deletada com sucesso.');
    			
    			if(trim($imagemPath)!=='' && file_exists($imagemPath)){
    				if(unlink($imagemPath) === false){
    					$this->mensagens('waning', 'Imagem da cautela Não foi deletada', 'Informe o administrador.');
    				}
    			}
    		}
    			
    	}
    	
        return $this->redirect(['index']);
    }

    /**
     * Finds the Cautela model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Cautela the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Cautela::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /* Envio de mensagens para views
     Tipo: success, danger, warning*/
    protected function mensagens($tipo, $titulo, $mensagem){
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
