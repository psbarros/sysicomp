# Sistema de Gerenciamento do IComp/UFAM

Código do sistema de gerenciamento da graduação e pós-graduação do Instituto de Computação da UFAM. Professores responsáveis pelo código: David Fernandes de Oliveira e Arilo Cláudio Dias-Neto.

## Como contribuir

Para contribuir com este trabalho, crie um fork deste projeto em sua conta no Github e submeta suas contribuições através de Pull Requests. Esse fork deve ser feito a partir do repositório original, que pode ser acessado através do endereço abaixo:

```
https://github.com/dbfernandes/sysicomp
```

## Como instalar este sistema

O primeiro passo para instalação é fazer um fork do repositório original do sistema em sua conta do Github. Para fazer isso, acesse endereço (https://github.com/dbfernandes/sysicomp) e click o botão fork desse repositório (vide imagem abaixo).

![Fork no Github](http://coyote.icomp.ufam.edu.br/sysicomp/fork.png)

Após o fork, você pode clonar seu novo repositório através do comando git clone:

```
$ git clone https://github.com/<seu usuário no github>/sysicomp
```

Feito o clone, basta seguir as orientações de instalação de qualquer sistema desenvolvido através do Yii 2. O primeiro passo é instalar as dependências do sistema através do composer:

```
$ php composer.phar install
```

Depois disso, inicialize seu sistema através do comando abaixo. Você será será questionado se deseja criar um ambiente de desenvolvimento ou produção. Caso você pretenda fazer edições no código do repositório, ou contribuir com este projeto, opte pelo ambiente de desenvolvimento.

```
$ php init
```

Uma vez inicializado seu sistema, acesse turma de Prática de Banco de Dados no CodeBench (2017/2), clique na aba de Materiais Didáticos, e faça o download do dump do banco de dados do deste sistema. Esse dump foi gerado a partir do sistema em produção, e foi colocado no Codebench porque possui dados sigilosos.
