<?php
use yii\helpers\Html;

use app\models\Edital;
use app\models\Candidato;

/* @var $this \yii\web\View */
/* @var $content string */

ini_set('max_execution_time', 5*60);


if(!Yii::$app->user->isGuest){
    $ultima_visualizacao = Yii::$app->user->identity->visualizacao_candidatos;
    $candidato = Candidato::find()->where("inicio > '".$ultima_visualizacao."'")->all();
    $count_candidatos = count($candidato);
}


?>
<?php if(!Yii::$app->user->isGuest && Yii::$app->user->identity->checarAcesso('coordenador')){ ?>
<script>

    setInterval(function(){

                                var xhttp = new XMLHttpRequest();
                                xhttp.onreadystatechange = function() {
                                    if (xhttp.readyState == 4 && xhttp.status == 200) {
                                        var class1 = document.getElementsByClassName("quantidadeCandidatos");
                                        class1[0].innerHTML = xhttp.responseText;
                                        class1[1].innerHTML = xhttp.responseText;
                                    }
                                };
                                    xhttp.open("GET", "index.php?r=edital/quantidadecandidatos", true);
                                    xhttp.send();

                                var xhttp2 = new XMLHttpRequest();
                                xhttp2.onreadystatechange = function() {
                                    if (xhttp2.readyState == 4 && xhttp2.status == 200) {
                                        document.getElementById("listaCandidatos").innerHTML = xhttp2.responseText;
                                    }
                                };
                                    xhttp2.open("GET", "index.php?r=edital/listacandidatos", true);
                                    xhttp2.send();


                                var xhttp3 = new XMLHttpRequest();
                                xhttp3.onreadystatechange = function() {
                                    if (xhttp3.readyState == 4 && xhttp3.status == 200) {
                                        document.getElementById("listaEncerrados").innerHTML = xhttp3.responseText;
                                    }
                                };
                                    xhttp3.open("GET", "index.php?r=edital/listaencerrados", true);
                                    xhttp3.send();

                                var xhttp4 = new XMLHttpRequest();
                                xhttp4.onreadystatechange = function() {
                                    if (xhttp4.readyState == 4 && xhttp4.status == 200) {
                                        var class2 = document.getElementsByClassName("quantidadeEncerrados");
                                        class2[0].innerHTML = xhttp4.responseText;
                                        class2[1].innerHTML = xhttp4.responseText;
                                    }
                                };
                                    xhttp4.open("GET", "index.php?r=edital/quantidadeencerrados", true);
                                    xhttp4.send();



                                var xhttp5 = new XMLHttpRequest();
                                xhttp5.onreadystatechange = function() {
                                    if (xhttp5.readyState == 4 && xhttp5.status == 200) {
                                        document.getElementById("listaCartasRespondidas").innerHTML = xhttp5.responseText;
                                    }
                                };
                                    xhttp5.open("GET", "index.php?r=edital/cartasrespondidas", true);
                                    xhttp5.send();


                                var xhttp6 = new XMLHttpRequest();
                                xhttp6.onreadystatechange = function() {
                                    if (xhttp6.readyState == 4 && xhttp6.status == 200) {
                                        var class3 = document.getElementsByClassName("quantidadeCartasRecebidas");
                                        class3[0].innerHTML = xhttp6.responseText;
                                        class3[1].innerHTML = xhttp6.responseText;
                                    }
                                };
                                    xhttp6.open("GET", "index.php?r=edital/quantidadecartasrecebidas", true);
                                    xhttp6.send();

                                var xhttp7 = new XMLHttpRequest();
                                xhttp7.onreadystatechange = function() {
                                    if (xhttp7.readyState == 4 && xhttp7.status == 200) {
                                        document.getElementById("listaDatas").innerHTML = xhttp7.responseText;
                                    }
                                };
                                xhttp7.open("GET", "index.php?r=cont-proj-registra-datas/listadatas", true);
                                                        xhttp7.send();
                                var xhttp8 = new XMLHttpRequest();
                                xhttp8.onreadystatechange = function() {
                                    if (xhttp8.readyState == 4 && xhttp8.status == 200) {
                                        var class4 = document.getElementsByClassName("registrarDatas");
                                        class4[0].innerHTML = xhttp8.responseText;
                                        class4[1].innerHTML = xhttp8.responseText;
                                    }
                                };
                                xhttp8.open("GET", "index.php?r=cont-proj-registra-datas/quantidadedatas", true);
                                xhttp8.send();


                            }, 1000
    );


    function zerarNotificacaoInscricoes(){

        var xhttp = new XMLHttpRequest();
            xhttp.open("GET", "index.php?r=edital/zerarnotificacaoinscricoes", true);
            xhttp.send();

    }

    function zerarNotificacaoEncerrados(){
        var xhttp = new XMLHttpRequest();
            xhttp.open("GET", "index.php?r=edital/zerarnotificacaoencerrados", true);
            xhttp.send();

    }
    function zerarNotificacaoCartas(){
        var xhttp = new XMLHttpRequest();
            xhttp.open("GET", "index.php?r=edital/zerarnotificacaocartas", true);
            xhttp.send();

    }

    function zerarNotificacaoDatas(){
        var xhttp = new XMLHttpRequest();
        xhttp.open("GET", "index.php?r=cont-proj-registra-datas/zerarnotificacaodatas", true);
        xhttp.send();

    }


</script>

<?php } ?>


