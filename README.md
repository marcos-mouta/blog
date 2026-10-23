# MM Blog

Para executar e testar este sistema na sua máquina, é necessário ter instalado:

- PHP 8.3 ou superior (o sistema usa o Laravel 13, que exige PHP 8.3 no mínimo; foi desenvolvido e testado com PHP 8.5)

## Como usar

```bash
# 1. copiar arquivos de variáveis
cp .env.example .env

# 2. instalar bibliotecas e dependencias
composer install

# 3. Gerar chave de segurança
php artisan key:generate

# 4. Executar migrações do banco
php artisan migrate

# 5. Iniciar servidor local
php artisan serve
```

Após criar o novo usuário no site, para que o mesmo seja Admin:

```bash
php artisan user:role email@exemplo.com admin
```

**Funcionamento:**

1. Qualquer usuário logado pode criar uma postagem ou fazer um comentário em uma postagem existente
2. Qualquer usuário (logado ou não) pode visualizar uma postagem e seus comentários
3. Somente o criador da postagem pode apagá-la ou editá-la
4. Somente o criador do comentário pode apagá-lo ou editá-lo
5. O usuário Admin pode apagar qualquer post ou comentário

As regras ficam em `app/Policies/PostPolicy.php` e `CommentPolicy.php`.
