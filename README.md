# Sistema Acadêmico

Sistema de gestão acadêmica desenvolvido em PHP com padrão MVC.

## Perfis de Usuário

- **Aluno**: Visualiza aulas, provas, tarefas e boletim
- **Professor**: Gerencia aulas, planos de aula, provas, turmas e matérias
- **Coordenador**: Gerencia professores e planos semestrais
- **Secretário**: Gerencia cadastros de alunos, professores e coordenadores
- **Administrador**: Acesso completo ao sistema

## Estrutura do Projeto

```
/app
  /controllers
  /models
  /views
/public
  /assets
    /css
    /js
    /images
  index.php
/config
/helpers
```

## Banco de Dados

O sistema utiliza MySQL com as seguintes tabelas principais:
- usuarios
- series
- turmas
- materias
- planos_aula
- aulas
- provas
- questoes
- alternativas
- tarefas
- respostas_prova
- respostas_tarefa
- boletim

## Instalação

1. Clone o repositório
2. Configure o banco de dados em `/config/database.php`
3. Acesse via navegador

## Funcionalidades Principais

- Sistema de autenticação
- Gestão de usuários por perfil
- Gestão de aulas e planos de aula
- Sistema de provas online
- Boletim e acompanhamento de progresso