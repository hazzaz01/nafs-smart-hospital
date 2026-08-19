# Smart Hospital: Complete Local Installation Guide

This guide explains how to download the latest code and run the project on a new Windows computer using Laragon.

## 1. Required software

Install the following software before continuing:

- [Git](https://git-scm.com/download/win)
- [Laragon Full](https://laragon.org/download/)
- PHP 8.1, 8.2, or 8.3
- MySQL 8

The following PHP extensions must be enabled in Laragon:

- `curl`
- `gd`
- `intl`
- `mbstring`
- `mysqli`
- `openssl`
- `zip`

This project does not currently require Composer, Node.js, npm, or a frontend build command.

## 2. Start the local server

1. Open Laragon.
2. Click **Start All**.
3. Confirm that Apache and MySQL are both running.
4. In Laragon, open **Menu > PHP > Version** and select PHP 8.1, 8.2, or 8.3.

If Apache does not start, make sure another application such as IIS, Apache, or Nginx is not already using port 80.

## 3. Clone the latest code

Open PowerShell and run:

```powershell
Set-Location C:\laragon\www
git clone https://github.com/hazzaz01/nafs-smart-hospital.git smart-hospital
Set-Location C:\laragon\www\smart-hospital
git switch main
git pull origin main
```

The project must now be located at:

```text
C:\laragon\www\smart-hospital
```

Confirm that this file exists:

```text
C:\laragon\www\smart-hospital\index.php
```

If Git requests GitHub authentication, ask the repository owner to give your GitHub account access to the repository.

## 4. Create the local configuration files

The real database and license configuration files are intentionally not stored in Git. Create them from the supplied examples:

```powershell
Copy-Item application\config\database.example.php application\config\database.php
Copy-Item application\config\license.example.php application\config\license.php
```

The default database settings are:

| Setting | Value |
|---|---|
| Host | `localhost` |
| Database | `smart_hospital` |
| Username | `root` |
| Password | Empty |

These normally match a new Laragon installation. If the local MySQL `root` user has a password, open `application/config/database.php` and update its `password` value.

Do not commit `application/config/database.php` or `application/config/license.php` because they are machine-specific and may contain secrets.

## 5. Create and import the database

### Recommended: import with HeidiSQL

1. In Laragon, open **Menu > MySQL > HeidiSQL**.
2. Open the default Laragon connection.
3. Right-click the server and select **Create new > Database**.
4. Enter `smart_hospital` as the database name.
5. Select `utf8_general_ci` as the collation.
6. Select the new `smart_hospital` database.
7. Open **File > Run SQL file**.
8. Select this file from the cloned project:

```text
backup\database_backup\db_ver_5.0_2025-05-15_17-56-35.sql
```

9. Wait for the import to finish without errors.

The SQL file is approximately 36 MB. HeidiSQL is recommended because phpMyAdmin may reject it because of upload-size or execution-time limits.

### Alternative: import from PowerShell

If the `mysql` command is available in the terminal, run:

```powershell
mysql -u root -e "CREATE DATABASE IF NOT EXISTS smart_hospital CHARACTER SET utf8 COLLATE utf8_general_ci;"
cmd /c "mysql -u root smart_hospital < backup\database_backup\db_ver_5.0_2025-05-15_17-56-35.sql"
```

If the MySQL account has a password, use `mysql -u root -p` and enter the password when prompted.

## 6. Correct local paths and default logos

The database backup was created on a different server. Its old `base_url`, `folder_path`, and uploaded logo filenames will not work on a new computer.

Open a HeidiSQL query tab for the `smart_hospital` database and run:

```sql
UPDATE sch_settings
SET base_url = 'http://localhost/smart-hospital/',
    folder_path = 'C:/laragon/www/smart-hospital/',
    image = '0.png',
    mini_logo = '0mini_logo.png',
    app_logo = '0app_logo.png'
WHERE id = 1;
```

Keep the trailing slash in both path values. These filenames point to logo files that are included in the repository. A different hospital logo can be uploaded later from the application settings page.

If the project was cloned to a different folder, replace `C:/laragon/www/smart-hospital/` and the URL with the actual folder and URL.

## 7. Run the database migrations

The imported database does not contain the latest eye-care modules. Apply migrations 125 through 131.

1. Open `application/config/migration.php`.
2. Change:

```php
$config['migration_enabled'] = false;
```

to:

```php
$config['migration_enabled'] = true;
```

3. Confirm that the configured version is:

```php
$config['migration_version'] = 131;
```

4. Open this URL once:

```text
http://localhost/smart-hospital/migrate
```

5. Confirm that the page displays:

```text
Database updated successfully.
```

6. Immediately change `migration_enabled` back to `false`.

Do not leave migrations enabled after completing the installation.

## 8. Set known local login passwords

The imported database contains hashed passwords. To set predictable development credentials, run this query in HeidiSQL:

```sql
UPDATE staff
SET password = '$2y$10$.cKhPp..i25uxVFvK.mteOID0jJx6kfUJJIkO2eU21QtCSbUvLBCy'
WHERE id IN (1, 2);
```

Local development accounts:

| Role | Username | Password |
|---|---|---|
| Super Admin | `superadmin@gmail.com` | `Admin@123` |
| Doctor | `ajay@gmail.com` | `Admin@123` |

These credentials are for local development only. Never use them in production.

## 9. Open and test the application

Open the login page:

```text
http://localhost/smart-hospital/site/login
```

Log in with one of the local accounts above.

Verify these custom modules:

```text
http://localhost/smart-hospital/admin/eyeexam
http://localhost/smart-hospital/admin/glaucoma
http://localhost/smart-hospital/admin/drscreening
http://localhost/smart-hospital/admin/eyesurgery
http://localhost/smart-hospital/admin/ocularimaging
```

Also confirm that the header logo appears. If it does not, repeat step 6 and verify that these files exist:

```text
uploads\hospital_content\logo\0.png
uploads\hospital_content\logo\0mini_logo.png
uploads\hospital_content\logo\0app_logo.png
```

## 10. Writable directories

Laragon normally provides the required write permissions automatically. The application must be able to write to:

```text
application\cache
application\logs
application\sessions
temp
uploads
```

If an upload, log, cache, or session operation fails, give the current Windows user **Modify** permission on those directories.

## Getting future code updates

Before updating, open PowerShell in the project directory:

```powershell
Set-Location C:\laragon\www\smart-hospital
git status
```

### When there are no local changes

```powershell
git switch main
git pull origin main
```

### When there are local uncommitted changes

Save them temporarily, update the code, and restore them:

```powershell
git stash push -u -m "local changes before update"
git switch main
git pull origin main
git stash pop
```

If `git stash pop` reports conflicts, do not delete or overwrite the conflicting files. Resolve the conflicts carefully or ask the repository owner for help.

After pulling new code, check whether `application/config/migration.php` contains a newer migration version. If it does, enable migrations temporarily, open `/migrate` once, confirm success, and disable migrations again.

`git pull` does not replace the ignored local database configuration files. It also does not automatically modify the local MySQL database.

## Troubleshooting

### `connection failed: Unknown database 'smart_hospital'`

Create the `smart_hospital` database and import the supplied SQL file from step 5.

### `Access denied for user 'root'@'localhost'`

The password in `application/config/database.php` does not match the local MySQL password. Update the configuration with the correct password.

### The home page works, but other pages return 404

Apache URL rewriting is not active. Confirm that Apache's `mod_rewrite` module is enabled and that `.htaccess` overrides are allowed. Laragon normally enables this automatically.

### The page has no CSS or JavaScript

Confirm that the folder and URL are exactly:

```text
C:\laragon\www\smart-hospital
http://localhost/smart-hospital/
```

Then repeat the `sch_settings` update from step 6.

### The logo or an uploaded image is broken

Open the image URL directly in the browser. If it returns an HTML page instead of an image, the configured filename does not exist. Repeat step 6 for the default logos or re-upload the image through **Setup > Settings**.

### `/migrate` reports an error

Confirm all of the following:

- The SQL backup was imported first.
- `migration_enabled` is temporarily set to `true`.
- `migration_version` is `131`.
- The MySQL user can create and alter tables.

### Blank page or HTTP 500

Temporarily change this setting in `application/config/config.php`:

```php
$config['log_threshold'] = 1;
```

Reload the failing page and inspect the newest file under `application/logs`. Restore `log_threshold` to `0` after diagnosing the error.

## Final checklist

- [ ] Git, Laragon, PHP, Apache, and MySQL are installed.
- [ ] Apache and MySQL are running.
- [ ] The latest `main` branch is in `C:\laragon\www\smart-hospital`.
- [ ] `application/config/database.php` exists.
- [ ] `application/config/license.php` exists.
- [ ] The `smart_hospital` database was imported.
- [ ] `sch_settings` contains the correct local URL and folder path.
- [ ] Migrations completed through version 131.
- [ ] `migration_enabled` was changed back to `false`.
- [ ] The login page opens.
- [ ] The local development credentials work.
- [ ] CSS, JavaScript, logos, and uploaded images load correctly.
- [ ] The custom eye-care modules open without errors.

