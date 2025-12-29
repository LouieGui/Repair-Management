## **Summary:** 

**Repair Management System** is a comprehensive web-based application built with **Laravel ** designed to streamline and manage the entire lifecycle of device repair operations. This system serves repair shops, service centers, and technical support departments by providing an integrated platform for tracking repair requests, managing technicians, handling customer information, processing quotations, managing parts inventory, and ensuring accountability through audit trails.

**Core Purpose**
The system aims to digitize and optimize repair workflows from initial customer request through diagnosis, repair execution, quality testing, payment processing, to final delivery—including post-repair warranty and return management.

---

# **Database Schema**

### **1. Users Table**
```sql
CREATE TABLE users (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    role ENUM('admin', 'technician', 'receptionist', 'customer'),
    password VARCHAR(255),
    is_active BOOLEAN DEFAULT true,
    deleted_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### **2. Customers Table**
```sql
CREATE TABLE customers (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    fullname VARCHAR(255) NOT NULL,
    unique_id VARCHAR(100),
    contact VARCHAR(20) NOT NULL,
    email VARCHAR(255),
    address TEXT,
    is_active BOOLEAN DEFAULT true,
    deleted_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### **3. Devices Table**
```sql
CREATE TABLE devices (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    customer_id BIGINT NOT NULL,
    brand VARCHAR(100) NOT NULL,
    model VARCHAR(100) NOT NULL,
    serial_number VARCHAR(100),
    imei VARCHAR(20),
    device_warranty_status ENUM('active', 'expired', 'none') DEFAULT 'none',
    device_password VARCHAR(100),
    is_active BOOLEAN DEFAULT true,
    deleted_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (customer_id) REFERENCES customers(id)
);
```

### **4. Repairs Table**
```sql
CREATE TABLE repairs (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    
    -- Customer & Device
    customer_id BIGINT NOT NULL,
    device_id BIGINT NOT NULL,
    technician_id BIGINT,
    
    -- Customer reported issue
    reported_issue TEXT NOT NULL,
    accessories TEXT,
    
    -- Status
    status ENUM(
        'received',
        'diagnosing',
        'waiting_approval',
        'approved',
        'waiting_parts',
        'in_progress',
        'testing',
        'ready_for_pickup',
        'completed',
        'cancelled'
    ) DEFAULT 'received',
    
    -- Technician details
    diagnosis TEXT,
    technician_notes TEXT,
    
    -- Dates
    date_received DATE,
    estimated_completion_date DATE,
    date_completed DATE,
    
    -- Warranty fields
    warranty_days INT DEFAULT 90, -- Warranty period in days for THIS repair
    warranty_until_date DATE, -- Auto-calculated: date_completed + warranty_days
    
    -- Financial
    total_amount DECIMAL(10, 2) DEFAULT 0,
    paid_amount DECIMAL(10, 2) DEFAULT 0,
    
    -- Standard fields
    is_active BOOLEAN DEFAULT true,
    deleted_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (customer_id) REFERENCES customers(id),
    FOREIGN KEY (device_id) REFERENCES devices(id),
    FOREIGN KEY (technician_id) REFERENCES users(id)
);
```

### **5. Returns Table**
```sql
CREATE TABLE returns (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    repair_id BIGINT NOT NULL, -- The ORIGINAL repair that's being returned
    new_repair_id BIGINT, -- The NEW repair created for fixing the returned device
    return_type ENUM('warranty_claim', 'recheck', 'different_issue') DEFAULT 'recheck',
    
    -- Return details
    return_date DATE,
    return_reason TEXT NOT NULL,
    is_same_issue BOOLEAN DEFAULT true,
    
    -- Warranty claim info
    is_under_warranty BOOLEAN DEFAULT false,
    
    -- Return handling
    received_by BIGINT, -- Staff who received the return
    notes TEXT,
    
    -- Standard fields
    is_active BOOLEAN DEFAULT true,
    deleted_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (repair_id) REFERENCES repairs(id),
    FOREIGN KEY (new_repair_id) REFERENCES repairs(id),
    FOREIGN KEY (received_by) REFERENCES users(id)
);
```

### **6. Parts Table** (Simple Inventory)
```sql
CREATE TABLE parts (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    sku VARCHAR(100),
    description TEXT,
    purchase_price DECIMAL(10, 2),
    selling_price DECIMAL(10, 2),
    stock_quantity INT DEFAULT 0,
    is_active BOOLEAN DEFAULT true,
    deleted_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### **7. Quotation Parts Table** (Quotation Items)
```sql
CREATE TABLE repair_parts (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    repair_id BIGINT NOT NULL,
    part_id BIGINT,
    custom_part_name VARCHAR(255),
    quantity INT DEFAULT 1,
    unit_price DECIMAL(10, 2),
    total_price DECIMAL(10, 2),
    is_approved BOOLEAN DEFAULT false,
    notes TEXT,
    is_active BOOLEAN DEFAULT true,
    deleted_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (repair_id) REFERENCES repairs(id),
    FOREIGN KEY (part_id) REFERENCES parts(id)
);
```

### **8. Payments Table**
```sql
CREATE TABLE payments (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    repair_id BIGINT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    payment_method ENUM('cash', 'card', 'online'),
    status ENUM('pending', 'completed', 'failed'),
    transaction_id VARCHAR(255),
    notes TEXT,
    is_active BOOLEAN DEFAULT true,
    deleted_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (repair_id) REFERENCES repairs(id)
);
```

### **9. Activity Log Table** (Centralized Logging)
```sql
CREATE TABLE audit_trails (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    
    -- Who performed the action
    user_id BIGINT,
    user_type VARCHAR(50), -- 'admin', 'technician', 'customer', 'system'
    
    -- What was affected
    audit_table VARCHAR(50) NOT NULL, -- Table name:
    
    -- Action details
    event VARCHAR(50) NOT NULL, -- 'created', 'updated', 'deleted', 'restored', 'status_changed'
    old_values TEXT, -- JSON string of old values
    new_values TEXT, -- JSON string of new values
    
    -- date created
    audited_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

---

## **📊 Table & Purpose:**

| **Table** | **Purpose** | **Essential?** |
|-----------|------------|----------------|
| 1. users | Staff/Technicians | ✅ Yes |
| 2. customers | Customer info | ✅ Yes |
| 3. devices | Customer devices | ✅ Yes |
| 4. repairs | Main repair records | ✅ Yes |
| 5. returns | Returned records | ✅ Yes |
| 6. parts | Inventory | ✅ Yes |
| 7. repair_parts | Quotation items | ✅ Yes |
| 8. payments | Financial tracking | ✅ Yes |
| 9. audit_trails | System audit trail | ⚠ Optional |


### **What Each Table Has:**
1. `id` - Primary key
2. Foreign keys (if needed)
3. Business fields
4. `is_active` - if active
5. `deleted_at` - Soft delete timestamp
6. `created_at` - Auto timestamp
7. `updated_at` - Auto update

---

## 🎯 Laravel Model Setup:

### Base Model Trait (app/Traits/HasCommonFields.php)
```php
namespace App\Traits;

trait HasCommonFields
{
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }
    
    public function scopeNotDeleted($query)
    {
        return $query->whereNull('deleted_at');
    }
    
    public function softDelete()
    {
        $this->update(['deleted_at' => now()]);
    }
    
    public function restore()
    {
        $this->update(['deleted_at' => null]);
    }
    
    public function toggleActive()
    {
        $this->update(['is_active' => !$this->is_active]);
    }
}
```

### Example Model (app/Models/Repair.php)
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasCommonFields;

class Repair extends Model
{
    use SoftDeletes, HasCommonFields;
    
    protected $fillable = [
        'customer_id',
        'device_id',
        'technician_id',
        'reported_issue',
        'accessories',
        'status',
        'diagnosis',
        'technician_notes',
        'date_received',
        'estimated_completion_date',
        'date_completed',
        'date_returned',
        'total_amount',
        'paid_amount',
        'is_active'
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'date_received' => 'date',
        'estimated_completion_date' => 'date',
        'date_completed' => 'date',
        'date_returned' => 'date',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];
    
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    
    public function device()
    {
        return $this->belongsTo(Device::class);
    }
    
    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
    
    public function parts()
    {
        return $this->hasMany(RepairPart::class);
    }
    
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    
    public function statusHistory()
    {
        return $this->hasMany(RepairStatusHistory::class);
    }
}
```

---

# **🔄 Process Flow:**

```
1. CUSTOMER REGISTRATION/CHECK
   - Check if customer exists
   - If new: Create customer record
   - Verify contact details
   ↓

2. DEVICE REGISTRATION/CHECK
   - Check if device exists for customer
   - If new device: Create device record
   - Record brand, model, serial, warranty status
   ↓

3. CREATE REPAIR ORDER
   - Create repair record
   - Link customer & device
   - Record reported issue & accessories
   - Status: 'received'
   - Set date_received = today
   ↓

4. INITIAL ASSESSMENT & DIAGNOSIS
   - Technician examines device
   - Update diagnosis field
   - Status: 'diagnosing'
   ↓

5. CREATE QUOTATION
   - Add parts needed to repair_parts table
   - Use parts from inventory OR custom parts
   - Set unit prices & calculate totals
   - Update repair total_amount
   ↓

6. CUSTOMER APPROVAL
   - Present quotation to customer
   - If approved: Status → 'approved'
   - If not approved: Status → 'cancelled'
   - If changes needed: Update repair_parts
   ↓

7. REPAIR EXECUTION
   - Assign technician (if not already)
   - Status: 'in_progress'
   - Technician performs repair
   - Update technician_notes as needed
   ↓

8. PARTS PROCUREMENT (if needed)
   - If waiting_parts: Status → 'waiting_parts'
   - Order/request parts
   - When parts arrive: Status → 'in_progress'
   ↓

9. QUALITY TESTING
   - After repair: Status → 'testing'
   - Perform quality checks
   - If passes: Status → 'ready_for_pickup'
   - If fails: Return to 'in_progress'
   ↓

10. CUSTOMER NOTIFICATION & PICKUP
    - Notify customer device is ready
    - Status: 'ready_for_pickup'
    - Set date_completed = today
    ↓

11. PAYMENT PROCESSING
    - Create payment record
    - Record amount, method, status
    - Update repair.paid_amount
    - If full payment: Status → 'completed'
    ↓

12. DEVICE RETURN/HANDOVER
    - Customer picks up device
    - Verify payment
    - Update repair (if needed)
    - Provide warranty details
    - Set warranty_until_date = date_completed + warranty_days
    ↓

13. WARRANTY/RETURN HANDLING (if occurs)
    - Customer returns with issue
    - Create returns record
    - Link to original repair_id
    - Check warranty status
    - If under warranty: warranty_covered = true
    ↓

14. CREATE NEW REPAIR FOR RETURNED DEVICE
    - Create new repair record
    - Link to returns.new_repair_id
    - If warranty covered: total_amount = 0
    - Repeat steps 4-12
    ↓

15. AUDIT TRAIL (Throughout)
    - Log all significant actions in audit_trails
    - Track who did what and when
    - Record changes to important fields
```
---

# **Backend Coding Practice**

## 1. Backend Mindset (Before Code)

A maintainable backend follows these principles:

### Core Principles

* **Separation of Concerns** – each layer has ONE job
* **Thin Controllers** – controllers should not contain business logic
* **Reusable Business Logic** – logic lives in Services
* **Database Logic Isolated** – handled by Models / Repositories
* **API-first mindset** – backend does not care about UI
* **Consistent response format**
* **Versioned APIs**

Laravel already supports this philosophy — you just need to structure it properly.

---

## 2. Recommended Laravel Backend Folder Structure

This is a **real-world scalable structure**, not academic fluff.

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Api/
│   │   │   ├── V1/
│   │   │   │   ├── RepairController.php
│   │   │   │   ├── CustomerController.php
│   │   │   │   └── DeviceController.php
│   ├── Requests/
│   │   ├── Repair/
│   │   │   ├── StoreRepairRequest.php
│   │   │   └── UpdateRepairRequest.php
│   ├── Resources/
│   │   └── RepairResource.php
│
├── Models/
│   ├── Repair.php
│   ├── Customer.php
│   ├── Device.php
│   ├── Technician.php
│
├── Services/
│   ├── RepairService.php
│   └── QuotationService.php
│
├── Repositories/
│   ├── RepairRepository.php
│   └── CustomerRepository.php
│
├── Enums/
│   └── RepairStatus.php
│
├── Exceptions/
│   └── ApiException.php
│
routes/
├── api.php
```

---

## 3. Database Layer (Models + Relationships)

Based on your ERD (Repair, Customer, Device, etc.)

### Example: `Repair.php`

```php
class Repair extends Model
{
    protected $fillable = [
        'customer_id',
        'device_id',
        'tech_id',
        'status',
        'problem_details',
        'technician_notes',
        'date_received',
        'date_returned',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function technician()
    {
        return $this->belongsTo(Technician::class, 'tech_id');
    }

    public function quotations()
    {
        return $this->hasMany(PartsQuotation::class);
    }

    public function returns()
    {
        return $this->hasMany(ReturnModel::class);
    }
}
```

✔ Clean
✔ Scalable
✔ Easy to extend

---

## 4. Repository Layer (Database Access)

**Why?**
So your business logic doesn’t care **how** data is stored.

### `RepairRepository.php`

```php
class RepairRepository
{
    public function create(array $data)
    {
        return Repair::create($data);
    }

    public function findById($id)
    {
        return Repair::with(['customer', 'device', 'technician'])->findOrFail($id);
    }

    public function update(Repair $repair, array $data)
    {
        $repair->update($data);
        return $repair;
    }
}
```

✔ Easier testing
✔ Replace DB logic later if needed
✔ Cleaner services

---

## 5. Service Layer (Business Logic)

**This is the heart of your backend**

### `RepairService.php`

```php
class RepairService
{
    public function __construct(
        protected RepairRepository $repairRepo
    ) {}

    public function createRepair(array $data)
    {
        // Business rules live here
        $data['status'] = RepairStatus::RECEIVED;

        return $this->repairRepo->create($data);
    }

    public function updateStatus($repairId, string $status)
    {
        $repair = $this->repairRepo->findById($repairId);
        $repair->status = $status;
        $repair->save();

        return $repair;
    }
}
```

✔ Controllers stay thin
✔ Logic is reusable (API, jobs, CLI)

---

## 6. Request Validation Layer

Never validate inside controllers.

### `StoreRepairRequest.php`

```php
class StoreRepairRequest extends FormRequest
{
    public function rules()
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'device_id'   => 'required|exists:devices,id',
            'problem_details' => 'required|string',
        ];
    }
}
```

✔ Centralized validation
✔ Clean controllers
✔ Auto error response

---

## 7. Controller Layer (Very Thin)

### `RepairController.php`

```php
class RepairController extends Controller
{
    public function __construct(
        protected RepairService $repairService
    ) {}

    public function store(StoreRepairRequest $request)
    {
        $repair = $this->repairService->createRepair($request->validated());

        return new RepairResource($repair);
    }

    public function show($id)
    {
        return new RepairResource(
            Repair::with(['customer','device'])->findOrFail($id)
        );
    }
}
```

✔ Easy to read
✔ Easy to maintain
✔ Easy to refactor

---

## 8. API Resource Layer (Response Shape)

### `RepairResource.php`

```php
class RepairResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'problem_details' => $this->problem_details,
            'customer' => [
                'id' => $this->customer->id,
                'name' => $this->customer->fullname,
            ],
            'device' => [
                'brand' => $this->device->brand,
                'model' => $this->device->model,
            ],
            'dates' => [
                'received' => $this->date_received,
                'returned' => $this->date_returned,
            ],
        ];
    }
}
```

✔ Stable API contracts
✔ Frontend-friendly
✔ Version-safe

---

## 9. API Routes (Versioned)

### `routes/api.php`

```php
Route::prefix('v1')->group(function () {
    Route::apiResource('repairs', RepairController::class);
});
```

✔ Supports future versions
✔ No breaking changes

---

## 10. Enums for Status (Very Important)

### `RepairStatus.php`

```php
enum RepairStatus: string
{
    case RECEIVED = 'received';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case RETURNED = 'returned';
}
```

✔ No magic strings
✔ Prevents bugs
✔ Self-documenting

---

# **Branching Strategy**

### `main`

```
main = working system
```

---

### `feature/*`

* Used for **new features or modules**
* Branch off from `main`
* Merged back to `main` when complete

**Naming format**

```
feature/<short-description>
```

**Samples**

```
feature/customer-table
feature/repair-module
feature/parts-quotation
```

---

### `fix/*`

* Used for **bug fixes**
* Branch off from `main`
* Merged back to `main` after fix is verified

**Naming format**

```
fix/<short-description>
```

**Samples**

```
fix/repair-status-bug
fix/quotation-total-bug
```

---

## Commit Message Convention

This project follows **Conventional Commits** for clarity, automation, and clean history.

### Commit Types

```
feat     – A new feature
fix      – A bug fix
refactor – Code change that neither fixes a bug nor adds a feature
style    – Code style changes (formatting, spacing, no logic change)
docs     – Documentation only changes
test     – Adding or updating tests
chore    – Maintenance tasks (configs, deps, cleanup)
perf     – Performance improvements
ci       – CI/CD related changes (pipelines, GitHub Actions)
build    – Build system or dependency changes
revert   – Revert a previous commit
```

---

### Commit Message Format

```
<type>(scope): short description
```

---
