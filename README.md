<h1 align="center">
    Shopify Laravel APP
</h1>

<h4 align="center">
    Uma api feita com Laravel 8
</h4>

---
[**Acesse a documentação via POSTMAN**](https://documenter.getpostman.com/view/15603180/TzRPkA7z)

---


# Índice
* [Dependências](#dependências)
* [Rotas](#rotas)
    * [Rotas Públicas](#rotas-públicas)
        * [Cadastro](#apisignup)
        * [Confirmação de Email](#apiemail-confirmation)
        * [Reenvio do Código](#apiresend-code)
        * [Login](#apilogin)
    * [Rotas Privadas](#rotas-privadas)
        * [Home](#api)
        * [Lista de Desejos do Usuário](#apiwishlist)
        * [Adicionar Novo Produto à Lista](#apiwishlistcreate)
        * [Remover Produto da Lista](#apiwishlistdeleteproduct_id)

<br>

---

# Dependências

<br>

Dependencies | Version
--- | --- |
Composer  | latest | 
Laravel  | latest |
MySQL   |  >= 5.7

Para utilizar a API siga as instruções abaixo.

No terminal execute os seguintes comandos:

```apacheconf
    composer install
```

```apacheconf
    php artisan key:generate
```

Depois, crie uma Base de Dados no MySQL.

Após criada o BD, acesse o arquivo ```config/database.php``` e configure de acordo com sua máquina.

Posteriormente, insira as migrations no banco de dados, adicionando o comando abaixo em seu terminal

```apacheconf
    php artisan migrate:fresh --seed
```

Execute o servidor laravel localmente

```apacheconf
    php artisan serve
```
---
<br>

#  Rotas #

##  Rotas Públicas #

<br>

### api/signup #

Rota para criar um novo usuário. Envia um email com o codigo de confirmação de conta para o usuário.

#### Exemplo de entrada #

```javascript
{
    "name": "Ezequiel Oliveira",
    "email": "ezequiel@gmail.com",
    "password": "12345678",
    "password_confirmation": "12345678"
}
```

#### Exemplo de saida #

```javascript
{
    "status": "success"
    "message": "email enviado"
}
```

### api/email-confirmation

Rota para confirmação do email.

#### Exemplo de entrada #

```javascript
{
    "email": "ezequiel@gmail.com",
    "code": "A7SJJDS8"
}
```

#### Exemplo de saida #

```javascript
{
    "status": "success"
}
```

### api/resend-code

Rota para receber o codigo novamente.

#### Exemplo de entrada #

```javascript
{
   "email": "ezequiel@gmail.com"
}
```

#### Exemplo de saida #

```javascript
{
    "status": "success"
}
```

### api/login

Rota para fazer a autenticação do usuário, utilizando sanctum, que retorna um bearer token.

#### Exemplo de entrada #

```javascript
{
    "email": "ezequiel@gmail.com"
    "password": "12345678"
}
```

#### Exemplo de saida #

```javascript
{
   "user": {
        "id": 1,
        "name": "Ezequiel El Mago",
        "email": "ezequieleso10@gmail.com",
        "email_verified_at": "2021-05-06T13:07:54.000000Z",
        "created_at": "2021-05-06T13:01:29.000000Z",
        "updated_at": "2021-05-06T13:07:54.000000Z"
   },
   "token": "1|JKEAU6dALeNDFEr25fdaDVUEjp77Ijk542E9xMSY"
}
```
---
<br>

## Rotas Privadas

Essas rotas podem ser acessadas utilizando o token (do tipo bearer) que é retornado no ato do login.

### api/

Retorna todos os items já pré cadastrados no banco de dados.

#### Exemplo de saida #

```javascript
{
    {
        "id": "4543367512203",
        "name": "Boné preto"
    },
    {
        "id": "4538642956427",
        "name": "Camiseta Send4Lovers"
    }
}
```

### api/wishlist

retorna todos os itens que foram adicionados à lista de desejos do usuário.

#### Exemplo de saída #

```javascript
{
    {
        "id": 20,
        "costumer_id": "1",
        "product_id": "4543367512203",
        "created_at": "2021-05-06T16:31:07.000000Z",
        "updated_at": "2021-05-06T16:31:07.000000Z"
    }
}
```

### api/wishlist/create

Rota para adicionar um novo produto à lista de desejos

#### Exemplo de entrada #

```javascript
{
	"product_id": "287215271721",
}
```

#### Exemplo de saida #

```javascript
{
     "status": "success"
}
```

### api/wishlist/delete/{product_id}

Rota para remover um produto da lista de desejos do usuário.

#### Exemplo de saida #

```javascript
{
    "message": "produto removido da lista de desejos"
}
```


