# API de Gerenciamento de Manutenção de Veículos (Demo Técnica)

Este projeto é uma API RESTful completa, construída em Laravel 12, para um sistema de gestão de manutenção de veículos.

A aplicação permite que os utilizadores se registem, gira os seus veículos pessoais, e rastreiem um histórico detalhado de manutenções, alertas futuros e anexos (como notas fiscais ou fotos).

## Destaques da Arquitetura

Este projeto foi desenhado para demonstrar proficiência nos seguintes conceitos do ecossistema Laravel:

  * **Autenticação JWT:** Sistema completo de registo, login, logout e refresh de token usando `tymon/jwt-auth`.
  * **Segurança (Policies):** O acesso aos recursos (Veículos, Manutenções, etc.) é estritamente controlado por Policies (ex: `VehiclePolicy`). Um utilizador *só* pode aceder ou modificar os seus próprios dados.
  * **Validação (Form Requests):** A lógica de validação de entrada é isolada em classes de Form Request (ex: `StoreVehicleRequest`), mantendo os Controladores limpos.
  * **Regras Customizadas:** Demonstração de regras de negócio personalizadas, como a `PlateFormat`, que valida placas nos formatos Renavan (AAA1234) e Mercosul (AAA1B23).
  * **Respostas Padronizadas (Traits):** O uso da `ApiResponser` Trait assegura que todas as respostas JSON da API (sucesso ou erro) sigam uma estrutura consistente.
  * **Transformação de Dados (API Resources):** Os dados de saída são formatados através de API Resources (ex: `VehicleResource`), que atuam como uma camada de transformação, protegendo a estrutura da base de dados e formatando dados para o cliente.
  * **Querying Avançado:** Os endpoints de listagem (index) usam `spatie/laravel-query-builder` para permitir filtragem, ordenação e inclusão de *queries* complexas diretamente via parâmetros de URL.
  * **Logging Centralizado:** Erros 500 são automaticamente capturados e registados com contexto completo (utilizador, URL, IP, erro) através de um método *helper* no `Controller` base, sem poluir a lógica de negócio.
  * **Gestão de Ficheiros:** Upload seguro de ficheiros (`multipart/form-data`) e download protegido por autorização (ex: `MaintenanceAttachmentController`).

-----

## Como Executar (Ambiente de Desenvolvimento com Laravel Sail)

Este projeto está configurado para ser executado com o [Laravel Sail](https://laravel.com/docs/sail), um ambiente de desenvolvimento local leve baseado em Docker.

**Não é necessário** instalar PHP, Composer, MySQL ou qualquer outra dependência na sua máquina. Apenas o Docker.

### 1\. Pré-requisitos

  * [Docker Desktop](https://www.docker.com/products/docker-desktop/) (ou Docker Engine no Linux)

### 2\. Instalação (Passo a Passo)

1.  **Clone o repositório:**

    ```bash
    git clone https://github.com/JonathanRFraga1/api-vehicle-maintenance.git
    cd api-vehicle-maintenance
    ```

2.  **Copie o ficheiro de ambiente:**
    O Sail é configurado através do `.env`.

    ```bash
    cp .env.example .env
    ```

3.  **Instale as dependências (via Sail):**
    Este comando corre o `composer install` *dentro* de um novo contentor Docker temporário.

    ```bash
    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v $(pwd):/app \
        -w /app \
        laravelsail/php83-composer:latest \
        composer install --ignore-platform-reqs
    ```

4.  **Inicie os Contentores (Sail):**
    Este comando irá construir e iniciar os contentores da aplicação (PHP, Nginx, MySQL, etc.) em segundo plano.

    ```bash
    ./vendor/bin/sail up -d
    ```

5.  **Gere as Chaves da Aplicação:**
    Agora que os contentores estão em execução, use o "alias" do Sail para executar comandos artisan.

    ```bash
    ./vendor/bin/sail artisan key:generate
    ./vendor/bin/sail artisan jwt:secret
    ```

### 3\. Base de Dados

O Sail já criou a sua base de dados. Agora, execute as migrações (que criam as tabelas) e os **seeders** (que povoam a base de dados com dados de teste):

```bash
./vendor/bin/sail artisan migrate --seed
```

A sua aplicação está agora 100% funcional e pronta para ser testada.

-----

## Como Testar a API (Postman)

A forma mais fácil de testar todos os endpoints é usando a coleção Postman deste projeto. Você pode acessar à coleção de duas maneiras:

**Opção 1: Acessar Online (Recomendado)**

Clique no link abaixo para acessar à coleção diretamente no seu navegador ou importá-la para a sua aplicação Postman:

[Postman online](https://www.postman.com/cloudy-firefly-986210/workspace/api-vehicle-maintenance/collection/20098354-aa37532c-d79c-4590-b473-8d7b4910eeef?action=share&source=collection_link&creator=20098354)

**Opção 2: Importar o Arquivo**

1.  **Aplicação em Execução:**
    A API estará em execução em: `http://localhost` (O Sail, por defeito, mapeia a porta 80 do host).

2.  **Importe a Coleção:**

      * Abra o Postman.
      * Clique em "Import".
      * Selecione o arquivo `API.postman_collection.json` da raiz deste projeto.

3.  **Configure a Variável `base_uri`:**

      * Na coleção importada, clique nos três pontinhos (...) e vá a "Edit".
      * Vá ao separador **"Variables"**.
      * No campo `base_uri`, altere o "CURRENT VALUE" para o URL do seu servidor Sail: `http://localhost`.

### Fluxo de Teste (Login e Autenticação)

Os *seeders* já criaram utilizadores de teste para si.

**Utilizadores de Teste:**

  * **Email:** `joao@email.com` | **Senha:** `123456`
  * **Email:** `maria@email.com` | **Senha:** `123456`

**Para se autenticar:**

1.  Na coleção Postman, abra a pasta "Auth" e execute a requisição `POST /api/login`.
2.  Use um dos utilizadores de teste no *Body* da requisição.
3.  Ao executar a requisição, o access token sera automaticamente mapeado para a váriavel {{token}}.

Agora, todas as outras requisições protegidas na coleção (como `GET /api/vehicles`) irão funcionar automaticamente.

A documentação detalhada de cada endpoint (parâmetros, corpos de requisição e respostas de exemplo) está disponível no separador **"Description"** de cada requisição individual dentro do Postman.
