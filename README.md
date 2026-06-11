# Nave Cadastros SP

## Visão geral

Pacote privado Laravel para sincronização e importação de dados do módulo **SP Produto**.

Ele é consumido por outros projetos Laravel via Composer, usando repositório VCS privado.

## Requisitos

- PHP `^8.1 || ^8.2 || ^8.3`
- Laravel `8.x` a `12.x`
- Composer 2
- Acesso ao repositório privado do pacote e de suas dependências privadas
- RabbitMQ e banco de dados configurados no projeto cliente

## Acesso a repositórios privados

No projeto cliente, adicione o repositório VCS no `composer.json`:

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/appnave/nave-cadastros-sp"
    }
  ]
}
```

Instale o pacote:

```bash
composer require appnave/nave-cadastros-sp
```

Se o pacote ou alguma dependência privada estiver em GitHub, autentique o Composer localmente:

```bash
composer config -g github-oauth.github.com <YOUR_TOKEN>
```

No GitHub Actions, use `COMPOSER_AUTH`:

```yaml
env:
  COMPOSER_AUTH: '{"github-oauth":{"github.com":"${{ secrets.GH_TOKEN }}"}}'
```

## Instalação local

No projeto cliente:

```bash
composer require appnave/nave-cadastros-sp
php artisan sp-produto:config
php artisan sp-produto:install
```

O comando `sp-produto:config` publica `config/sp-produto.php`.
O comando `sp-produto:install` publica as migrations e, se confirmado, executa migrations e seeders.

## Variáveis de ambiente

Configure, no mínimo, estas variáveis no projeto cliente:

```env
MS_SP_PRODUTO_TABLE_PREFIX=produto_
MS_SP_PRODUTO_COMPANY=BildVitta\Hub\Entities\HubCompany

PRODUTO_DB_HOST=
PRODUTO_DB_PORT=
PRODUTO_DB_DATABASE=
PRODUTO_DB_USERNAME=
PRODUTO_DB_PASSWORD=

RABBITMQ_ACTIVE=true
RABBITMQ_HOST=
RABBITMQ_PORT=
RABBITMQ_USER=
RABBITMQ_PASSWORD=
RABBITMQ_VIRTUALHOST=/
RABBITMQ_USE_SSL=true
RABBITMQ_EXCHANGE_REAL_ESTATE_DEVELOPMENTS=real_estate_developments
RABBITMQ_QUEUE_REAL_ESTATE_DEVELOPMENTS=
RABBITMQ_EVENT_REAL_ESTATE_DEVELOPMENT_UPDATED=false
```

## Comandos úteis

```bash
php artisan sp-produto:config
php artisan sp-produto:install
php artisan sp-produto:configure
php artisan dataimport:produto_real_estate_developments --select=500 --offset=0 --table=0
php artisan dataimport:produto_properties --select=500 --offset=0 --table=0
php artisan rabbitmqworker:real_estate_developments
php artisan db:seed --class=SpProdutoSeeder
```

## Convenções

- O pacote publica o provider `BildVitta\SpProduto\SpProdutoServiceProvider` via auto-discovery do Laravel.
- O alias disponível é `MessagesCrm`.
- O helper global `prefixTableName()` usa o prefixo definido em `MS_SP_PRODUTO_TABLE_PREFIX`.
- A lista de relações sincronizadas pode ser ajustada em `config/sp-produto.php` antes de rodar as migrations.
- O comando `sp-produto:configure` só executa se `RABBITMQ_ACTIVE=true`.

