# 🗃️ Database Connection & Management Guide

## **1. Database Configuration**

### **Environment Setup**
The Repair Management System uses MySQL as its database. Here's how to configure and connect to the database:

### **Configuration File**
The database configuration is located in `.env` file. Here are the default settings:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=repair_management
DB_USERNAME=root
DB_PASSWORD=
```

### **Setting Up Your Database**

#### **Option 1: Using MySQL Workbench**
1. **Install MySQL Workbench** from [https://www.mysql.com/products/workbench/](https://www.mysql.com/products/workbench/)
2. **Create a new connection**:
   - Hostname: `127.0.0.1`
   - Port: `3306`
   - Username: `root`
   - Password: (leave empty or use your MySQL password)
3. **Create the database**:
   - Click on the "Create a new schema" button
   - Name: `repair_management`
   - Charset: `utf8mb4`
   - Collation: `utf8mb4_unicode_ci`
   - Click "Apply"

#### **Option 2: Using Command Line**
```bash
# Connect to MySQL
mysql -u root -p

# Create database
CREATE DATABASE repair_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Create user (optional)
CREATE USER 'repair_user'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON repair_management.* TO 'repair_user'@'localhost';
FLUSH PRIVILEGES;
```

#### **Option 3: Using phpMyAdmin**
1. Access phpMyAdmin (usually at `http://localhost/phpmyadmin`)
2. Click on "New" in the left sidebar
3. Enter database name: `repair_management`
4. Collation: `utf8mb4_unicode_ci`
5. Click "Create"

## **2. Running Migrations**

After setting up your database, run the migrations to create all tables:

```bash
php artisan migrate
```

This will create all 9 tables:
- `users` - Staff and customer accounts
- `customers` - Customer information
- `devices` - Customer devices
- `repairs` - Repair records
- `returns` - Returned devices
- `parts` - Inventory parts
- `repair_parts` - Quotation items
- `payments` - Financial transactions
- `audit_trails` - System audit logs

## **3. Running Seeders**

To populate your database with sample data:

```bash
php artisan db:seed
```

This will:
- Create admin, technician, receptionist, and customer users
- Add sample customers with contact information
- Create sample devices for each customer
- Add inventory parts with pricing
- Create sample repair records with different statuses
- Add repair parts (quotation items)
- Create sample payments
- Add a sample return record
- Generate audit trail entries for all actions

## **4. Viewing the Database**

### **Using MySQL Workbench**
1. Connect to your database
2. Expand the `repair_management` schema
3. Browse tables and view data
4. Use the built-in query editor for custom queries

### **Using Command Line**
```bash
# Connect to the database
mysql -u root -p repair_management

# Show tables
SHOW TABLES;

# View data from a table
SELECT * FROM users LIMIT 10;

# Run custom queries
SELECT r.*, c.fullname, d.brand, d.model
FROM repairs r
JOIN customers c ON r.customer_id = c.id
JOIN devices d ON r.device_id = d.id
WHERE r.status = 'in_progress';
```

### **Using phpMyAdmin**
1. Select the `repair_management` database
2. Click on tables to browse data
3. Use the SQL tab for custom queries
4. Export data as needed

## **5. Common Database Operations**

### **Reset Database (Development Only)**
```bash
php artisan migrate:fresh --seed
```

### **Rollback Migrations**
```bash
php artisan migrate:rollback
```

### **Check Migration Status**
```bash
php artisan migrate:status
```

### **Create Backup**
```bash
mysqldump -u root -p repair_management > repair_management_backup.sql
```

### **Restore Backup**
```bash
mysql -u root -p repair_management < repair_management_backup.sql
```

## **6. Troubleshooting**

### **Connection Issues**
- **Error**: "SQLSTATE[HY000] [2002] Connection refused"
  **Solution**: Make sure MySQL server is running (`sudo service mysql start`)

- **Error**: "Database repair_management doesn't exist"
  **Solution**: Create the database manually as shown above

- **Error**: "Access denied for user 'root'@'localhost'"
  **Solution**: Check your MySQL credentials in `.env` file

### **Migration Issues**
- **Error**: "Class not found"
  **Solution**: Run `composer dump-autoload`

- **Error**: "Syntax error in SQL"
  **Solution**: Check migration files for syntax errors

## **7. Database Credentials**

### **Default Credentials**
- **Admin**: `admin@repairmanagement.com` / `admin123`
- **Technician 1**: `tech1@repairmanagement.com` / `tech123`
- **Technician 2**: `tech2@repairmanagement.com` / `tech123`
- **Receptionist**: `reception@repairmanagement.com` / `reception123`
- **Customer 1**: `customer1@example.com` / `customer123`
- **Customer 2**: `customer2@example.com` / `customer123`

## **8. Database Schema Overview**

The database follows a comprehensive schema with:
- **Foreign key relationships** between tables
- **Soft deletes** for data safety
- **Timestamps** for tracking
- **Enums** for status fields
- **Proper indexing** for performance

Refer to `documentation.md` for the complete schema details.

## **9. Security Best Practices**

1. **Never commit `.env` file** to version control
2. **Use strong passwords** for database users
3. **Regular backups** of production data
4. **Limit database user privileges** in production
5. **Use SSL connections** for remote databases
