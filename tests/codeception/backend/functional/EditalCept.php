<?php

use tests\codeception\backend\FunctionalTester;
use tests\codeception\common\_pages\LoginPage;
use tests\codeception\backend\_pages\EditalPage;

/* @var $scenario Codeception\Scenario */

$I = new FunctionalTester($scenario);
$I->wantTo('ensure login page works');

$editalPage = EditalPage::openBy($I);

$I->amGoingTo('Criação do Edital Inválido');
$I->amOnPage('index.php?r=site/login');
$editalPage->logar();
$I->seeInCurrentUrl('/');
$I->amOnPage('index.php?r=edital/create');
$editalPage->criarEdital('', '', '', '', '');

$I->expectTo('see validations errors');

$I->see('"Número" não pode ficar em branco.', '.help-block');
$I->see('"Vagas Mestrado" não pode ficar em branco.', '.help-block');
$I->see('"Vagas Doutorado" não pode ficar em branco.', '.help-block');
$I->see('"Data Ínicio" não pode ficar em branco.', '.help-block');
$I->see('"Data Final" não pode ficar em branco.', '.help-block');

$I->dontSeeRecord('app\models\Edital', array('numero' => '042-2017'));

$I->amGoingTo('Criação do Edital Válido');
$I->amOnPage('index.php?r=edital/create');
$editalPage->criarEdital('042-2015', '20-04-2016', '25-02-2016', '25', '20');

$I->expectTo('Correta Validação');

$I->dontSee('"Número" não pode ficar em branco.', '.help-block');
//$I->see('“Edital (PDF)” não pode ficar em branco.', '.help-block');
$I->dontSee('"Vagas Mestrado" não pode ficar em branco.', '.help-block');
$I->dontSee('"Vagas Doutorado" não pode ficar em branco.', '.help-block');
$I->dontSee('"Data Ínicio" não pode ficar em branco.', '.help-block');
$I->dontSee('"Data Final" não pode ficar em branco.', '.help-block');

$I->seeRecord('app\models\Edital', array('numero' => '042-2015'));


