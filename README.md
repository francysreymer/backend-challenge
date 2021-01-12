## 📝 Índice

- [Sobre](#about)
- [Iniciando](#getting_started)
- [Uso](#usage)
- [Tecnologias](#built_using)
- [Autores](#authors)

## 🧐 Sobre 

Software online de cadastro de contratos e imóveis e leitura via API e via browser.
O software e desenvolvido em Laravel + Vuejs.

## 🏁 Iniciando

Essas instruções fornecerão uma cópia do projeto instalado e funcionando em sua máquina local para fins de desenvolvimento e teste.

### Pré-requisitos

O que você precisa para instalar o software:

```
Versão PHP igual ou maior que 7.0 
```
```
Framework Laravel igual ou maior que 7.0 
```
```
Banco de Dados Mysql igual ou maior que 5.7.28
```

### Instalando

Clone o git deste projeto em seu computador:

```
git clone https://francysreymer@bitbucket.org/francysreymer/contratos-de-imoveis.git
```

Crie uma base de dados no seu banco Mysql local e altere o arquivo .env na raiz do
projeto laravel com as informações de acesso ao banco.
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=NomeDoMeuBanco
DB_USERNAME=username
DB_PASSWORD=password
```

Em seguida, execute os comandos na raiz do projeto para limpar a cache:
```
php artisan config:cache
php artisan config:clear
php artisan cache:clear

```

Agora, execute o comando na raiz do projeto para criar o banco de dados:
```
php artisan migrate
```

Digite o comando na raiz do projeto para executar o servidor:

```
php artisan serve
```


## 🎈 Uso

- Para acessar o software web basta copiar e colar a url que aparecer na linha
de comando, deve ser algo como: http://127.0.0.1:8000

## ⛏️ Tecnologias

- [MySQL](https://www.mysql.com/) - Database
- [Laravel](https://laravel.com/) - Server Framework
- [VueJs](https://vuejs.org/) - Web Framework

## ✍️ Autores

- Francys Reymer