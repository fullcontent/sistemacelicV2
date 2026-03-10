# PLAN: Sistema de Login, Roles, Permissões e Hierarquia

## 1. Análise e Requisitos

### Objetivo:
Implementar a autenticação de usuários (email e senha) usando a interface do **AdminLTE**.
Estabelecer um sistema de autoridade e segurança unindo **Tipos (Grupos)**, **Roles**, **Permissões Granulares** e **Hierarquia**.

### Estrutura de Usuários
- **Grupos (Tipos):** CASTRO vs CLIENTE.
- **Roles:** Programador, Administrador, Gestor, Coordenador, Operacional, Financeiro.
- **Múltiplas Roles:** Um usuário pode ter mais de uma role simultaneamente.
- **Hierarquia:** 
  - Nível 1: Programador (Acesso total)
  - Nível 2: Administrador
  - Nível 3: Gestor
  - Nível 4: Coordenador
  - Nível 5: Operacional / Financeiro
- Clientes são isolados e não podem interferir no sistema do grupo CASTRO.

### Funcionalidades Núcleo
1. **Autenticação:** Login, Logout com views customizadas do AdminLTE.
2. **Autorização:** Utilização do pacote `spatie/laravel-permission` (ideal para gerenciar múltiplas roles e permissões granulares por módulo).
3. **Auditoria:** Logar ações críticas (Criação de usuário, alteração de permissões/roles, desativação, recuperação do Programador). Provavelmente usaremos `spatie/laravel-activitylog`.
4. **Painel de Gestão:** Interface detalhada no AdminLTE listando módulos e checkboxes de permissões para cada usuário, exibindo as permissões padrão da Role e as customizadas.
5. **Regras de Negócio de Hierarquia:** Lógica customizada (Policy ou Middleware) para impedir que Nível 3 altere Nível 2, etc.

## 2. Fases da Implementação (Roadmap)

### Fase 1: Fundação Backend e Banco de Dados 
- Instalar e configurar pacotes: `spatie/laravel-permission` (Roles/Permissões) e `spatie/laravel-activitylog` (Auditoria).
- Atualizar a base de dados: Migration adicionando colunas no `users` (ex: `group_type` [CASTRO/CLIENTE], `hierarchy_level`, `client_id`).
- Criar Seeders preenchendo as Roles exatas e Permissões por módulos definidos (Financeiro, Serviços, OC, Admin).
- Criar o Super Admin (Programador) e os níveis de hierarquia na Model `User`.

### Fase 2: Autenticação UI e Lógica
- Configurar rotas de Login e Controladores customizados (`LoginController`).
- Publicar e ajustar views do `jeroennoten/laravel-adminlte` específicas para a tela de autenticação (email e senha).
- Integrar validações, mensagens de erro estruturadas e sistema de *Remember Me*.

### Fase 3: Controle e Hierarquia (Middleware e Policies)
- Criar `UserPolicy` ou Middlewares para as defesas de nível: "Um usuário não pode editar permissões de outro usuário com nível superior".
- Proteger rotas de Administração usando as roles definidas.

### Fase 4: Painel de Gestão de Permissões
- Construção visual do painel CRUD de usuários no AdminLTE.
- Painel "Matrix" para atribuir Roles e marcar/desmarcar checkboxes de permissões específicas.
- Lógica de não exclusão real (Soft Deletes) implementada em rotas de desativação de usuário.

### Fase 5: Auditoria
- Interceptar eventos do Eloquent ou usar diretamente o ActivityLog para gravar quem alterou permissões de quem.
- Visualização de logs para administradores.

## 3. Arquitetura
- **Database:** `users`, `roles`, `permissions`, `model_has_roles`, `model_has_permissions`, `activity_log`.
- **Backend:** Laravel 11, Fortify/Custom Controllers, Spatie Permissions.
- **Frontend:** Blade Templates com pacotes do AdminLTE (`x-adminlte-*` components).
