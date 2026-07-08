# Единое окно процессинга

Веб-приложение для менеджеров поддержки процессинга Trapeza.

## Стек

- PHP 8.4, Laravel 13, Vue 3
- MySQL 9, Redis 8.6
- Laravel Horizon, Laravel Reverb
- Docker, Predis

## Требования

- PHP 8.4
- Composer
- Node.js
- Docker + Docker Compose

## Установка

```bash
# Docker-сервисы (MySQL, Redis, phpMyAdmin)
docker compose up -d

# PHP-зависимости
php8.4 /usr/bin/composer install

# Генерация ключа
php8.4 artisan key:generate

# NPM-зависимости
npm install

# Симлинк для storage
php8.4 artisan storage:link
```

## Разработка

```bash
# Vite HMR
npm run dev
```

## Очереди

```bash
# Supervisor запускает автоматически
# Ручной запуск:
php8.4 artisan horizon
```

## Миграции

Миграции управляются централизованно в `trapeza-migrations`.

```bash
cd /var/www/trapeza-migrations
php8.4 artisan migrate --database=mysql_support_processing --path=database/migrations/support_processing
```

## Домен

- Локально: `http://support-processing.loc`
- Прод: `https://processing.trapeza.ru`

## Структура проекта

```
app/
  Http/Controllers/    — тонкие контроллеры (handleTransaction/handleException)
  UseCases/            — бизнес-логика
  Services/            — инфраструктура (интеграции, внешние сервисы)
  Models/              — Eloquent-модели
  Enums/               — enum'ы
config/
database/
docker/
resources/
  js/                  — Vue 3 SPA
routes/
  api.php              — API для SPA
  web.php              — SSO-роуты + SPA
```
