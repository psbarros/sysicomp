<?php

namespace backend\controllers;

use Yii;
use yii\base\NotSupportedException;
use app\models\ReservaSala;
use app\models\ReservaSalaSearch;
use app\models\SalaSearch;
use app\models\Sala;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Exception;
use kartik\mpdf\Pdf;

/**
 * ReservaSalaController implements the CRUD actions for ReservaSala model.
 */
class ReservaSalaController extends Controller
{

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
                            return Yii::$app->user->identity->checarAcesso('coordenador') || Yii::$app->user->identity->checarAcesso('secretaria') ||
                           Yii::$app->user->identity->checarAcesso('professor');
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

    public function actionIndex()
    {
        $searchModel = new SalaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCalendario(){
        setlocale(LC_ALL, 'pt-BR');
        $reservasCalendario = array();
        $idSala = filter_input(INPUT_GET, 'idSala');

        $modelSalas = Sala::find()->all();
        if($idSala)
            $modelSala = Sala::findOne(['id' => $idSala]);
        else
            $modelSala = $modelSalas[0];

        $reservas = ReservaSala::findAll(['sala' => $idSala]);
        foreach ($reservas as $reserva) {
            $reservaItem = new \yii2fullcalendar\models\Event();
            $reservaItem->id = $reserva->id;
            $reservaItem->title = $reserva->atividade;
            $reservaItem->start = $reserva->dataInicio.'T'.$reserva->horaInicio;
            $reservaItem->end = $reserva->dataTermino.'T'.$reserva->horaTermino;
            $reservasCalendario[] = $reservaItem;
        }

        return $this->render('calendario',[
            'modelSala' => $modelSala,
            'reservasCalendario' => $reservasCalendario,
            'modelSalas' => $modelSalas,
        ]);
    }

    public function actionImprimir($idSala, $inicio, $termino, $datestr){

        setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');

        $sala = Sala::findOne($idSala);

        $reservas = ReservaSala::find()
            ->where(['sala' => $idSala])
            ->andWhere('dataInicio >= "' . $inicio . ' 00:00:00"')
            ->andWhere('dataTermino < "' . $termino . ' 23:59:59"')
            ->all();

        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('imprimir',[
            'sala' => $sala,
            'inicio' => $inicio,
            'termino' => $termino,
            'reservas' => $reservas,
            'datestr' => $datestr,
            'ver_matriz' => false,
        ]);

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_LANDSCAPE,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            'marginTop' => '4',
            'marginBottom' => '4',
            'marginRight' => '10',
            'marginLeft' => '10',
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            // any css to be embedded if required
            'cssInline' => 'td {padding: 0px} .td-content {border: 2px inset black;} .td-content div {color: blue}',
             // set mPDF properties on the fly
            'options' => ['title' => 'Instituto de Computação - ' . $sala->nome],
             // call mPDF methods on the fly
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();

    }

    public function actionImprimir2($idSala, $inicio, $termino){

        $sala = Sala::findOne($idSala);

        $reservas = ReservaSala::find()
            ->where(['sala' => $idSala])
            ->andWhere('dataInicio >= "' . $inicio . ' 00:00:00"')
            ->andWhere('dataTermino < "' . $termino . ' 23:59:59"')
            ->all();

        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('imprimir',[
            'sala' => $sala,
            'inicio' => $inicio,
            'termino' => $termino,
            'reservas' => $reservas,
            'ver_matriz' => false,
        ]);
        return $content;
    }


    public function actionListagemreservas(){

        $searchModel = new ReservaSalaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('listagemReservas', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreateemlote()
    {
        $arraySalas = ArrayHelper::map(Sala::find()->orderBy('nome')->all(), 'id', 'nome');


        $model = new ReservaSala();

        if ($model->load(Yii::$app->request->post())) {


            $datetime1 = new \DateTime($model->dataInicio);
            $datetime2 = new \DateTime($model->dataTermino);
            $interval = $datetime1->diff($datetime2);
            $diferencaDias =  abs($interval->format('%a'));
            $diferencaDias++;

            $auxDataInicio = $model->dataInicio;


            for($i=0; $i<$diferencaDias; $i++){

                $dataDoLoop = date('d-m-Y',date(strtotime("+".$i." days", strtotime($auxDataInicio))));

                // 0 é domingo, 1 é segunda, 2 é terça, 3 é quarta, 4 é quinta, 5 é sexta, 6 é sábado
                $diaDaSemana = date('w', strtotime($dataDoLoop));

                if(in_array($diaDaSemana, $model->diasSemana)) {

                    $model->id = null;
                    $model->isNewRecord = true;
                    $model->idSolicitante = Yii::$app->user->id;
                    $model->dataInicio = $dataDoLoop;
                    $model->dataTermino = $dataDoLoop;

                    $reservas = ReservaSala::find()->where(['dataInicio' => date('Y-m-d', strtotime($model->dataInicio))])->andWhere(['sala' => $model->sala])->all();

                    if(count($reservas) > 0){
                        foreach ($reservas as $value) {
                            if(!(($model->horaTermino < $value->horaInicio && $model->horaInicio < $value->horaInicio) ||
                                ($model->horaInicio > $value->horaTermino && $model->horaTermino > $value->horaTermino))){
                                $this->enviarNotificaoDesmarqueReserva($model, $value);
                                $value->delete();
                            }
                        }
                    }

                    $model->save();
                }

            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('createemlote', [
                'model' => $model,
                'arraySalas' => $arraySalas,
            ]);
        }

    }


    public function actionCreate($sala, $dataInicio, $horaInicio)
    {
        $model = new ReservaSala();
        $model->sala = $sala;
        $model->dataInicio = $dataInicio;
        $model->dataTermino = $model->dataInicio;
        $model->horaInicio = $horaInicio;

        if($model->salaDesc->reservasAtivas > 4 && !Yii::$app->user->identity->secretaria){
            $this->mensagens('warning', 'Limite de Reservas', 'Você alcançou o limite de 5 reservas ativas.');
            return $this->redirect(['calendario', 'idSala' => $model->sala]);
        }else if($model->dataInicio < date('Y-m-d')){
            $this->mensagens('warning', 'Data Inválida', 'A data para reserva deve ser igual ou superior que a data de hoje.');
            return $this->redirect(['calendario', 'idSala' => $model->sala]);
        }else if(!$model->horarioOk()){
            $this->mensagens('danger', 'Horário Inválido', 'Não foi possível reservar esta sala no horário escolhido, pois ela já possui uma reserva. Tente novamente em outro horário!');
            return $this->redirect(['calendario', 'idSala' => $model->sala]);
        }

        $model->idSolicitante = Yii::$app->user->identity->id;
        $model->dataReserva = date('Y-m-d H:i:s');

        if ($model->load(Yii::$app->request->post())) {
            if(!$model->horarioOk()){
                $this->mensagens('danger', 'Horário Inválido', 'Não foi possível reservar esta sala no horário escolhido, pois ela já possui uma reserva. Tente novamente em outro horário!');
                return $this->redirect(['calendario', 'idSala' => $model->sala]);
            }
            if($model->save()){
                $this->mensagens('success', 'Reserva de Sala', 'A \''.$model->atividade.'\' foi reservada com sucesso.');
                return $this->redirect(['calendario', 'idSala' => $model->sala]);
            }else{
                $this->mensagens('danger', 'Erro ao Reservar Sala', 'Ocorreu um erro ao reservar a sala. Verifique os campos e tente novamente.');
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        } else {
            if(filter_input(INPUT_GET, 'requ') != 'AJAX')
                return $this->redirect(['index']);
            else
                return $this->renderAjax('create', [
                    'model' => $model,
                ]);
        }
    }


    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $this->validaUpdateDelete($model);

        $model->dataInicio = date('d-m-Y', strtotime($model->dataInicio));
        $model->dataTermino = date('d-m-Y', strtotime($model->dataTermino));

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if($model->save()){
                $this->mensagens('success', 'Reserva de Sala', 'A reserva \''.$model->atividade.'\' foi Alterada com sucesso.');
                return $this->redirect(['view', 'id' => $model->id]);
            }else{
                $this->mensagens('danger', 'Erro ao Reservar Sala', 'Ocorreu um erro ao alterar a reserva de sala. Verifique os campos e tente novamente.');
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $this->validaUpdateDelete($model);

        if($model->delete()){
            $this->mensagens('success', 'Reservar Sala', 'A reserva de sala foi removida com sucessso.');
        }else{
            $this->mensagens('danger', 'Erro ao remover Reserva Sala', 'Ocorreu um erro ao remover a reserva de sala.');
        }

        return $this->redirect(['listagemreservas']);
    }

    protected function findModel($id)
    {
        if (($model = ReservaSala::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('A página solicitada não existe.');
        }
    }

    protected function validaUpdateDelete($model){
        if($model->idSolicitante != Yii::$app->user->identity->id && $model->dataInicio < date('d-m-Y')
            || ($model->dataInicio == date('d-m-Y') && $model->horaInicio < date('H:i:s')))
            throw new ForbiddenHttpException('Acesso negado.');
    }

    public function enviarNotificaoDesmarqueReserva($reservaLote, $reserva){

        $subject  = "[IComp/UFAM] Perda de Reserva de Sala";

        $mime_boundary = "<<<--==-->>>";

        // message
        $message = "";
        $message .= "Prof.".$reserva->solicitante->nome."\r\n";
        $message .= "Devido à solicitação de ".$reservaLote->tipo." pela Secretaria do IComp, a sua Reserva para a ".$reserva->atividade." no dia ".$reserva->dataInicio." no Horário das ".$reserva->horaInicio." foi cancelada por motivo de prioridade.\r\n";
        $message .= "Qualquer dúvida entre em contato com a Secretaria do IComp.\r\n";
        $message .= $mime_boundary."\r\n";

        try{
               Yii::$app->mailer->compose()
                ->setFrom("secretaria@icomp.ufam.edu.br")
                ->setTo($reserva->solicitante->email)
                ->setSubject($subject)
                ->setTextBody($message)
                ->send();
        }catch(Exception $e){
                $this->mensagens('warning', 'Erro ao enviar Email(s)', 'Ocorreu um Erro ao Enviar as Notificações de Perda de Reserva de Sala.
                    Tente novamente ou contate o adminstrador do sistema');
                return false;
        }
        return true;
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
