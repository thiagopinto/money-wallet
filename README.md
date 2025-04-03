# 💰 Money Wallet API

![PHP](https://img.shields.io/badge/PHP-8.1%2B-blue)
![Laravel](https://img.shields.io/badge/Laravel-10.x-red)
![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-orange)
![Tests](https://img.shields.io/badge/tests-passing-success)

API segura para carteira digital com gerenciamento de saldos e transferências entre usuários.

**Repositório**: [github.com/thiagopinto/money-wallet](https://github.com/thiagopinto/money-wallet.git)  
**Autor**: Thiago Pinto Dias

## 🚀 Começando

### Pré-requisitos
- PHP 8.1+
- Composer
- MySQL 5.7+ (ou SQLite para testes)

### Instalação
```bash
# Clone o repositório
git clone https://github.com/thiagopinto/money-wallet.git
cd money-wallet

# Instale as dependências
composer install

# Configure o ambiente
cp .env.example .env
php artisan key:generate

# Configure o banco de dados (edite o .env)
nano .env

# Execute as migrações
php artisan migrate

# Dados de teste (opcional)
php artisan db:seed
```

## ⚙️ Configuração
Edite o arquivo `.env` com suas credenciais:

```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=money_wallet
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha

SERVICES_AUTHORIZATION_URL=https://api.exemplo.com/authorize
SERVICES_NOTIFICATION_URL=https://api.exemplo.com/notify
```

## ▶️ Executando a API
```bash
php artisan serve
```
A API estará disponível em: [http://localhost:8000](http://localhost:8000)

## 📚 Endpoints da API
### POST `/api/v1/transactions`
Realiza transferência entre carteiras

**Requisição:**
```json
{
  "amount": 150.75,
  "payer_id": 1,
  "payee_id": 2
}
```

**Resposta de Sucesso (201):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "amount": "150.75",
    "payer_id": 1,
    "payee_id": 2,
    "status": "completed",
    "created_at": "2023-08-20T15:30:00.000000Z"
  },
  "message": "Transferência realizada com sucesso"
}
```

**Resposta de Erro (400):**
```json
{
  "success": false,
  "message": "Saldo insuficiente"
}
```

## 🧪 Testes
```bash
# Execute todos os testes
php artisan test

# Execute testes específicos
php artisan test --testsuite=Feature  # Testes de integração
php artisan test --testsuite=Unit     # Testes unitários
php artisan test --filter=common_user_can_transfer_money_to_another_user #Execute um teste específico

```

## 🔍 Listar Rotas
Para ver todas as rotas disponíveis:
```bash
php artisan route:list
```

## 📌 Variáveis de Ambiente Importantes
| Variável | Descrição | Valor Padrão |
|----------|-----------|--------------|
| APP_ENV | Ambiente da aplicação | local |
| DB_CONNECTION | Banco de dados | mysql |
| SERVICES_AUTHORIZATION_URL | URL do serviço de autorização | - |
| SERVICES_NOTIFICATION_URL | URL do serviço de notificação | - |

