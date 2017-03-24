<?php

namespace tests\codeception\backend\_pages;

use yii\codeception\BasePage;

/**
 * Represents loging page
 * @property \codeception_frontend\AcceptanceTester|\codeception_frontend\FunctionalTester|\codeception_backend\AcceptanceTester|\codeception_backend\FunctionalTester $actor
 */
class EditalPage extends BasePage
{
    public $route = 'edital/create';


    public function logar()
    {
        $this->actor->fillField('#loginform-username', '013.186.002-00');
        $this->actor->fillField('#loginform-password', '123456');
        $this->actor->click('login-button');
    }

    public function criarEdital($numero, $datainicio, $datafim, $vagas_mestrado, $vagas_doutorado)
    {
        $this->actor->fillField('input[name="Edital[numero]"]', $numero);
        $this->actor->fillField('input[name="Edital[datainicio]"]', $datainicio);
        $this->actor->fillField('input[name="Edital[datafim]"]', $datafim);
        $this->actor->fillField('input[name="Edital[vagas_mestrado]"]', $vagas_mestrado);
        $this->actor->fillField('input[name="Edital[vagas_doutorado]"]', $vagas_doutorado);
        $this->actor->fillField('input[name="Edital[cotas_mestrado]"]', '2');
        $this->actor->fillField('input[name="Edital[cotas_doutorado]"]', '3');
        //$this->actor->fillField('#form_mestrado', '0');
        $this->actor->click('criar');
    }
}
