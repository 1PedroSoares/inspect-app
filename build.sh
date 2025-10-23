#!/bin/bash
echo "Iniciando build do Laravel..."

# 1. Instalar dependências do Composer
composer install --no-dev --optimize-autoloader

# 2. Instalar dependências NPM e compilar assets
# (Baseado no seu package.json)
npm install
npm run build

# 3. Preparar o Laravel para produção
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Criar o link de storage (a Vercel cria /public/storage)
php artisan storage:link

echo "Build concluído."