<header class="main-header">

    <?= Html::a('<span class="logo-mini">APP</span><span class="logo-lg">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">
                <?php if(!Yii::$app->user->isGuest && Yii::$app->user->identity->checarAcesso('coordenador')){ ?>
                <!-- Messages: style can be found in dropdown.less-->
                <li class="dropdown messages-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-flag-o"></i>
                        <span class="label label-success"> <div class="quantidadeCandidatos"> </div> </span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header"> Número de Novas Inscrições: <div style="display: inline" class="quantidadeCandidatos"> </div> </b></li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul id="listaCandidatos" class="menu">


                            </ul>

                        </li>
                        <li class="footer"><a href="#" onclick = "zerarNotificacaoInscricoes()"> Limpar Notificações </a></li>
                    </ul>
                </li>

                <li class="dropdown messages-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell-o"></i>
                        <span class="label label-warning"> <div class=" quantidadeEncerrados "> </div> </span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header"> Número de Inscrições Finalizadas: <div style="display: inline" class=" quantidadeEncerrados "> </div> </b></li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul id="listaEncerrados" class="menu">


                            </ul>

                        </li>
                        <li class="footer"><a href="#" onclick = "zerarNotificacaoEncerrados()"> Limpar Notifições </a></li>
                    </ul>
                </li>


                <li class="dropdown messages-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-envelope-o"></i>
                        <span class="label label-danger"> <div class=" quantidadeCartasRecebidas "> </div> </span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header"> Número de Cartas Respondidas: <div style="display: inline" class=" quantidadeCartasRecebidas "> </div> </b></li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul id="listaCartasRespondidas" class="menu">


                            </ul>

                        </li>
                        <li class="footer"><a href="#" onclick = "zerarNotificacaoCartas()"> Limpar Notifições </a></li>
                    </ul>
                </li>

                    <li class="dropdown messages-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-bookmark-o"></i>
                            <span class="label label-success"> <div class="registrarDatas"> </div> </span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header"> Novos Lembretes: <div style="display: inline" class="registrarDatas"> </div> </b></li>
                            <li>
                                <!-- inner menu: contains the actual data -->
                                <ul id="listaDatas" class="menu">


                                </ul>

                            </li>
                            <li class="footer"><a href="#" onclick = "zerarNotificacaoDatas()"> Fechar caixa de lembretes </a></li>
                        </ul>
                    </li>


                <?php } ?>
                <!-- User Account: style can be found in dropdown.less -->
                <?php  if(!Yii::$app->user->isGuest){ ?>
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">

                        <img src="img/administrador.png" class="img-circle" width="25px" height="25px" alt="User Image"/>

                        <span class="hidden-xs"> <?= Yii::$app->user->identity->nome ?> </span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header" style="height: 80px">

                            <p>
                                <?= Yii::$app->user->identity->nome ?>
                                <?= "<small>Criado em ".Yii::$app->user->identity->created_at."</small>"?>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <?= Html::a(
                                    'Perfil',
                                    ['user/perfil'],
                                    ['class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                            <div class="pull-right">
                                <?= Html::a(
                                    'Sair',
                                    ['/site/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>
                <?php }else{ echo Html::a('Login', ['site/login'], ['data-method' => 'post', 'class' => 'btn btn-info btn-lg']); } ?>
            </ul>
        </div>
    </nav>
</header>
