# Boilerplate Laravel 7.0 + AdminLTE 3.0.4 
A blank project with all you need to start a new application using Laravel and AdminLTE.

Use the file config/template.php to customize menu, color, version, etc.

### Features
- [x] Basic Bootstrap Template (Site)
- [x] AdminLTE Template (Admin)
- [X] Multi-language Support
- [x] User Authentication
- [X] User Profile (Basic)
- [X] Access Control List (Basic ACL)
- [X] CURD to manage Users
- [X] CURD to manage Roles and Permissions
- [X] Basic CRUD Example
- [X] Social Login

### Site Preview
![preview-site](preview-site.jpg)

### Admin Preview
![preview-admin](preview-admin.jpg)
![preview-crud-list](preview-crud-list.jpg)
![preview-crud-new](preview-crud-new.jpg)

### Instalation
Make a copy or rename ".env.example" file to ".env" and configure some variables before.

```
composer install
php artisan migrate
```

### Run
```
php artisan serve
```