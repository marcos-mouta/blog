# Blog com Laravel — Posts + Comentários + Papéis (RBAC simples)

## Como usar

```bash
# 1. Criar um projeto Laravel limpo
composer create-project laravel/laravel blog
cd blog

# 2. Copiar as pastas app, database, resources, routes e tests deste pacote para dentro de blog
#    (aceitar substituir os arquivos existentes)

# 3. Criar as tabelas e rodar
php artisan migrate
php artisan serve        # http://localhost:8000

# 4. Testes
php artisan test
```

## Papéis (coluna `role` em `users`)

Todo mundo que se cadastra começa como `user`. Para mudar o papel de alguém:

```bash
php artisan user:role email@exemplo.com admin
php artisan user:role email@exemplo.com moderator
php artisan user:role email@exemplo.com user
```

| Ação                       | user                  | moderator             | admin                 |
|----------------------------|-----------------------|-----------------------|-----------------------|
| Ver posts e comentários    | sim (inclusive visitante) | sim               | sim                   |
| Criar post / comentar      | sim                   | sim                   | sim                   |
| Editar o próprio post/comentário | sim             | sim                   | sim                   |
| Editar conteúdo de outras pessoas | não            | não                   | não                   |
| Apagar o próprio post/comentário | sim             | sim                   | sim                   |
| Apagar comentário de outra pessoa | não            | sim                   | sim                   |
| Apagar post de outra pessoa | não                  | não                   | sim                   |

As regras ficam em `app/Policies/PostPolicy.php` e `CommentPolicy.php`.
