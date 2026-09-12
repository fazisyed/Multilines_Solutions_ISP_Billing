ISP Billing Software

A robust, full-featured ISP Management & Billing System built with Vanilla PHP, optimized for performance and ease of use. This application handles everything from customer onboarding to monthly automated billing, report generation, and complaint management.

## 🚀 Key Features

### 1. 📊 Dynamic Dashboard
*   **Real-time Analytics**: View instant stats on Active Customers, Monthly Collected Revenue, and Total Outstanding Dues.
*   **Interactive Charts**: 
    *   **Revenue Growth**: Visual breakdown of collections.
    *   **Area Distribution**: See which areas have the most customers.
    *   **Status Overview**: Quick glance at Active vs. Inactive/Disabled users.
*   **Action Center**: Quick links to generate dummy data or perform bulk actions.

### 2. 👥 Comprehensive Customer Management
*   **Unified Profile**: A persistent, 2-column layout for managing Personal, Address, Technical, and Billing info in one view.
*   **Advanced List View**:
    *   **Selective Columns**: Use the "Columns" picker to show/hide specific data points (e.g., hide IP/Mac for billing staff). Auto-saves your preference.
    *   **Search & Sort**: Filter by Name, Mobile, or Area instantly. Sort by any column.
    *   **Status Workflow**: Manually or automatically transition users between 'Active', 'Inactive', 'Temporary Disable', etc.

### 3. � Robust Import & Export Suite
*   **Intelligent Excel Import**:
    *   **Interactive Mapping**: Drag-and-drop or select dropdowns to map your Excel headers to database fields.
    *   **Duplicate Protection**: Automatically detects existing customers by Mobile Number.
    *   **Smart Update**: If a customer exists, it *updates* their missing info instead of creating a duplicate or error.
*   **Universal Export**:
    *   Export Any Table (All Customers, Due List, Complain List, etc.) to **Excel**, **CSV**, or **PDF**.
    *   **PDF Customization**: Automatically excludes "Action" buttons and hidden columns for clean, printable reports.

### 4. 💰 Automated Billing & Reporting
*   **One-Click Sync**: The "Sync Balances" feature automatically calculates dues for all customers based on their connection date, monthly rent, and payment history.
*   **Detailed Reports**:
    *   **Due List**: See exactly who owes money, filtered by Area or District.
    *   **Collection Report**: Track daily/monthly collections by employee or area.
    *   **Customer Summary**: A ledger-style view for financial auditing.
    *   **Inactive/Expired List**: Track users who have left or whose accounts have expired.

### 5. 🎫 Complaint Management
*   **Ticketing System**: Log customer issues with priorities and assign them to specific employees.
*   **Status Tracking**: Monitor tickets from 'Pending' to 'Resolved'.
*   **Printable Lists**: Generate work orders or daily complaint lists for field technicians.

---

## 🛠️ Installation & Setup

### Prerequisites
*   **PHP 7.4 or higher**
*   **MySQL / MariaDB**
*   **Apache Server** (e.g., XAMPP, WAMP, Laragon) with `mod_rewrite` enabled.

### Step-by-Step Guide

1.  **Clone/Download**:
    Extract the project files into your web root (e.g., `C:\xampp\htdocs\HK ISP Billing`).

2.  **Database Setup**:
    *   Open your Database Manager (e.g., phpMyAdmin).
    *   Create a new database named `hk_isp_billing`.
    *   Import the `database/schema.sql` file to create the necessary tables.
    *   *(Optional)* Import `database/seeds.sql` if you want sample data.

3.  **Configuration**:
    *   Open `config/database.php`.
    *   Update the credentials if your MySQL password is not empty:
        ```php
        define('DB_HOST', 'localhost');
        define('DB_NAME', 'hk_isp_billing');
        define('DB_USER', 'root');
        define('DB_PASS', ''); // Your password here
        ```

4.  **Launch**:
    *   Open your browser and visit: `http://localhost/HK_ISP_Billing/`
    *   The application should load the Dashboard immediately.

---

## � Project Structure

```text
/
├── app/
│   ├── Controllers/     # Logic for Pages, Customers, Reports
│   ├── Core/            # Router, Database Wrapper, Helper functions
├── config/              # Database connection settings
├── public/
│   ├── css/             # Global styles (style.css)
│   ├── js/              # Chart.js and custom scripts
│   └── index.php        # Entry point
├── resources/
│   └── views/           # HTML Templates (organized by feature)
│       ├── customers/   # Customer list, create, edit
│       ├── reports/     # Due list, Summary, Collections
│       ├── complains/   # Ticketing system
│       └── partials/    # Header, Footer, Sidebar
└── database/            # SQL schemas and seeds
```

## 🧩 Usage Tips

*   **Column Visibility**: If your table looks cluttered, click the **"Columns"** button (top-right of tables) to uncheck fields you don't need. The system remembers your choice!
*   **Printing**: All pages have a "Print" button that is optimized to save ink and paper, hiding navigation bars and buttons.
*   **Importing**: When importing Excel files, ensure your first row contains headers. The system will help you match them.

---

## ⚖️ License
Proprietary Software. Internal Use Only.
