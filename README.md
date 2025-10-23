# Desafio Técnico: Sistema de Inspeções (InspecApp)

Seja bem vindo. Este é um micro-sistema desenvolvido em Laravel (PHP) e JavaScript (jQuery) para gerenciar inspeções de campo e seus checklists e consumir APIs externas.

## Funcionalidades

* CRUD completo de Inspeções (Criar, Listar, Editar, Excluir).
* Gerenciamento de Checklist por inspeção (Adicionar, Remover, Marcar como feito) via AJAX.
* Integração com a API ViaCEP para preenchimento automático de endereço ao digitar o CEP.
* Regra de Negócio: Uma inspeção só pode ser "Concluída" se todos os seus itens de checklist marcados como "Obrigatório" estiverem concluídos.

## Stack Utilizada

* **Backend:** PHP 8.1+ / Laravel 10
* **Frontend:** Bootstrap 5, JavaScript - jQuery (via CDN, não requer build/NPM)
* **Banco de Dados:** MySQL, mas pode ser usado outro de sua preferência, ajustando o `.env`
* **API Externa:** [ViaCEP](https://viacep.com.br/)
* **Dependências PHP:** Composer


## Como Rodar o Projeto

### 1. Pré-requisitos

* PHP (>= 8.1)
* Composer
* Um servidor de banco de dados relacional de sua preferência, utilizei o MySQL.

### 2. Instalação

1.  Clone o repositório:
    ```bash
    git clone https://[seu-repositorio-git]/inspecao-app.git
    cd inspecao-app
    ```

2.  Instale as dependências do PHP (Laravel):
    ```bash
    composer install
    ```

3.  Configure o ambiente:
    * Copie o arquivo de exemplo `.env.example` para `.env`:
        ```bash
        cp .env.example .env
        ```
    * Gere a chave da aplicação:
        ```bash
        php artisan key:generate
        ```
    * Edite o arquivo `.env` e configure sua conexão com o banco de dados (DB_DATABASE, DB_USERNAME, DB_PASSWORD).

4.  Execute as Migrations e os Seeders:
    * Isso criará as tabelas e adicionará os dados de exemplo (2 inspeções com checklists).
    ```bash
    php artisan migrate --seed
    ```

### 3. Executando

1.  Inicie o servidor de desenvolvimento do Laravel:
    ```bash
    php artisan serve
    ```

2.  Acesse o sistema no seu navegador:
    [http://127.0.0.1:8000](http://127.0.0.1:8000)

## API Externa (ViaCEP)

O sistema consome a API do ViaCEP através de um endpoint interno que atua como *wrapper*.

* **Endpoint interno:** `GET /api/cep/{cep}`
* **Exemplo de chamada (ViaCEP):** `https://viacep.com.br/ws/01001001/json/`
* **Implementação:** A lógica de consumo e tratamento de erros está centralizada no serviço `app/Services/ViaCepService.php`, centralizando a responsabilidade e aplicando as melhores práticas.

## Testes

Para rodar os testes unitários e de feature:

```bash
php artisan test