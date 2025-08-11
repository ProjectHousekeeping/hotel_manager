# Sistema de Gestão

Um sistema web desenvolvido em Laravel com interface administrativa usando Filament, projetado para oferecer uma solução completa de gestão com interface moderna e intuitiva.

## 📋 Requisitos do Sistema

Antes de instalar o sistema, certifique-se de que seu ambiente atende aos seguintes requisitos:

### Softwares Necessários
- **PHP 8.1 ou superior**
- **Composer** (gerenciador de dependências do PHP)
- **MySQL 8.0 ou superior** (banco de dados do projeto)

### Extensões PHP Obrigatórias
As seguintes extensões devem estar habilitadas no arquivo `php.ini`:
- `extension=fileinfo` - Para manipulação de arquivos
- `extension=intl` - Para internacionalização
- `extension=pdo_mysql` - Para conexão com banco MySQL

## 🚀 Instalação

Siga os passos abaixo para configurar o sistema em seu ambiente local:

### 1. Clone o Repositório
```bash
git clone [URL_DO_REPOSITORIO]
cd [NOME_DO_DIRETORIO]
```

### 2. Instale as Dependências
Execute o comando abaixo para instalar todas as dependências do projeto:
```bash
composer update
```

### 3. Configure o Arquivo de Ambiente
Copie o arquivo de exemplo de configuração:
```bash
copy .env.example .env
```

Abra o arquivo `.env` criado e configure as seguintes variáveis:

```env
# Configurações da Aplicação
APP_NAME="Hotel Panel"
APP_ENV=local
APP_KEY=          # Será gerado automaticamente no próximo passo
APP_DEBUG=true
APP_TIMEZONE='America/Sao_Paulo'
APP_URL=http://localhost:8000    # ou URL do seu servidor de deploy
APP_LOCALE='pt_BR'

# Configurações de Localização
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

# Configurações de Sistema
APP_MAINTENANCE_DRIVER=file
PHP_CLI_SERVER_WORKERS=4
BCRYPT_ROUNDS=12

# Configurações de Log
LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

# Configurações de Banco de Dados (MySQL)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hotel_panel
DB_USERNAME=root
DB_PASSWORD=sua_senha_mysql
```

### 4. Gere a Chave de Aplicação
Gere a chave de criptografia do Laravel:
```bash
php artisan key:generate
```

### 5. Configure o Banco de Dados
Execute as migrações para criar as tabelas necessárias:
```bash
php artisan migrate
```

### 6. Crie um Usuário Administrador
Crie um usuário para acessar o painel administrativo:
```bash
php artisan filament:user
```
*Siga as instruções no terminal para definir nome, email e senha do administrador.*

### 6.1 Popula o banco de dados com informações genericas para testes.
```bash
php artisan db:seed
```

### 7. Inicie o Servidor de Desenvolvimento
Execute o servidor local do Laravel:
```bash
php artisan serve
```

### 8. Acesse o Sistema
Abra seu navegador e acesse:
- **URL do sistema:** `http://localhost:8000`
- **Painel administrativo:** `http://localhost:8000/admin`

Use as credenciais criadas no passo 5 para fazer login no painel administrativo.

## 🛠️ Tecnologias Utilizadas

- **Laravel** - Framework PHP
- **Filament** - Painel administrativo
- **SQLite** - Banco de dados
- **PHP** - Linguagem de programação

## 📝 Comandos Úteis

### Resetar o Banco de Dados
```bash
php artisan migrate:fresh
```

### Criar Novo Usuário Admin
```bash
php artisan filament:user
```

### Limpar Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## 🆘 Solução de Problemas

### Erro: "Class not found"
Execute: `composer dump-autoload`

### Erro: "Key not found"
Execute: `php artisan key:generate`

### Erro: "Database not found"
Execute: `php artisan migrate`

## 👥 Desenvolvedores

Este sistema foi desenvolvido por:

**Matheus Zalamena**  
[![LinkedIn](https://img.shields.io/badge/LinkedIn-0077B5?style=flat&logo=linkedin&logoColor=white)](https://linkedin.com/in/matheus-zalamena)

**Gabriel Bellagamba**  
[![LinkedIn](https://img.shields.io/badge/LinkedIn-0077B5?style=flat&logo=linkedin&logoColor=white)](https://linkedin.com/in/gabriel-bellagamba)

## 📄 Licença

Este projeto está sob a licença [MIT](LICENSE).

## 🤝 Contribuição

Contribuições são bem-vindas! Sinta-se à vontade para abrir issues ou enviar pull requests.

---

**Desenvolvido com ❤️ usando Laravel e Filament**
