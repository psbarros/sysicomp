<?php

namespace backend\controllers;

use Yii;
use app\models\Candidato;
use app\models\Aluno;
use app\models\Edital;
use common\models\User;
use app\models\CandidatosSearch;
use backend\models\SignupForm;
use common\models\LinhaPesquisa;
use common\models\Recomendacoes;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use mPDF;


/**
 * CandidatosController implements the CRUD actions for Candidato model.
 */
class CandidatosController extends Controller
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
                               return Yii::$app->user->identity->checarAcesso('coordenador') || Yii::$app->user->identity->checarAcesso('secretaria');
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
     * Lists all Candidato models.
     * @return mixed
     */
    public function actionIndex($id)
    {
        $searchModel = new CandidatosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider2 = $searchModel->search2(Yii::$app->request->queryParams);
        $edital = $this->findEdital($id);
        $cartarecomendacao = $edital->cartarecomendacao;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dataProvider2' => $dataProvider2,
            'idEdital' => $id,
            'cartarecomendacao' => $cartarecomendacao,
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

        //obtendo o nome linha de pesquisa através do id da linha de pesquisa
        $linhaPesquisa = new LinhaPesquisa();
        $linhaPesquisa = $linhaPesquisa->getLinhaPesquisaNome($model->idLinhaPesquisa);
        if ($linhaPesquisa != null){
            $model->idLinhaPesquisa = $linhaPesquisa->nome;
        }
        //fim de obter nome da linha de pesquisa

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Candidato model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Candidato();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Candidato model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Candidato model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }



    public function actionDownloadscompletos($id){

        $idEdital = $id;

        $resultado = shell_exec("cd ../../frontend/web/documentos/ && zip -r ".$idEdital.".zip ".$idEdital);

        if (is_dir('../../frontend/web/documentos/'.$idEdital)){

      echo "oi";
	        header('Content-type: application/zip');
            header('Content-disposition: attachment; filename=Doc_Completos_'.$idEdital.".zip");
            readfile("../../frontend/web/documentos/".$idEdital.".zip");
            unlink("../../frontend/web/documentos/".$idEdital.".zip");

        }
        else{

        $this->mensagens('warning', 'Não há documentos', 'Nenhum candidato fez upload de sua documentação.');

        return $this->redirect(['edital/view','id'=>$id]);

        }

    }

	public function actionDownloads($id,$idEdital)
    {
        //$model = $this->findModel($id);

        $modelCandidato = new Candidato();
        $candidato = $modelCandidato->download($id,$idEdital);


        $salt1 = "programadeposgraduacaoufamicompPPGI";
        $salt2 = $id * 777;
        $idCriptografado = md5($salt1+$id+$salt2);


        $diretorio = '../../frontend/web/documentos/'.$idEdital.'/'.$idCriptografado;


		$zipFile = 'candidato_'.$candidato->id.'.zip';
        $zipArchive = new \ZipArchive();

            if (!$zipArchive->open("uploads/".$zipFile, \ZIPARCHIVE::CREATE | \ZIPARCHIVE::OVERWRITE))
                die("Failed to create archive\n");

				$options = array('add_path' => 'candidato/', 'remove_path' => $diretorio);

                $zipArchive->addGlob($diretorio.'/*', GLOB_BRACE, $options);

            if (!$zipArchive->status == \ZIPARCHIVE::ER_OK)
                echo "Failed to write files to zip\n";

            $zipArchive->close();
            header('Content-type: application/zip');
            header('Content-disposition: attachment; filename='.$zipFile);
            readfile("uploads/".$zipFile);
            unlink("uploads/".$zipFile);

    }

    public function actionAprovar($id,$idEdital){


        $model = $this->findModel($id);

        $cartas_respondidas = new Recomendacoes();
        $cartas_respondidas = $cartas_respondidas->getCartasRespondidas($id);

        $edital_carta_recomendacao = new Edital();
        $edital_carta_recomendacao = $edital_carta_recomendacao->getCartaRecomendacao($idEdital);

        // A Helen pediu para permitir a aprovação mesmo sem cartas.
        //if($cartas_respondidas <2 && $edital_carta_recomendacao == 1){
        //    $this->mensagens('danger', 'Cartas de Recomendação', 'Não foi possível avaliar o candidato, pois faltam cartas a serem respondidas.');
        //    return $this->redirect(['candidatos/index','id'=>$idEdital]);
        //}

        if($model->resultado === 1 || $model->resultado === 2 ){
            $this->mensagens('danger', 'Candidato Reprovado', 'Este Candidato já foi Avaliado');
            return $this->redirect(['candidatos/index','id'=>$idEdital]);
        }


//        $model_usuario = new User();

        $model_candidato = $this->findModel($id);


        if($model_candidato != null){
            $usuario_ja_existe = User::find()->select("id")->where("username = '".$model_candidato->cpf."'")->one();
        }


/*        if($usuario_ja_existe != null){
            $model_usuario_existente = User::findOne($usuario_ja_existe->id);
            $model_usuario_existente->aluno = 1;
            $model_usuario_existente->status = 10;
            $salvou = $model_usuario_existente->save();

            $id_usuario = $model_usuario_existente->id;

        }
        else{

            $model_usuario->nome = $model_candidato->nome;
            $model_usuario->username = $model_candidato->cpf;
            $model_usuario->password = $model_candidato->senha;
            $model_usuario->email = $model_candidato->email;
            $model_usuario->administrador =  0;
            $model_usuario->coordenador =  0;
            $model_usuario->secretaria =  0;
            $model_usuario->professor = 0;
            $model_usuario->aluno = 1;
            $model_usuario->auth_key = Yii::$app->security->generateRandomString();

            $salvou = $model_usuario->save();

            $id_usuario = $model_usuario->id;

        }


        if($salvou == true){    */
            // Eliminei a criação do usuário ALUNO
			//return $this->actionAprovar1($id,$idEdital,$id_usuario);
			return $this->actionAprovar1($id,$idEdital);
        /*}
        else{
            $this->mensagens('warning', 'Erro', 'Erro ao Aprovar Candidato. Entre com contato com o administrador do sistema.');

        }

        return $this->redirect(['candidatos/index','id'=>$idEdital]);
*/
    }




    //public function actionAprovar1($id,$idEdital,$id_usuario){
	public function actionAprovar1($id,$idEdital){

        $model_candidato = $this->findModel($id);
        $model_aluno = new Aluno();

        $model_aluno->senha  = $model_candidato->senha;
        $model_aluno->nome  = $model_candidato->nome;
        $model_aluno->endereco  = $model_candidato->endereco;
        $model_aluno->bairro  = $model_candidato->bairro;
        $model_aluno->cidade  = $model_candidato->cidade;
        $model_aluno->uf  = $model_candidato->uf;
        $model_aluno->cep  = $model_candidato->cep;
        $model_aluno->email  = $model_candidato->email;
        $model_aluno->datanascimento  = $model_candidato->datanascimento;
        $model_aluno->cpf  = $model_candidato->cpf;
        $model_aluno->sexo  = $model_candidato->sexo;
        $model_aluno->telresidencial  = $model_candidato->telresidencial;
        $model_aluno->telcelular  = $model_candidato->telcelular;
        $model_aluno->regime  = $model_candidato->regime;
        $model_aluno->cursograd  = $model_candidato->cursograd;
        $model_aluno->instituicaograd  = $model_candidato->instituicaograd;
        $model_aluno->egressograd  = $model_candidato->egressograd;
        $model_aluno->status  = $model_candidato->status;
        $model_aluno->bolsista  = $model_candidato->solicitabolsa;

        $model_candidato->resultado = 2;

        //mudança de atributos
        $model_aluno->area  = $model_candidato->idLinhaPesquisa;
        $model_aluno->curso  = $model_candidato->cursodesejado;

        if ($model_aluno->load(Yii::$app->request->post()) && $model_aluno->save()) {
                $model_candidato->save();

            return $this->redirect(['index', 'id' => $idEdital]);
        } else {

        $linhasPesquisas = ArrayHelper::map(LinhaPesquisa::find()->orderBy('nome')->all(), 'id', 'nome');
        $orientadores = ArrayHelper::map(User::find()->where(['professor' => '1'])->orderBy('nome')->all(), 'id', 'nome');

        return $this->render('/aluno/create', [
            'model' => $model_aluno,
            'linhasPesquisas' => $linhasPesquisas,
            'orientadores' => $orientadores,
        ]);
        }

    }

    public function actionReprovar($id,$idEdital)
    {
        $model = $this->findModel($id);

        $cartas_respondidas = new Recomendacoes();
        $cartas_respondidas = $cartas_respondidas->getCartasRespondidas($id);

        if($model->resultado === 1 || $model->resultado === 2){
            $this->mensagens('danger', 'Candidato Avaliado', 'Este Candidato já foi Avaliado');
            return $this->redirect(['candidatos/index','id'=>$idEdital]);
        }

            $model->resultado = 1;

            if($model->save(false)){
                $this->mensagens('success', 'Candidato Reprovado', 'Candidato Reprovado com sucesso.');
            }
            else{
                $this->mensagens('warning', 'Erro', 'Erro ao Aprovar Candidato. Entre com contato com o administrador do sistema.');
            }

        return $this->redirect(['candidatos/index','id'=>$idEdital]);
    }


    public function actionPdf($documento){

        $id = Yii::$app->request->get('id');
        $model = $this->findModel($id);

        $mudarDiretorioParaFrontEnd = "../../frontend/web/";

        $localArquivo = $mudarDiretorioParaFrontEnd.$model->getDiretorio().$documento;

       if(!file_exists($localArquivo))
            throw new NotFoundHttpException('A Página solicitada não existe.');

        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename="'.$documento.'"');
        header('Content-Type: application/pdf');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($mudarDiretorioParaFrontEnd.$model->getDiretorio().$documento));
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Expires: 0');

        readfile($localArquivo);

    }

    public function actionPdfcartas($id){

            $pdf = new mPDF('utf-8','A4','','','15','15','42','30');

            $pdf->SetHTMLHeader('
                <table style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;">
                    <tr>
                        <td width="20%" align="center" style="font-family: Helvetica;font-weight: bold; font-size: 175%;"> <img src = "../../frontend/web/img/logo-brasil.jpg" height="90px" width="90px"> </td>
                        <td width="60%" align="center" style="font-family: Helvetica;font-weight: bold; font-size: 135%;">  PODER EXECUTIVO <br> UNIVERSIDADE FEDERAL DO AMAZONAS <br> INSTITUTO DE COMPUTAÇÃO <br> PROGRAMA DE PÓS-GRADUAÇÃO EM INFORMÁTICA </td>
                        <td width="20%" align="center" style="font-family: Helvetica;font-weight: bold; font-size: 175%;"> <img src = "../../frontend/web/img/ufam.jpg" height="90px" width="70px"> </td>
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

            $model_cartas = new Recomendacoes();
            $candidato = $this->findModel($id);
            $recomendacao = $model_cartas->getCartas($id);

            $i=0;

    while($i<count($recomendacao)){


            $pontos = array (1 => "Fraco",2 => "Regular",3 => "Bom",4 => "Muito bom",5 => "Excelente",6 => "Sem condições para afirmar");

            $classificacao = array (1 => "entre os 50% mais aptos",2 => "entre os 30% mais aptos",3 => "entre os 10% mais aptos",4 => "entre os 5% mais aptos");

            $orientador = array (0 => "",1 => "Orientador; ");
            $professor = array (0 => "",1 => "Professor em Disciplina; ");
            $empregador = array (0 => "",1 => "Empregador; ");
            $coordenador = array (0 => "",1 => "Coordenador; ");
            $colegaCurso = array (0 => "",1 => "Colega em Curso Superior; ");
            $colegaTrabalho = array (0 => "",1 => "Colega de Profissão; ");
            $outrosContatos = array (0 => "",1 => "Outras Funções; ");
            $conheceGraduacao = array (0 => "",1 => "Curso de Graduação; ");
            $conhecePos = array (0 => "",1 => "Curso de Pós-Graduação; ");
            $conheceEmpresa = array (0 => "",1 => "Empresa; ");
            $conheceOutros = array (0 => "",1 => "Outros; ");


            $atributos = array(
                                       array('Atributos do Candidato'=>'Domínio em sua área de conhecimento científico','Nível'=>$pontos[$recomendacao[$i]->dominio])
                                       ,array('Atributos do Candidato'=>'Facilidade de aprendizado capacidade intelectual','Nível'=>$pontos[$recomendacao[$i]->aprendizado])
                                       ,array('Atributos do Candidato'=>'Assiduidade, perseverança','Nível'=>$pontos[$recomendacao[$i]->assiduidade])
                                       ,array('Atributos do Candidato'=>'Relacionamento com colegas e superiores','Nível'=>$pontos[$recomendacao[$i]->relacionamento])
                                       ,array('Atributos do Candidato'=>'Iniciativa, desembaraço, originalidade e liderança','Nível'=>$pontos[$recomendacao[$i]->iniciativa])
                                       ,array('Atributos do Candidato'=>'Capacidade de expressão escrita','Nível'=>$pontos[$recomendacao[$i]->expressao])
                                       ,array('Atributos do Candidato'=>'Conhecimento em Inglês','Nível'=>$pontos[$recomendacao[$i]->ingles])
            );


            $pdf->writeHTML('

                <div style ="text-align:center"> <b>CARTA DE RECOMENDAÇÃO</b> </div>
                <hr>
                <div style ="text-align:center;margin-bottom:10px"> <b>DADOS DO CANDIDATO</b> </div>
                <div style ="text-align:left"> <b>Nome do Candidato:</b> '.$candidato->nome.' </div>
                <div style ="text-align:left"> <b>Graduado em: </b> '.$candidato->cursograd.' - '.$candidato->instituicaograd.' </div>
                </p>

                <hr>
                <div style ="text-align:center;"> <b>AVALIADOR DO CANDIDATO</b> </div>

                <div style ="text-align:left"> <b>Nome: </b>'.$recomendacao[$i]->nome.' </div>
                <div style ="text-align:left"> <b>Titulação: </b>'.$recomendacao[$i]->titulacao.' </div>
                <div style ="text-align:left"> <b>Instituição: </b>'.$recomendacao[$i]->instituicaoTitulacao.' </div>
                <div style ="text-align:left"> <b>Ano da Titulação: </b>'.$recomendacao[$i]->anoTitulacao.' </div>
                <div style ="text-align:left"> <b>Instituição/Empresa onde atua: </b>'.$recomendacao[$i]->instituicaoAtual.' </div>
                <div style ="text-align:left"> <b>Cargo: </b>'.($recomendacao[$i]->cargo).' </div>

                <hr>
                <div style ="text-align:center;margin-bottom:10px"> <b>AVALIAÇÃO DO CANDIDATO</b> </div>

                <div style ="text-align:left"> <b> Conheço o candidato desde: </b>'.$recomendacao[$i]->anoContato. ' em ' .$conheceGraduacao[$recomendacao[$i]->conheceGraduacao]."".$conhecePos[$recomendacao[$i]->conhecePos]."".$conheceEmpresa[$recomendacao[$i]->conheceEmpresa]."".$conheceOutros[$recomendacao[$i]->conheceOutros].'
                </div>

                <div style ="text-align:left"> <b> Com relação ao candidato, fui seu(sua): </b>'.$orientador[$recomendacao[$i]->orientador]."".$professor[$recomendacao[$i]->professor]."".$empregador[$recomendacao[$i]->empregador]."".$coordenador[$recomendacao[$i]->coordenador]."".$colegaCurso[$recomendacao[$i]->colegaCurso]."".$colegaTrabalho[$recomendacao[$i]->colegaTrabalho]."".$outrosContatos[$recomendacao[$i]->outrosContatos].'
                </div>

                <p> <b> Como classifica o candidato em relação aos atributos abaixo: </b> </p>

                <table style="border: solid; width: 100%; text-align:center">
                    <tr style="background-color:#848484">
                        <th>
                                Área
                        </th>
                        <th>
                                Nível
                        </th>
                    </tr>
                    <tr style="background-color:#F2F5A9">
                        <td style="text-align:left"> Domínio em sua área de conhecimento científico </td>
                        <td> '.$pontos[$recomendacao[$i]->dominio].'</td>
                    <tr style="background-color:#D8D8D8">
                        <td style="text-align:left"> Facilidade de aprendizado capacidade intelectual </td>
                        <td>'.$pontos[$recomendacao[$i]->aprendizado].'</td>
                    </tr>
                    <tr style="background-color:#F2F5A9">
                        <td style="text-align:left"> Assiduidade, perseverança </td>
                        <td> '.$pontos[$recomendacao[$i]->assiduidade].'</td>
                    </tr>
                    <tr style="background-color:#D8D8D8">
                        <td style="text-align:left"> Relacionamento com colegas e superiores </td>
                        <td> '.$pontos[$recomendacao[$i]->relacionamento].'</td>
                    </td>
                    <tr style="background-color:#F2F5A9">
                        <td style="text-align:left"> '.'Iniciativa, desembaraço, originalidade e liderança </td>
                        <td> '.$pontos[$recomendacao[$i]->iniciativa].'</td>
                    </tr>
                    <tr style="background-color:#D8D8D8">
                        <td style="text-align:left"> '.'Capacidade de expressão escrita </td>
                        <td> '.$pontos[$recomendacao[$i]->expressao].'</td>
                    </tr>
                    <tr style="background-color:#F2F5A9">
                        <td style="text-align:left" > '.'Conhecimento em Inglês </td>
                        <td> '.$pontos[$recomendacao[$i]->ingles].'</td>
                    </tr>

                </table>

                <p> <div style=" display:inline; text-align: justify; font-weight:bold"> Comparando este candidato com outros alunos ou profissionais, com similar nível de educação e experiência, que conheceu nos últimos 2 anos, classifique a sua aptidão para realizar estudos avançados e pesquisas: '.$classificacao[$recomendacao[$i]->classificacao].'.</div> </p>

                <p> <b>Informações Adicionais:</b> </p>

                <p>'. $recomendacao[$i]->informacoes.'</p>

            ');


        $i++;

        if($i < count($recomendacao)){
            $pdf->addPage();
        }

    }

        $mudarDiretorioParaFrontEnd = "../../frontend/web/";
        $localArquivo = $mudarDiretorioParaFrontEnd.$candidato->getDiretorio();
        $pdf->output($localArquivo."Cartas.pdf","F");

    }

    public function actionReenviarcartas($id, $idEdital){
        $recomendacoesArray = Recomendacoes::findAll(['idCandidato' => $id, 'dataResposta' => '0000-00-00 00:00:00']);

        for ($i=0; $i < count($recomendacoesArray); $i++) {
            $recomendacoesArray[$i]->prazo = date("Y-m-d", strtotime('+1 days'));
            if(!$recomendacoesArray[$i]->save()){
                $this->mensagens('danger', 'Erro ao Reenviar Cartas', 'As cartas de Recomendações não poderam ser enviadas.');
                return $this->redirect(['candidatos/index','id'=>$idEdital]);
            }
        }

        $this->notificarCartasRecomendacao($recomendacoesArray, $id);

        $this->mensagens('success', 'Cartas de Recomendações Reenviadas', 'As cartas de Recomendações foram reenviadas.');
        return $this->redirect(['candidatos/index','id'=>$idEdital]);
    }

    public function notificarCartasRecomendacao($recomendacoesArray, $id){

        $model = Candidato::findOne(['id' => $id]);

        foreach ($recomendacoesArray as $recomendacoes) {
            echo "<script>console.log('$recomendacoes->nome')</script>";
            $link = "http://localhost/MyProjects/ppgi/frontend/web/index.php?r=recomendacoes/create&token=".$recomendacoes->token;
            // subject
            $subject  = "[PPGI/UFAM] Solicitacao de Carta de Recomendacao para ".$model->nome;

            $mime_boundary = "<<<--==-->>>";
            $message = '';
            // message
            $message .= "Caro(a) ".$recomendacoes->nome.", \r\n\n";
            $message .= "Você foi requisitado(a) por ".$model->nome." (email: ".$model->email.") para escrever uma carta de recomendação para o processo de seleção do Programa de Pós-Graduação em Informática (PPGI) da Universidade Federal do Amazonas (UFAM).\r\n";
            $message .= "\nPara isso, a carta deve ser preenchida eletronicamente utilizando o link: \n ".$link."\r\n";
            $message .= "O prazo para preenchimento da carta é ".$recomendacoes->prazo.".\r\n";
            $message .= "Em caso de dúvidas, por favor nos contate. Agradecemos sua colaboração.\r\n";
            $message .= "\nCoordenação do PPGI - ".date(DATE_RFC822)."\r\n";
            $message .= $mime_boundary."\r\n";

            /*Envio das cartas de Email*/
           try{
               Yii::$app->mailer->compose()
                ->setFrom("secretariappgi@icomp.ufam.edu.br")
                ->setTo($recomendacoes->email)
                ->setSubject($subject)
                ->setTextBody($message)
                ->send();
            }catch(Exception $e){
                $this->mensagens('warning', 'Erro ao enviar Email(s)', 'Ocorreu um Erro ao Enviar as Solicitações de Cartas de Recomendação.
                    Tente novamente ou contate o adminstrador do sistema');
            }
        }
    }

    /**
     * Finds the Candidato model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Candidato the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Candidato::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('A Página solicitada não foi encontrada');
        }
    }

    protected function findEdital($id){
        if(($edital = Edital::findOne($id)) !== null){
            return $edital;
        }else{
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
