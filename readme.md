# Abelо Host — PHP Blog

An educational blog built with **PHP, MySQL 8, Smarty, and SCSS**, run using **Docker Compose**. The application uses routes, controllers, services, and repositories to work with posts and categories.


## Services

| Service | Container | Address / purpose |
| --- | --- | --- |
| PHP + Apache | `blog_php` | <http://localhost:8080> |
| MySQL 8.0 | `blog_mysql` | `localhost:3306` from the host machine; `mysql:3306` from other containers |
| phpMyAdmin | `blog_phpmyadmin` | <http://localhost:8081> |
| Sass (Node.js 22) | `blog_sass` | Automatic SCSS compilation via `npm run watch` |

## 1. Get the Project

Clone the repository or open the existing project directory:

```bash
git clone https://github.com/alexergaster/abelo_host.git
cd abelo_host
```

## 2. Configure the Environment

Create a **`.env`** file in the project root (or copy `.env.example` if it exists):

```dotenv
DB_HOST=mysql
DB_DATABASE=blog
DB_USERNAME=blog_user
DB_PASSWORD=blog_password
```

These values match the MySQL settings in the provided `docker-compose.yml`:

```yaml
MYSQL_DATABASE: blog
MYSQL_USER: blog_user
MYSQL_PASSWORD: blog_password
```

**Important:** `DB_HOST` must be `mysql`, **not** `localhost`, because PHP connects to the database from another container.

## 3. Start the Containers

Run the following command from the project root:

```bash
docker compose up -d --build
```

Check container status:

```bash
docker compose ps
```

View logs if an error occurs:

```bash
docker compose logs -f php
docker compose logs -f mysql
docker compose logs -f sass
```

## 4. Install PHP Dependencies


```bash
docker compose exec php composer install
```

## 5. Prepare the Database

On the first startup, MySQL creates the `blog` database and the `blog_user` account based on the Compose configuration. **The tables and initial data are not created automatically by these settings alone.**

If the repository contains an SQL file with the database schema (for example, `database/schema.sql`), import it through phpMyAdmin:

1. Open <http://localhost:8081>.
2. Log in as `blog_user` with password `blog_password` (or `root` / `root` for the local environment).
3. Select the **`blog`** database.
4. Go to the **Import** tab and select the actual SQL file from the project.

If the project includes `database/seed.php`, you can run it after creating the tables:

```bash
docker compose exec php php database/seed.php
```

Expected core blog tables: `posts`, `categories`, `tags`, `post_categories`, `post_tags`.

## 6. Compile SCSS

The `sass` service runs the following on startup:

```bash
npm install && npm run watch
```

When SCSS files are edited, changes should be compiled automatically according to the `watch` script in `package.json`.

To compile once (if the `build` script is defined):

```bash
docker compose exec sass npm run build
```

Check Sass logs:

```bash
docker compose logs -f sass
```

In the current project structure, the expected CSS file is `public/assets/css/style.css`. Its exact path is determined by the scripts in `package.json`.

## 7. Open the Website

After starting the containers and preparing the database, open:

- **Home page:** <http://localhost:8080/>
- **Category by slug:** <http://localhost:8080/categories/php>
- **Individual post:** <http://localhost:8080/posts/1>
- **phpMyAdmin:** <http://localhost:8081/>

**Stack:** PHP · Apache · MySQL 8.0 · Smarty · SCSS/Sass · Node.js 22 · Docker Compose.
