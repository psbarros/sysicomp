<?php

namespace backend\controllers;

use Yii;
use app\models\CautelaAvulsa;
use app\models\CautelaAvulsaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use kartik\mpdf\Pdf;
use mPDF;
/**
 * CautelaAvulsaController implements the CRUD actions for CautelaAvulsa model.
 */
class CautelaAvulsaController extends Controller
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
     * Lists all CautelaAvulsa models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CautelaAvulsaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CautelaAvulsa model.
     * @param integer $idCautelaAvulsa
     * @param integer $id
     * @return mixed
     */
    public function actionView($idCautelaAvulsa, $id)
    {
        return $this->render('view', [
            'model' => $this->findModel($idCautelaAvulsa, $id),
        ]);
    }
    
    public function actionView2($id){
    	return $this->render('view', [
    			'model' => $this->findModel2($id),
    	]);
    }

    /**
     * Creates a new CautelaAvulsa model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CautelaAvulsa();

        if($model->StatusCautelaAvulsa == "Em aberto"){
            $StatusCautelaAvulsa = 1;
        }
        else if($model->StatusCautelaAvulsa = "Concluída"){
            $StatusCautelaAvulsa = 2;
        }
        else if ($model->StatusCautelaAvulsa = "Em atraso"){
            $StatusCautelaAvulsa = 3;
        }
	
        //if ($model->load(Yii::$app->request->post()) && $model->save()) 
        	$model->id = Yii::$app->user->id;
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
            	$model->id = Yii::$app->user->id;
            	$arq = UploadedFile::getInstance($model, 'ImagemCautela');
            	if($arq!==null){
            		$arquivo = $model->idCautelaAvulsa.'-'.$arq->baseName;
            		$arquivo = 'repositorio/cautelasavulsas/'.$arquivo.'.'.$arq->extension;
            		$model ->ImagemCautela = $arquivo;
            		$arq->saveAs($arquivo);
            		
            		$model->save();
            	}
            	//$model->url = 'repositorio/'.$arquivo.'.'.$model->ImagemEquipamento->extension;
            
            
            	/*if (!$model->save()) {
            		print_r($model->getErrors());
            		echo Yii::$app->user->id;
            		return;
            	}*/

            	return $this->redirect(['view', 'idCautelaAvulsa' => $model->idCautelaAvulsa, 'id' => $model->id]);
            
            
            
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing CautelaAvulsa model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $idCautelaAvulsa
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($idCautelaAvulsa, $id)
    {
        $model = $this->findModel($idCautelaAvulsa, $id);
		$arquivoAntigo = $model->ImagemCautela;
        if ($model->load(Yii::$app->request->post())) {
        		$model->id = Yii::$app->user->id;
            	$arq = UploadedFile::getInstance($model, 'ImagemCautela');
            	if($arq!==null){
            		$arquivo = $model->idCautelaAvulsa.'-'.$arq->baseName;
            		$arquivo = 'repositorio/cautelasavulsas/'.$arquivo.'.'.$arq->extension;
            		$model ->ImagemCautela = $arquivo;
            		$arq->saveAs($arquivo);
            	}else{
            		$model->ImagemCautela = $arquivoAntigo;
            	}
            	//$model->url = 'repositorio/'.$arquivo.'.'.$model->ImagemEquipamento->extension;
            
            
            	if (!$model->save()) {
            		print_r($model->getErrors());
            		return;
            	}

            	return $this->redirect(['view', 'idCautelaAvulsa' => $model->idCautelaAvulsa, 'id' => $model->id]);
            
            
            
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing CautelaAvulsa model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $idCautelaAvulsa
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($idCautelaAvulsa)
    {	
    	$cautelaAvulsa = $this->findModel2($idCautelaAvulsa);
    	
    	$imagemPath = $cautelaAvulsa->ImagemCautela;
    	
    	if($cautelaAvulsa->cautelaAvulsaTemBaixa === false){
        	if($this->findModel2($idCautelaAvulsa)->delete() !== false){
        		$this->mensagens('success', 'Deleção Realizada', 'A cautela foi deletada.');
        		
        		if(trim($imagemPath)!=='' && file_exists($imagemPath)){
        			if(unlink($imagemPath) === false){
        				$this->mensagens('warning', 'Imagem da autela Não pode ser deletada', 'Informe o administrador.');
        			}
        		}
        	}
        	else
        		$this->mensagens('danger', 'Deleção NÃO Realizada', 'Contate o administrador.');
    	}else{
    		
    		if(($cautelaAvulsa->cautelaAvulsaTemBaixa->delete() !== false) && ($cautelaAvulsa->delete() !== false))
    			$this->mensagens('success', 'Deleção Realizada', 'A cautela e sua baixa foram deletadas.');
    		
    			if(trim($imagemPath)!=='' && file_exists($imagemPath)){
    				if(unlink($imagemPath) === false){
    					$this->mensagens('warning', 'Imagem da autela Não pode ser deletada', 'Informe o administrador.');
    				}
    			}
    		else
    			$this->mensagens('danger', 'Deleção NÃO Realizada', 'Contate o administrador. Pode haver inconsistência nos dados.');
    	}

        return $this->redirect(['index']);
    }

    public function actionGerapdfunico($id){

    	setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
    	
    	$model = $this->findModel2($id);
    	$idUsuario = Yii::$app->user->identity->id;
    	
    	$dadosBaixa = "";
    	if($model->cautelaAvulsaTemBaixa!==false){
    		$dadosBaixa = '<p><b>Dados da Baixa:</b></p>
                    <table style=" margin-right: auto; width: 200px;" border="2">
                        <tbody>
                            <tr>
                                <td style="width: 30px;">
                                    <p><b>Recebedor:</b></p>
                                </td>
        		                <td style="width: 30px;">
                                    <p>'.$model->cautelaAvulsaTemBaixa->Recebedor.'</p>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30px;">
                                    <p><b>Devolução:</b></p>
                                </td>
        		                <td style="width: 30px;">
                                    <p>'.$model->cautelaAvulsaTemBaixa->DataDevolucao.'</p>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30px;">
                                    <p><b>Observação:</b></p>
                                </td>
        		                <td style="width: 30px;">
                                    <p>'.$model->cautelaAvulsaTemBaixa->ObservacaoBaixaCautela.'</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>';
    	}
    	
    	//$modelEquipamento2 = Equipamento::findOne($model->idEquipamento);
    	
    	
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
    	
                    <table style="margin-right: auto;" border="2">
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
    	
    	
    	
                    <table style="margin-right: auto; width: 600px;" border="2">
                        <tbody>
                            <tr>
                                <td style="width: 290px;">
                                    <p><b>Origem:</b></p>
                                </td>
                                <td style="width: 225px;">
                                    <p><b>Equipamento:</b></p>
                                </td>
                            </tr>
    	
                            <tr>
                                <td style="width: 147.5px;">
                                    <p>'.$model->origem.'</p>
                                </td>
                                <td style="width: 225px;">
                                    <p>'.$model->NomeEquipamento.'</p>
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
					'.(($model->cautelaAvulsaTemBaixa !== false)?'':
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
    
    public function actionGerapdfcoletivo(){
    	
    	setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
    	 
    	$model = new CautelaAvulsa();
    	$model->load(Yii::$app->request->post());
    	
    	//$model = $this->findModel($id);
    	$idUsuario = Yii::$app->user->identity->id;
    	$dados = "";
    	$flagTemBaixa = false;
    	foreach (explode(",",$model->idsmulticautela) as $id){
    		$modelCautela2 = CautelaAvulsa::findOne($id);
    		$flagTemBaixa = $flagTemBaixa || ($modelCautela2->cautelaAvulsaTemBaixa!==false);
    		//$modelEquipamento = Equipamento::findOne($modelCautela2->idEquipamento);
    		$baixaLocal = "";
    		if($modelCautela2->cautelaAvulsaTemBaixa!==false){
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
                                    <p>'.$modelCautela2->cautelaAvulsaTemBaixa->Recebedor.'</p>
                                </td>
        		                <td style="width: 175.5px;">
                                    <p>'.$modelCautela2->cautelaAvulsaTemBaixa->DataDevolucao.'</p>
                                </td>
        		                <td style="width: 180px;">
                                    <p>'.$modelCautela2->cautelaAvulsaTemBaixa->ObservacaoBaixaCautela.'</p>
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
    	
    	
    	
                    <table style=" margin-right: auto; width: 600px;" border="2">
                        <tbody>
                            <tr>
                                <td style="width: 140.5px;">
                                    <p><b>Origem:</b></p>
                                </td>
                                <td style="width: 450px;">
                                    <p><b>Equipamento:</b></p>
                                </td>
                            </tr>
    	
                            <tr>
                                <td style="width: 140.5px;">
                                    <p>'.$modelCautela2->origem.'</p>
                                </td>
                                <td style="width: 450px;">
                                    <p>'.$modelCautela2->NomeEquipamento.'</p>
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
                                    <p>'.$modelCautela2->ValidadeCautela.'</p>
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
    		if($modelCautela2->cautelaAvulsaTemBaixa !== false){
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
     * Finds the CautelaAvulsa model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $idCautelaAvulsa
     * @param integer $id
     * @return CautelaAvulsa the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($idCautelaAvulsa, $id)
    {
        if (($model = CautelaAvulsa::findOne(['idCautelaAvulsa' => $idCautelaAvulsa, 'id' => $id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    protected function findModel2($id){
    	return CautelaAvulsa::findOne($id);
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
