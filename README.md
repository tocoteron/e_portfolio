# e-Portfolio
This is an e-Portfolio, teachers and students can learn interactively by using this system.

## Getting Started

### Prerequisites
- Apache2
- PHP7
- MySQL15

### Installing
1. Create database for e-Portfolio on MySQL server.
    1. `mysql -u user_name -p`, e.g., `mysql -u root -p`
    2. `CREATE DATABASE e-portfolio;`
    3. `USE e-portfolio;`
    4. `source ./database_schema.sql`
    5. done.
2. Create `./database_config.php`, settings file of the database.
    <?
    $db_host = '127.0.0.1';
    $db_name = 'e-portfolio';
    $db_user = 'user_name';
    $db_password = 'user_password';
3. Move this repository in Apache document root.

### Usage
1. Connect to `http://127.0.0.1/e_portfolio`.
2. Login as teacher by using below account.
- User ID : teacher
- Password: rehcaet
3. Change password, `php ./password_update.php teacher new_password`
