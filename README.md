#Sistema de Progresssão dos professores do DCC

> Sistema web para geração de relatório de progressão dos professores do DCC-UFBA.

##Conteúdo
* Time de Desenvolvimento
* Ferramentas utilizadas
* Instalação das Dependências
* Instalação do Laravel
* Como rodar

## Time de Desenvolvimento
* "Adeilson Silva" <adeilsonsilva@dcc.ufba.com.br>
* "Luan Menezes" <luancmenezes@gmail.com>
* "Lucas" <luckas_ccs@hotmail.com>
* "Marcus Freire" <mfreire.e@gmail.com>

## Ferramentas Utilizadas
* PHP (http://php.net/)
* Composer (https://getcomposer.org/)
* Laravel (http://laravel.com/)

##Instalação das Dependências
```
$ sudo apt-get install mysql-server libapache2-mod-auth-mysql php5-mysql
$ git clone https://github.com/AdeilsonSilva/engsoft.git
$ cd engsoft
$ composer install
```

##Instalação do Composer
```
$ curl -sS https://getcomposer.org/installer | php
$ sudo mv composer.phar /usr/local/bin/composer
```
##Instalação do Laravel
```
$ composer global require "laravel/installer=~1.1"
```

## Como rodar
```
 $ php artisan serve
 ```
