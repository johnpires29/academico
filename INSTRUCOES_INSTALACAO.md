# Instruções de Instalação do Sistema Acadêmico

## Erro de Conexão com o Banco de Dados

Você está recebendo o erro: `Unknown database 'academico'` porque o banco de dados ainda não foi criado.

## Passos para Instalação

### 1. Criar o Banco de Dados

Existem duas maneiras de criar o banco de dados:

#### Opção 1: Usando o Laragon

1. Abra o Laragon
2. Clique no botão "Database" ou acesse o HeidiSQL incluído
3. Conecte-se ao MySQL usando:
   - Host: localhost
   - Usuário: root
   - Senha: (deixe em branco, a menos que você tenha definido uma)
4. Crie um novo banco de dados chamado `academico`

#### Opção 2: Usando o MySQL diretamente

1. Abra um terminal ou prompt de comando
2. Execute o comando:
   ```
   mysql -u root -e "CREATE DATABASE academico CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
   ```

### 2. Importar a Estrutura Inicial

Após criar o banco de dados, você precisa importar a estrutura inicial:

1. No HeidiSQL ou outro cliente MySQL, conecte-se ao banco de dados `academico`
2. Importe o arquivo SQL localizado em `config/create_database.sql`

Alternativamente, você pode executar via linha de comando:
```
mysql -u root academico < config/create_database.sql
```

### 3. Verificar a Configuração

Certifique-se de que o arquivo `config/database.php` contém as configurações corretas:

```php
return [
    'host' => 'localhost',
    'dbname' => 'academico',  // Deve ser 'academico'
    'username' => 'root',     // Seu usuário do MySQL
    'password' => '',         // Sua senha do MySQL (se houver)
    'charset' => 'utf8mb4',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
];
```

### 4. Credenciais Iniciais

Após a instalação, você pode fazer login com as seguintes credenciais:

- **Email**: admin@exemplo.com
- **Senha**: admin123

## Solução de Problemas

Se você continuar enfrentando problemas:

1. Verifique se o Laragon está em execução
2. Verifique se o serviço MySQL está ativo
3. Confirme que as credenciais no arquivo `config/database.php` estão corretas
4. Verifique se o banco de dados `academico` foi criado corretamente