<?php

namespace backend\controllers;

use Yii;
use app\models\Afastamentos;
use common\models\User;
use app\models\AfastamentosSearch;
use backend\models\SignupForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use kartik\mpdf\Pdf;
use mPDF;
use yii\web\Exception;
use yii\helpers\Html;

/**
 * CandidatosController implements the CRUD actions for Afastamentos model.
 */
class AfastamentosController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                               return Yii::$app->user->identity->checarAcesso('professor') || Yii::$app->user->identity->checarAcesso('secretaria');
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Afastamentos models.
     * @return mixed
     */
    public function actionIndex($id = NULL)
    {
        $searchModel = new AfastamentosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Candidato model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {

        $model = $this->findModel($id);
		//$model->datasaida = date("d-M-Y", strtotime($model->datasaida));
        //$model->dataretorno = date("d-M-Y", strtotime($model->dataretorno));

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Afastamentos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Afastamentos();

        $model->idusuario = Yii::$app->user->identity->id;
        $usuario = User::findIdentity($model->idusuario);
        $model->nomeusuario= $usuario->nome;
        $model->emailusuario= $usuario->email;

        if ($model->load(Yii::$app->request->post())){
            if($model->save()){
                try{
                    $this->enviarSolicitacaoAfastamento($model);
                    $this->mensagens('success', 'Salvo com sucesso', 'Os dados de sua solicitação de afastamento foram enviados ao Diretor do IComp com sucesso.');
                    return $this->redirect(['view', 'id' => $model->id]);
                }catch(Exception $e){
                    $this->mensagens('danger', 'Erro ao Enviar a Solicitação', 'Contate o Administrador do Sistema');
                }
            }
             else
                    $this->mensagens('danger', 'Erro ao salvar a solicitação', 'Ocorreu um erro ao salvar as informações da solicitação de afastamento.
                            Verifique os campos e tente novamente');
        }
        return $this->render('create', ['model' => $model,]);

    }

    /**
     * Deletes an existing Afastamentos model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    function actionPrint($id) {

    $model = $this->findModel($id);
       $idUsuario = Yii::$app->user->identity->id;


       $pdf = new mPDF('utf-8','A4','','','15','15','42','30');


    if ($model->tipo == 1){
        $tipo = 'Nacional';
    }
    else{
        $tipo = 'Internacional';
    }

        $pdf->SetHTMLHeader('
                <table style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;">
                    <tr>
                        <td width="20%" align="center" style="font-family: serif;font-weight: bold; font-size: 175%;"> <img src = "img/logo-brasil.jpg" height="90px" width="90px"> </td>
                        <td width="60%" align="center" style="font-family: serif;font-weight: bold; font-size: 135%;">  PODER EXECUTIVO <br> UNIVERSIDADE FEDERAL DO AMAZONAS <br> INSTITUTO DE COMPUTAÇÃO <br> PROGRAMA DE PÓS-GRADUAÇÃO EM INFORMÁTICA </td>
                        <td width="20%" align="center" style="font-family: serif;font-weight: bold; font-size: 175%;"> <img src = "img/ufam.jpg" height="90px" width="70px"> </td>
                    </tr>
                </table>
                <hr>
        ');

        $pdf->SetHTMLFooter('

                <table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;">
                    <tr>
                        <td  colspan = "3" align="center" ><span style="font-weight: bold"> Av. Rodrigo Otávio, 6.200 - Campus Universitário Senador Arthur Virgílio Filho - CEP 69077-000 - Manaus, AM, Brasil </span></td>
                    </tr>
                    <tr>
                        <td width="33%" align="center" style="font-weight: bold; font-style: italic;">  Tel. (092) 3305-1193/2808/2809</td>
                        <td width="33%" align="center" style="font-weight: bold; font-style: italic;">  E-mail: secretaria@icomp.ufam.edu.br</td>
                        <td width="33%" align="center" style="font-weight: bold; font-style: italic;">  http://www.icomp.ufam.edu.br </td>
                    </tr>
                </table>
        ');

    $pdf->WriteHTML(' <br>
                    <table style= "margin-top:0px;" width="100%;">
                    <tr>
                        <td style="text-align:right;">
                            <b> SOLICITAÇÃO DE AFASTAMENTO TEMPORÁRIO </b>
                        </td>
                        <td align="right" width="35%">
                            <b>Hora: '.date("H:i", $model->dataenvio).'</b> <br> <b> Data: '.date("d/m/Y", strtotime($model->dataenvio)).'</b>
                        </td>
                    </tr>
                    </table>
                    <table width="100%" style="border-top: solid 1px; ">
                    <tr>
                        <td colspan="2" style= "height:35px;">
                            O(A) professor(a) <b>'.$model->nomeusuario.'</b> estará fastado de suas atividades presenciais na UFAM no período de <b>'.date("d/m/Y", strtotime($model->datasaida)).'</b> a <b>'.date("d/m/Y", strtotime($model->dataretorno)).'</b>.
                        </td>
                    </tr>
                    <tr><td colspan="2"><br></td><tr>
                    <tr>
                        <td colspan="2" style="width:100%">
                            O motivo deste afastamento: <b>'. $model->justificativa.'</b>.
                        </td>
                    </tr>
                    <tr><td colspan="2"><br></td><tr>
                    <tr>
                        <td colspan="2" style="width:100%">
                            Para reposição de suas aulas, o(a) professor(a) fez o seguinte planejamento:<br>
                            '.$model->reposicao.'.
                        </td>
                    </tr>
                    <tr><td colspan="2"><br></td><tr>
                    <tr>
                        <td colspan="2" style="width:100%">
                            Os dados desse funcionário estão descritos a seguir:
                        </td>
                    </tr>

                    <tr>
                        <td style="width:50%">
                            <b>Funcionário:</b> '.$model->nomeusuario.'
                        </td>
                        <td style="width:50%">
                            <b>E-mail:</b> '.$model->emailusuario.'
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>Local:</b> '.$model->local.'
                        </td>
                        <td>
                            <b>Tipo de Viagem: </b>'.$tipo.'
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>Data de Saída: </b>'.date("d/m/Y", strtotime($model->datasaida)).'
                        </td>

                        <td>
                            <b>Data de Retorno: </b>'.date("d/m/Y", strtotime($model->dataretorno)).'
                        </td>
                    </tr>
                    <tr><td colspan="2"><br></td><tr>
                    <tr><td colspan="2"><br></td><tr>
                    <tr><td colspan="2"><br></td><tr>
                    </table>');



        $pdf->WriteHTML('

        <table width="100%" border = "0">

                    <tr>
                        <td align="center">__________________________________________________</td>
                    </tr>
                    <tr>
                        <td align="center"><b>Profa. Dra. Tanara Lauschner</b></td>
                    </tr>
                    <tr>
                        <td align="center">Diretora do Instituto de Computação - IComp</td>
                    </tr>

                </table>
        ');


        $pdf->Output('');

        $pdfcode = $pdf->output();
    }

    public function enviarSolicitacaoAfastamento($model){

        $tipoViagem = array (1 => "Nacional",2 => "Internacional");
        // subject
        $subject  = "[IComp/UFAM] Solicitacao de Afastamento Temporário para ".$model->nomeusuario;

        $mime_boundary = "<<<--==-->>>";
	$url = "http://sistemas.icomp.ufam.edu.br/novoppgi/backend/web/index.php?r=afastamentos%2Fprint&id=".$model->id;

        $message = '';
        // message

        $message .= "O(A) professor(a) ".$model->nomeusuario." enviou uma solicitacao de afastamento temporário do IComp.\r\n\n";
        $message .= "Nome: ".$model->nomeusuario."\r\n";
        $message .= "E-mail: ".$model->emailusuario."\r\n";
        $message .= "Local: ".utf8_decode($model->local)."\r\n";
        $message .= "Tipo de Viagem: ".$tipoViagem[$model->tipo]."\r\n";
        $message .= "Data de Saída: ".date("d/m/Y", strtotime($model->datasaida))."\r\n";
        $message .= "Data de Retorno: ".date("d/m/Y", strtotime($model->dataretorno))."\r\n";
        $message .= "Justificativa: ".utf8_decode($model->justificativa)."\r\n";
        $message .= "Data e Hora do envio: ".date("d/m/Y H:i:s", $model->dataenvio)."\r\n";
	$message .= "\n\nPara baixar o documento, logue primeiramente no sistema e ent�o clique no link a seguir: ".$url."\r\n";

        $chefe = "tanara@icomp.ufam.edu.br";
        $secretaria = "secretaria@icomp.ufam.edu.br";

        $email[] = $chefe;
        $email[] = $secretaria;


        $message .= $mime_boundary."\r\n";

        try{
               Yii::$app->mailer->compose()
                ->setFrom("secretaria@icomp.ufam.edu.br")
                ->setTo($email)
                ->setSubject($subject)
                ->setTextBody($message)
                ->send();
        }catch(Exception $e){
                $this->mensagens('warning', 'Erro ao enviar Email(s)', 'Ocorreu um Erro ao Enviar as Solicitações de Cartas de Recomendação.
                    Tente novamente ou contate o adminstrador do sistema');
                return false;
        }
        return true;
    }


    /**
     * Finds the Afastamentos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Afastamentos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Afastamentos::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('A Página solicitada não foi encontrada');
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
