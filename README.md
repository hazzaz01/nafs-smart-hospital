# Smart Hospital — Local Development Setup

This guide takes a new machine from a fresh clone to a working local installation. The recommended setup is Windows with Laragon because that matches the development environment used by this project.

## What you need

- Git
- Laragon Full with Apache and MySQL 8
- PHP 8.1, 8.2, or 8.3 with `mysqli`, `mbstring`, `curl`, `gd`, `intl`, `openssl`, and `zip` enabled
- About 1 GB of free disk space

No Composer or Node.js installation is required for the current application.

## 1. Install and start Laragon

1. Install Laragon Full.
2. Open Laragon.
3. Click **Start All**.
4. Confirm that both Apache and MySQL show as running.
5. In Laragon, select **Menu → PHP → Version** and choose PHP 8.3 if it is available.

If Apache cannot start, another program is probably using port 80. Stop IIS, Skype's web server option, or the other Apache/Nginx process, then restart Laragon.

## 2. Put the project in Laragon's web directory

Open PowerShell and run:

```powershell
Set-Location C:\laragon\www
git clone <YOUR_REPOSITORY_URL> smart-hospital
Set-Location C:\laragon\www\smart-hospital
```

The folder must be named `smart-hospital`. The expected final path is:

```text
C:\laragon\www\smart-hospital\index.php
```

If you received a ZIP instead of a Git URL, extract it to `C:\laragon\www\smart-hospital` and make sure `index.php` is directly inside that folder—not inside another nested folder.

## 3. Create the local configuration files

From the project directory, run:

```powershell
Copy-Item application\config\database.example.php application\config\database.php
Copy-Item application\config\license.example.php application\config\license.php
```

The default configuration expects:

```text
Database host: localhost
Database name: smart_hospital
Database user: root
Database password: empty
```

Those are Laragon's default MySQL credentials. If your MySQL root account has a password, edit `application/config/database.php` and change only the `password` value.

Do not commit `database.php` or `license.php`; both are intentionally ignored by Git.

## 4. Create and import the database

### Recommended method: HeidiSQL

1. In Laragon, select **Menu → MySQL → HeidiSQL**.
2. Open the default Laragon session.
3. Right-click the server in the left panel and select **Create new → Database**.
4. Enter `smart_hospital` as the database name.
5. Choose `utf8_general_ci` as the collation.
6. Select the new `smart_hospital` database.
7. Select **File → Run SQL file**.
8. Choose:

```text
backup\database_backup\db_ver_5.0_2025-05-15_17-56-35.sql
```

9. Wait until the import finishes without errors. The file is approximately 36 MB, so it may take a minute.

Do not use phpMyAdmin for this import if it reports an upload-size or execution-time limit; HeidiSQL does not have that browser upload limitation.

## 5. Set known local login passwords

The seed database contains hashed passwords. To make local credentials predictable, open a new HeidiSQL query tab, paste the following SQL, and run it:

```sql
UPDATE staff
SET password = '$2y$10$.cKhPp..i25uxVFvK.mteOID0jJx6kfUJJIkO2eU21QtCSbUvLBCy'
WHERE id IN (1, 2);
```

This establishes these local accounts:

| Role | Username | Password |
|---|---|---|
| Super Admin | `superadmin@gmail.com` | `Admin@123` |
| Doctor | `ajay@gmail.com` | `Admin@123` |

Use the Doctor account when testing the Eye Examinations module.

These credentials are for local development only. Never use them in a deployed environment.

## 6. Apply the project migrations

The seed database predates the custom Eye Examination module, so migrations 125 and 126 must be applied.

1. Open `application/config/migration.php`.
2. Change:

```php
$config['migration_enabled'] = false;
```

to:

```php
$config['migration_enabled'] = true;
```

3. Confirm that the version is:

```php
$config['migration_version'] = 126;
```

4. Open this URL in a browser:

```text
http://localhost/smart-hospital/migrate
```

5. Wait for this exact message:

```text
Database updated successfully.
```

6. Immediately change `migration_enabled` back to `false`.

Do not leave migrations enabled after setup.

## 7. Open the application

Open:

```text
http://localhost/smart-hospital/site/login
```

Log in as the Doctor:

```text
Username: ajay@gmail.com
Password: Admin@123
```

After login, open:

```text
http://localhost/smart-hospital/admin/eyeexam
```

The page should show the Eye Examinations dashboard. Click **New Eye Exam** and confirm that the form contains these tabs:

- Basic Info
- Visual Acuity
- Refraction
- IOP
- Anterior Segment
- Fundus
- Assessment

The Assessment tab should support multiple diagnoses, multiple medications, a treatment plan, and follow-up settings.

## 8. Required writable directories

Laragon on Windows normally handles these permissions automatically. If the application cannot write logs, cache, uploaded files, or sessions, make sure the current Windows user has Modify permission on:

```text
application\cache
application\logs
temp
uploads
```

On Linux or macOS with Apache, run from the project root:

```bash
chmod -R 775 application/cache application/logs temp uploads
```

The Apache/PHP user must own or have group write access to those directories.

## Alternative Apache setup for Linux or macOS

If Laragon is not available:

1. Install Apache, MySQL 8, and PHP 8.1–8.3.
2. Enable Apache's `rewrite` module.
3. Allow `.htaccess` overrides for the project directory with `AllowOverride All`.
4. Place the project at your web root as a folder named `smart-hospital`.
5. Create the `smart_hospital` database and import the same SQL file.
6. Copy the example configuration files as described above.
7. Edit `application/config/config.php` and set `base_url` to the exact local URL, including the trailing slash.
8. Apply migrations 125 and 126 using the migration steps above.

Example Apache directory configuration:

```apache
<Directory "/var/www/html/smart-hospital">
    Options FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>
```

Then restart Apache.

## Troubleshooting

### HTTP 500 on `/admin/eyeexam`

The Eye Examination tables were not migrated. Repeat step 6 and confirm that `eye_examinations` exists in the `smart_hospital` database.

### `connection failed: Unknown database 'smart_hospital'`

The database was not created or the configured name is wrong. Create `smart_hospital`, import the seed SQL, and confirm the name in `application/config/database.php`.

### `Access denied for user 'root'@'localhost'`

The MySQL password in `application/config/database.php` does not match the local MySQL account. Enter the correct local password there.

### 404 for every page except the home page

Apache rewriting is disabled. In Laragon, verify that Apache is running and that `mod_rewrite` is enabled. On a custom Apache setup, enable `rewrite`, set `AllowOverride All`, and restart Apache.

### The page has no styling or JavaScript

The project directory or `base_url` is wrong. The Windows setup expects both of these:

```text
Folder: C:\laragon\www\smart-hospital
URL:    http://localhost/smart-hospital/
```

### Login says the credentials are invalid

Run the password-reset SQL from step 5 again. Confirm that staff IDs 1 and 2 exist and that both have `is_active = 1`.

### A blank page appears

Temporarily set this in `application/config/config.php`:

```php
$config['log_threshold'] = 1;
```

Reload the failing page, then inspect the newest file under `application/logs`. Restore `log_threshold` to `0` after diagnosing the issue.

## Final setup checklist

- Apache and MySQL are running.
- The project folder is exactly `smart-hospital`.
- `application/config/database.php` exists.
- `application/config/license.php` exists.
- The `smart_hospital` database was imported successfully.
- Migrations report `Database updated successfully.`
- `migration_enabled` is back to `false`.
- Doctor login works with `ajay@gmail.com` / `Admin@123`.
- `/admin/eyeexam` loads without an HTTP 500 error.

