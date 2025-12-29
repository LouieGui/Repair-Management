# 🧪 Postman Testing Guide for Repair Management System

This guide will help you test the web endpoints using Postman. The system currently uses web routes with Blade views.

## 🔗 Base URL
```
http://127.0.0.1:8000
```

## 📋 Prerequisites
1. **Postman** installed on your computer
2. **Laravel development server** running (`php artisan serve`)
3. **Database** properly set up and migrated

## 🚀 Getting Started

### 1. Set Up Postman Environment
Create a new environment in Postman with these variables:

| Variable | Initial Value | Description |
|----------|---------------|-------------|
| `base_url` | `http://127.0.0.1:8000` | Web application base URL |

### 2. Run Database Migrations
```bash
php artisan migrate
```
## Run Database Migrations with seed
```bash
php artisan migrate:fresh --seed
```

## Check available routes
```bash
php artisan route:list
```