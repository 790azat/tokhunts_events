# Tokhunts Events

Сайт компании по организации мероприятий: Laravel 13 + Livewire 4 + Tailwind CSS 4.

- Языки: армянский (по умолчанию), русский, английский. Переключатель в шапке, выбор запоминается.
- Живые разделы: слайд-шоу на первом экране (или видео), портфолио с фильтром по категориям и подгрузкой, галерея видео с плеером, отзывы, счётчики, форма заявки с проверкой полей на лету.
- Регистрация и вход. Клиент видит свои заявки и их статус в «Мои заявки».
- Админ-панель `/admin`: работы (фото, видео-файлы, ссылки YouTube/Vimeo, порядок, обложка, подписи), категории, услуги, отзывы, заявки со статусами, пользователи и права админа, контакты/соцсети/цифры/видео на главной.
- Все тексты услуг, работ и категорий вводятся на трёх языках.

## Локальный запуск

```bash
composer install && npm install
cp .env.example .env && php artisan key:generate
# в .env укажите ADMIN_EMAIL и ADMIN_PASSWORD
php artisan migrate --seed
php artisan storage:link
npm run build
php artisan serve
```

Админ входит на `/login` с ADMIN_EMAIL/ADMIN_PASSWORD. Если админ ещё не создан, достаточно зарегистрироваться на сайте с адресом из ADMIN_EMAIL: он сразу получит права администратора. Другим пользователям права выдаются в разделе «Пользователи».

`php artisan db:seed` добавляет категории, услуги и 6 демо-работ со стоковыми фото. Демо-работы нужно удалить или заменить своими в админ-панели.

## Деплой на Vercel

У Vercel нет постоянного диска, поэтому база данных и файлы живут во внешних сервисах:

| Что | Выбор по умолчанию | Почему |
| --- | --- | --- |
| PHP | `vercel-php@0.8.0` (`api/index.php`, `vercel.json`), регион `fra1` | community-runtime для PHP на Vercel; регион рядом с базой Neon (Франкфурт) и с Арменией |
| База | Postgres от **Neon** (Vercel → Storage → Neon, бесплатный план) | подключается к проекту в два клика, сам добавляет переменные |
| Фото и видео | **Vercel Blob** (Vercel → Storage → Blob, подключается к проекту) | файлы грузятся из браузера прямо в Blob, минуя лимит Vercel 4.5 МБ; до 500 МБ на файл |

Таблицы создаются сами: первый запрос после каждого деплоя выполняет миграции, а в пустую базу добавляет админа (ADMIN_EMAIL/ADMIN_PASSWORD) и стартовый контент.

Пока база не подключена, сайт на Vercel работает в **демо-режиме**: временная SQLite в `/tmp` создаётся с демо-данными при каждом холодном старте. Заявки, регистрации и изменения в этом режиме не сохраняются надолго, загрузка файлов не работает.

### 1. Переменные окружения (Vercel → Project → Settings → Environment Variables)

```
APP_KEY=base64:...            # php artisan key:generate --show
APP_ENV=production
APP_DEBUG=false
APP_URL=https://ваш-домен
ADMIN_EMAIL=почта-владельца
ADMIN_PASSWORD=надёжный-пароль

# База (Neon добавляет DATABASE_URL сам)
DB_CONNECTION=pgsql

# Файлы: BLOB_READ_WRITE_TOKEN добавляется при подключении Blob-хранилища

# Альтернатива Blob: S3 / Cloudflare R2
MEDIA_DISK=s3
FILESYSTEM_DISK=s3
LIVEWIRE_TEMPORARY_FILE_UPLOAD_DISK=s3
AWS_ACCESS_KEY_ID=...
AWS_SECRET_ACCESS_KEY=...
AWS_DEFAULT_REGION=auto
AWS_BUCKET=tokhunts-media
AWS_ENDPOINT=https://<account-id>.r2.cloudflarestorage.com
AWS_URL=https://pub-xxxx.r2.dev   # публичный адрес бакета
AWS_USE_PATH_STYLE_ENDPOINT=true
```

### 2. Альтернатива: бакет Cloudflare R2 (если не Vercel Blob)

1. Cloudflare → R2 → Create bucket (`tokhunts-media`).
2. Settings → Public access → включить `r2.dev` (или свой поддомен). Этот адрес идёт в `AWS_URL`.
3. R2 → Manage API tokens → токен с правами Object Read & Write → ключи в `AWS_ACCESS_KEY_ID` / `AWS_SECRET_ACCESS_KEY`.
4. Settings → CORS policy, чтобы браузер мог загружать файлы напрямую:

```json
[{ "AllowedOrigins": ["https://ваш-домен", "https://*.vercel.app"], "AllowedMethods": ["GET", "PUT", "POST"], "AllowedHeaders": ["*"], "MaxAgeSeconds": 3600 }]
```

Файлы из админки уходят из браузера прямо в R2, минуя лимит Vercel в 4.5 МБ на запрос. Лимиты: фото до 20 МБ, видео до 200 МБ.

### 3. Таблицы в базе

На Vercel миграции выполняются автоматически. Вручную, если нужно:

Вариант А, через GitHub: добавьте секреты репозитория `DB_URL`, `APP_KEY`, `ADMIN_EMAIL`, `ADMIN_PASSWORD`, затем Actions → **Migrate database** → Run workflow (отметьте «seed» при первом запуске).

Вариант Б, со своего компьютера: `DB_CONNECTION=pgsql DB_URL=... php artisan migrate --seed --force`.

После этого сделайте Redeploy в Vercel.

## Как владельцу загружать работы

1. Войти на `/login` → «Админ-панель».
2. «Работы и видео» → «Новая работа»: название (HY/RU/EN), категория, дата, место.
3. Перетащить фото и видео (можно сразу несколько) или вставить ссылку на YouTube/Vimeo → «Сохранить».
4. Первое фото будет обложкой; порядок и обложку можно поменять стрелками и звёздочкой.
5. «Избранная» поднимает работу наверх портфолио и в слайд-шоу на главной.
