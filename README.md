# Sistema de Gestão

Um sistema web desenvolvido em Laravel com interface administrativa usando Filament, projetado para oferecer uma solução completa de gestão com interface moderna e intuitiva.

## 📋 Requisitos do Sistema

Antes de instalar o sistema, certifique-se de que seu ambiente atende aos seguintes requisitos:

### Softwares Necessários
- **PHP 8.1 ou superior**
- **Composer** (gerenciador de dependências do PHP)
- **SQLite** (banco de dados padrão do projeto)

### Extensões PHP Obrigatórias
As seguintes extensões devem estar habilitadas no arquivo `php.ini`:
- `extension=fileinfo` - Para manipulação de arquivos
- `extension=intl` - Para internacionalização
- `extension=pdo_sqlite` - Para conexão com banco SQLite

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
*No Linux/Mac use: `cp .env.example .env`*

### 4. Configure o Banco de Dados
Execute as migrações para criar as tabelas necessárias:
```bash
php artisan migrate
```

### 5. Gere a Chave de Aplicação
Gere a chave de criptografia do Laravel:
```bash
php artisan key:generate
```

### 6. Crie um Usuário Administrador
Crie um usuário para acessar o painel administrativo:
```bash
php artisan filament:user
```
*Siga as instruções no terminal para definir nome, email e senha do administrador.*

### 7. Inicie o Servidor de Desenvolvimento
Execute o servidor local do Laravel:
```bash
php artisan serve
```

### 8. Acesse o Sistema
Abra seu navegador e acesse:
- **URL do sistema:** `http://localhost:8000`
- **Painel administrativo:** `http://localhost:8000/admin`

Use as credenciais criadas no passo 6 para fazer login no painel administrativo.

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
[![LinkedIn](https://img.shields.io/badge/LinkedIn-0077B5?style=flat&logo=linkedin&logoColor=white)](https://linkedin.com/in/)

## 📄 Licença

Este projeto está sob a licença [MIT](LICENSE).

## 🤝 Contribuição

Contribuições são bem-vindas! Sinta-se à vontade para abrir issues ou enviar pull requests.

---

**Desenvolvido com ❤️ usando Laravel e Filament**