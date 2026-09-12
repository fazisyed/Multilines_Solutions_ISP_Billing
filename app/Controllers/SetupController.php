<?php
namespace App\Controllers;

use App\Core\Controller;
use Database;
use PDO;

/**
 * SetupController
 * * Handles general application setup, including package, merchant, OLT, address, form configuration, payment settings, SMS setup, and WhatsApp setup.
 */
class SetupController extends Controller
{
    /**
     * SetupController constructor.
     */
    public function __construct()
    {
        $this->db = (new Database())->getConnection();
    }

    /**
     * Handles package setup view and operations.
     * * @return void
     */
    public function package()
    {
        if (!$this->db) {
            die("Database connection failed. Please check your config/database.php settings.");
        }

        // 1. Auto-Migration (Fix for missing tables)
        try {
            $this->db->exec("CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                display_name VARCHAR(255) NOT NULL,
                username VARCHAR(100) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                role ENUM('Super Admin', 'Admin', 'Employee') DEFAULT 'Employee',
                status ENUM('pending', 'active', 'inactive') DEFAULT 'pending',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )");

            // Seed Super Admin if not exists
            $stmt = $this->db->prepare("SELECT id FROM users WHERE username = 'superadmin'");
            $stmt->execute();
            if (!$stmt->fetch()) {
                $hash = password_hash('superadmin', PASSWORD_DEFAULT);
                $sql = "INSERT INTO users (display_name, username, password, email, role, status) 
                        VALUES ('Super Admin', 'superadmin', ?, 'nayeemibrahim46@gmail.com', 'Super Admin', 'active')";
                $this->db->prepare($sql)->execute([$hash]);
            }

            $this->db->exec("CREATE TABLE IF NOT EXISTS merchants (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )");

            $this->db->exec("CREATE TABLE IF NOT EXISTS packages (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                price DECIMAL(10, 2) NOT NULL,
                description TEXT,
                merchant_id INT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (merchant_id) REFERENCES merchants(id) ON DELETE SET NULL
            )");

            // Seed Merchants if empty
            $count = $this->db->query("SELECT COUNT(*) FROM merchants")->fetchColumn();
            if ($count == 0) {
                $this->db->exec("INSERT INTO merchants (name) VALUES ('HK ISP'), ('Bangla Link'), ('Airtel')");
            }

        } catch (\Exception $e) {
            // Log error or continue
        }

        $message = '';
        $messageType = '';

        // Handle POST Request (Create/Update Package)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $name = $_POST['name'] ?? '';
            $price = $_POST['price'] ?? 0;
            $description = $_POST['description'] ?? '';
            $merchant_id = $_POST['merchant_id'] ?? null;

            if ($name && $price) {
                try {
                    if ($id) {
                        // Update
                        $sql = "UPDATE packages SET name=:name, price=:price, description=:description, merchant_id=:merchant_id WHERE id=:id";
                        $stmt = $this->db->prepare($sql);
                        $stmt->execute([
                            ':name' => $name,
                            ':price' => $price,
                            ':description' => $description,
                            ':merchant_id' => $merchant_id ?: null,
                            ':id' => $id
                        ]);
                        $message = "Package updated successfully!";
                    } else {
                        // Create
                        $sql = "INSERT INTO packages (name, price, description, merchant_id) VALUES (:name, :price, :description, :merchant_id)";
                        $stmt = $this->db->prepare($sql);
                        $stmt->execute([
                            ':name' => $name,
                            ':price' => $price,
                            ':description' => $description,
                            ':merchant_id' => $merchant_id ?: null
                        ]);
                        $message = "Package created successfully!";
                    }
                    $messageType = "success";
                } catch (\PDOException $e) {
                    $message = "Error saving package: " . $e->getMessage();
                    $messageType = "error";
                }
            } else {
                $message = "Name and Price are required.";
                $messageType = "error";
            }
        }

        // Fetch Merchants for the dropdown
        $merchants = $this->db->query("SELECT * FROM merchants ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

        // Fetch Packages for the list
        $sql = "SELECT p.*, m.name as merchant_name 
                FROM packages p 
                LEFT JOIN merchants m ON p.merchant_id = m.id 
                ORDER BY p.id DESC";
        $packages = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        $this->view('setup/package', [
            'title' => 'Package Setup',
            'path' => '/setup/package',
            'merchants' => $merchants,
            'packages' => $packages,
            'message' => $message,
            'messageType' => $messageType
        ]);
    }

    /**
     * Handle OLT Setup view and store operations.
     */
    public function oltSetup()
    {
        try {
            $this->db->exec("CREATE TABLE IF NOT EXISTS olt_setup (
                id INT AUTO_INCREMENT PRIMARY KEY,
                olt_name VARCHAR(255) NOT NULL,
                ip_address VARCHAR(50) NOT NULL,
                port_count INT DEFAULT 8,
                location VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )");
        } catch (\Exception $e) {}

        $olts = $this->db->query("SELECT * FROM olt_setup ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

        $this->view('setup/olt', [
            'title' => 'OLT Setup',
            'path' => '/setup/oltSetup',
            'olts' => $olts
        ]);
    }

    public function storeOlt()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['olt_name'] ?? '';
            $ip = $_POST['ip_address'] ?? '';
            $ports = $_POST['port_count'] ?? 8;
            $location = $_POST['location'] ?? '';

            if ($name && $ip) {
                $stmt = $this->db->prepare("INSERT INTO olt_setup (olt_name, ip_address, port_count, location) VALUES (?, ?, ?, ?)");
                $stmt->execute([$name, $ip, $ports, $location]);
            }
            return $this->redirect('/setup/oltSetup');
        }
    }

    /**
     * Handle Address Setup view and store operations.
     */
    public function addressSetup()
    {
        try {
            $this->db->exec("CREATE TABLE IF NOT EXISTS address_setup (
                id INT AUTO_INCREMENT PRIMARY KEY,
                district VARCHAR(100) NOT NULL,
                thana VARCHAR(100) NOT NULL,
                area VARCHAR(100) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )");
        } catch (\Exception $e) {}

        $addresses = $this->db->query("SELECT * FROM address_setup ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

        $this->view('setup/address', [
            'title' => 'Address Setup',
            'path' => '/setup/addressSetup',
            'addresses' => $addresses
        ]);
    }

    public function storeAddress()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $district = $_POST['district'] ?? '';
            $thana = $_POST['thana'] ?? '';
            $area = $_POST['area'] ?? '';

            if ($district && $area) {
                $stmt = $this->db->prepare("INSERT INTO address_setup (district, thana, area) VALUES (?, ?, ?)");
                $stmt->execute([$district, $thana, $area]);
            }
            return $this->redirect('/setup/addressSetup');
        }
    }

    /**
     * Handle Payment Settings Setup.
     */
    public function paymentSettings()
    {
        try {
            $this->db->exec("CREATE TABLE IF NOT EXISTS payment_settings (
                id INT AUTO_INCREMENT PRIMARY KEY,
                gateway_name VARCHAR(100) NOT NULL,
                api_key VARCHAR(255),
                secret_key VARCHAR(255),
                status ENUM('Active', 'Inactive') DEFAULT 'Active',
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )");

            $count = $this->db->query("SELECT COUNT(*) FROM payment_settings")->fetchColumn();
            if ($count == 0) {
                $this->db->exec("INSERT INTO payment_settings (gateway_name, status) VALUES ('Bkash', 'Inactive'), ('SSLCommerz', 'Inactive')");
            }
        } catch (\Exception $e) {}

        $gateways = $this->db->query("SELECT * FROM payment_settings ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);

        $this->view('setup/payment', [
            'title' => 'Payment Settings Setup',
            'path' => '/setup/paymentSettings',
            'gateways' => $gateways
        ]);
    }

    public function storePaymentSettings()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $apiKey = $_POST['api_key'] ?? '';
            $secretKey = $_POST['secret_key'] ?? '';
            $status = $_POST['status'] ?? 'Inactive';

            if ($id) {
                $stmt = $this->db->prepare("UPDATE payment_settings SET api_key = ?, secret_key = ?, status = ? WHERE id = ?");
                $stmt->execute([$apiKey, $secretKey, $status, $id]);
            }
            return $this->redirect('/setup/paymentSettings');
        }
    }

    /**
     * Handle SMS Setup.
     */
    public function smsSetup()
    {
        try {
            $this->db->exec("CREATE TABLE IF NOT EXISTS sms_settings (
                id INT AUTO_INCREMENT PRIMARY KEY,
                provider_name VARCHAR(100) DEFAULT 'Custom API',
                api_url TEXT,
                sender_id VARCHAR(100),
                api_key VARCHAR(255),
                status ENUM('Active', 'Inactive') DEFAULT 'Inactive',
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )");

            $count = $this->db->query("SELECT COUNT(*) FROM sms_settings")->fetchColumn();
            if ($count == 0) {
                $this->db->exec("INSERT INTO sms_settings (provider_name, status) VALUES ('Default SMS Gateway', 'Inactive')");
            }
        } catch (\Exception $e) {}

        $sms = $this->db->query("SELECT * FROM sms_settings LIMIT 1")->fetch(PDO::FETCH_ASSOC);

        $this->view('setup/sms', [
            'title' => 'SMS Setup',
            'path' => '/setup/smsSetup',
            'sms' => $sms
        ]);
    }

    public function storeSmsSetup()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $provider = $_POST['provider_name'] ?? '';
            $url = $_POST['api_url'] ?? '';
            $sender = $_POST['sender_id'] ?? '';
            $apiKey = $_POST['api_key'] ?? '';
            $status = $_POST['status'] ?? 'Inactive';

            $stmt = $this->db->prepare("UPDATE sms_settings SET provider_name = ?, api_url = ?, sender_id = ?, api_key = ?, status = ? WHERE id = 1");
            $stmt->execute([$provider, $url, $sender, $apiKey, $status]);

            return $this->redirect('/setup/smsSetup');
        }
    }

    // --- WhatsApp Setup Methods ---
    public function whatsappSetup()
    {
        try {
            $this->db->exec("CREATE TABLE IF NOT EXISTS whatsapp_settings (
                id INT AUTO_INCREMENT PRIMARY KEY,
                provider_name VARCHAR(100) DEFAULT 'Meta Cloud API',
                api_url TEXT,
                phone_number_id VARCHAR(100),
                access_token TEXT,
                status ENUM('Active', 'Inactive') DEFAULT 'Inactive',
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )");

            $count = $this->db->query("SELECT COUNT(*) FROM whatsapp_settings")->fetchColumn();
            if ($count == 0) {
                $this->db->exec("INSERT INTO whatsapp_settings (provider_name, status) VALUES ('Meta Cloud API', 'Inactive')");
            }
        } catch (\Exception $e) {}

        $whatsapp = $this->db->query("SELECT * FROM whatsapp_settings LIMIT 1")->fetch(PDO::FETCH_ASSOC);

        $this->view('setup/whatsapp', [
            'title' => 'WhatsApp Setup',
            'path' => '/setup/whatsappSetup',
            'whatsapp' => $whatsapp
        ]);
    }

    public function storeWhatsappSetup()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $provider = $_POST['provider_name'] ?? '';
            $url = $_POST['api_url'] ?? '';
            $phoneId = $_POST['phone_number_id'] ?? '';
            $token = $_POST['access_token'] ?? '';
            $status = $_POST['status'] ?? 'Inactive';

            $stmt = $this->db->prepare("UPDATE whatsapp_settings SET provider_name = ?, api_url = ?, phone_number_id = ?, access_token = ?, status = ? WHERE id = 1");
            $stmt->execute([$provider, $url, $phoneId, $token, $status]);

            return $this->redirect('/setup/whatsappSetup');
        }
    }

    /**
     * Handle Column Preview Setup.
     * * @return void
     */
    public function columnPreview()
    {
        // 1. Auto-Migration for table_settings
        try {
            $this->db->exec("CREATE TABLE IF NOT EXISTS table_settings (
                id INT AUTO_INCREMENT PRIMARY KEY,
                table_name VARCHAR(50) NOT NULL UNIQUE,
                columns_json TEXT NOT NULL,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )");
        } catch (\Exception $e) {
            // Log error
        }

        // 1. Determine which table we are editing
        $allowedTables = ['all_customers', 'pending_customers', 'recent_customers', 'complain_list', 'collection_report', 'customer_summary', 'due_list', 'inactive_list'];
        $currentTable = $_GET['table'] ?? 'all_customers';
        if (!in_array($currentTable, $allowedTables)) {
            $currentTable = 'all_customers';
        }

        $message = '';
        $messageType = '';

        // 2. Handle POST Request (Save Settings)
        if (isset($_GET['action']) && $_GET['action'] === 'reset') {
            $del = $this->db->prepare("DELETE FROM table_settings WHERE table_name = ?");
            $del->execute([$currentTable]);
            $message = "Settings reset to default for " . ucwords(str_replace('_', ' ', $currentTable));
            $messageType = "success";
        }
        elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $columns = $_POST['columns'] ?? [];

            $toSave = [];
            if (is_array($columns)) {
                foreach ($columns as $idx => $val) {
                    if (isset($val['key'])) {
                        $toSave[] = [
                            'key' => $val['key'],
                            'label' => $val['label'] ?? ucfirst(str_replace('_', ' ', $val['key'])),
                            'enabled' => isset($val['enabled']) ? true : false
                        ];
                    }
                }
            }

            if (!empty($toSave)) {
                $json = json_encode($toSave);
                $check = $this->db->prepare("SELECT id FROM table_settings WHERE table_name = ?");
                $check->execute([$currentTable]);
                if ($check->fetch()) {
                    $sql = "UPDATE table_settings SET columns_json = ?, updated_at = NOW() WHERE table_name = ?";
                } else {
                    $sql = "INSERT INTO table_settings (columns_json, table_name) VALUES (?, ?)";
                }
                $this->db->prepare($sql)->execute([$json, $currentTable]);

                $message = "Columns updated successfully for " . ucwords(str_replace('_', ' ', $currentTable));
                $messageType = "success";
            }
        }

        $allPossibleColumns = $this->getColumnDefinitions($currentTable);

        $stmt = $this->db->prepare("SELECT columns_json FROM table_settings WHERE table_name = ?");
        $stmt->execute([$currentTable]);
        $json = $stmt->fetchColumn();

        if ($json) {
            $savedColumns = json_decode($json, true);
            $savedKeys = array_column($savedColumns, 'key');
            foreach ($allPossibleColumns as $defCol) {
                if (!in_array($defCol['key'], $savedKeys)) {
                    $savedColumns[] = $defCol;
                }
            }
        } else {
            $savedColumns = $allPossibleColumns;
        }

        $this->view('setup/column_preview', [
            'title' => 'Column Preview Setup',
            'path' => '/setup/column-preview',
            'allPossibleColumns' => $savedColumns,
            'currentTable' => $currentTable,
            'message' => $message,
            'messageType' => $messageType
        ]);
    }

    /**
     * Handle Customer Form Setup.
     * * @return void
     */
    public function customerForm()
    {
        $sections = $this->db->query("SELECT * FROM customer_form_sections ORDER BY order_index ASC")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($sections as &$section) {
            $stmt = $this->db->prepare("
                SELECT f.*, 
                (SELECT COUNT(*) FROM customer_meta m WHERE m.field_key = f.field_key AND m.field_value IS NOT NULL AND m.field_value != '') as has_data
                FROM customer_form_fields f 
                WHERE f.section_id = ? 
                ORDER BY f.order_index ASC
            ");
            $stmt->execute([$section['id']]);
            $section['fields'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        $this->view('setup/customer_form', [
            'title' => 'Customer Form Setup',
            'path' => '/setup/customer-form',
            'sections' => $sections
        ]);
    }

    /**
     * Save Customer Form Configuration.
     * * @return void
     */
    public function saveCustomerForm()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || !isset($data['sections'])) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
            return;
        }

        try {
            $this->db->beginTransaction();

            foreach ($data['sections'] as $sIndex => $sData) {
                if (isset($sData['id']) && $sData['id'] > 0) {
                    $stmt = $this->db->prepare("UPDATE customer_form_sections SET name = ?, order_index = ? WHERE id = ?");
                    $stmt->execute([$sData['name'], $sIndex, $sData['id']]);
                    $sectionId = $sData['id'];
                } else {
                    $stmt = $this->db->prepare("INSERT INTO customer_form_sections (name, order_index) VALUES (?, ?)");
                    $stmt->execute([$sData['name'], $sIndex]);
                    $sectionId = $this->db->lastInsertId();
                }

                if (isset($sData['fields'])) {
                    foreach ($sData['fields'] as $fIndex => $fData) {
                        if (isset($fData['id']) && $fData['id'] > 0) {
                            $stmt = $this->db->prepare("UPDATE customer_form_fields SET section_id = ?, label = ?, placeholder = ?, type = ?, required = ?, is_visible = ?, order_index = ?, options = ? WHERE id = ?");
                            $stmt->execute([
                                $sectionId,
                                $fData['label'],
                                $fData['placeholder'] ?? null,
                                $fData['type'],
                                $fData['required'] ? 1 : 0,
                                $fData['is_visible'] ? 1 : 0,
                                $fIndex,
                                isset($fData['options']) ? json_encode($fData['options']) : null,
                                $fData['id']
                            ]);
                        } else {
                            $stmt = $this->db->prepare("INSERT INTO customer_form_fields (section_id, field_key, label, placeholder, type, required, is_visible, order_index, options) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                            $stmt->execute([
                                $sectionId,
                                (!empty($fData['field_key'])) ? $fData['field_key'] : 'custom_' . bin2hex(random_bytes(4)) . '_' . $fIndex,
                                $fData['label'],
                                $fData['placeholder'] ?? null,
                                $fData['type'],
                                $fData['required'] ? 1 : 0,
                                $fData['is_visible'] ? 1 : 0,
                                $fIndex,
                                isset($fData['options']) ? json_encode($fData['options']) : null
                            ]);
                        }
                    }
                }
            }

            if (isset($data['deleted_sections'])) {
                foreach ($data['deleted_sections'] as $id) {
                    $this->db->prepare("DELETE FROM customer_form_sections WHERE id = ?")->execute([$id]);
                }
            }
            if (isset($data['deleted_fields'])) {
                foreach ($data['deleted_fields'] as $id) {
                    $stmtCheck = $this->db->prepare("SELECT field_key FROM customer_form_fields WHERE id = ?");
                    $stmtCheck->execute([$id]);
                    $fKey = $stmtCheck->fetchColumn();

                    if ($fKey) {
                        $checkMeta = $this->db->prepare("SELECT COUNT(*) FROM customer_meta WHERE field_key = ? AND field_value IS NOT NULL AND field_value != ''");
                        $checkMeta->execute([$fKey]);
                        if ($checkMeta->fetchColumn() > 0) {
                            continue;
                        }
                    }
                    $this->db->prepare("DELETE FROM customer_form_fields WHERE id = ? AND is_standard = 0")->execute([$id]);
                }
            }

            $this->db->commit();
            echo json_encode(['status' => 'success']);
        } catch (\Exception $e) {
            $this->db->rollBack();
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Handle Print Preview Setup.
     * * @return void
     */
    public function printPreview()
    {
        try {
            $this->db->exec("CREATE TABLE IF NOT EXISTS print_settings (
                id INT AUTO_INCREMENT PRIMARY KEY,
                header_style VARCHAR(50) DEFAULT 'with_header',
                layout VARCHAR(50) DEFAULT '3',
                receipt_text VARCHAR(50) DEFAULT 'Thank you for connecting with us.',
                signature_path VARCHAR(255) DEFAULT NULL,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )");

            $count = $this->db->query("SELECT COUNT(*) FROM print_settings")->fetchColumn();
            if ($count == 0) {
                $this->db->exec("INSERT INTO print_settings (header_style, layout, receipt_text) VALUES ('with_header', '3', 'Thank you for connecting with us.')");
            }
        } catch (\Exception $e) {}

        $message = '';
        $messageType = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';

            if ($action === 'save_settings') {
                $header_style = $_POST['header_style'] ?? 'with_header';
                $layout = $_POST['layout'] ?? '1';
                $receipt_text = substr($_POST['receipt_text'] ?? '', 0, 50);

                $stmt = $this->db->prepare("UPDATE print_settings SET header_style = ?, layout = ?, receipt_text = ? WHERE id = 1");
                if ($stmt->execute([$header_style, $layout, $receipt_text])) {
                    $message = "Settings updated successfully.";
                    $messageType = "success";
                } else {
                    $message = "Failed to update settings.";
                    $messageType = "error";
                }
            } elseif ($action === 'upload_signature') {
                if (isset($_FILES['signature']) && $_FILES['signature']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = __DIR__ . '/../../../public/uploads/signatures/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    $fileTmpPath = $_FILES['signature']['tmp_name'];
                    $fileName = $_FILES['signature']['name'];
                    $fileNameCmps = explode(".", $fileName);
                    $fileExtension = strtolower(end($fileNameCmps));
                    
                    $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
                    if (in_array($fileExtension, $allowedExts)) {
                        $newFileName = 'signature_' . time() . '.' . $fileExtension;
                        $destPath = $uploadDir . $newFileName;
                        
                        if (move_uploaded_file($fileTmpPath, $destPath)) {
                            $relativePath = 'uploads/signatures/' . $newFileName;
                            $this->db->prepare("UPDATE print_settings SET signature_path = ? WHERE id = 1")->execute([$relativePath]);
                            $message = "Signature uploaded successfully.";
                            $messageType = "success";
                        } else {
                            $message = "Error moving the uploaded file.";
                            $messageType = "error";
                        }
                    } else {
                        $message = "Invalid file type. Only JPG, PNG, GIF are allowed.";
                        $messageType = "error";
                    }
                } else {
                    $message = "Please select a valid image file.";
                    $messageType = "error";
                }
            }
        }

        $settings = $this->db->query("SELECT * FROM print_settings WHERE id = 1")->fetch(PDO::FETCH_ASSOC);
        $previewCustomer = $this->db->query("SELECT * FROM customers ORDER BY id DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);

        $this->view('setup/print_preview', [
            'title' => 'Print Preview Setup',
            'path' => '/setup/print-preview',
            'settings' => $settings,
            'previewCustomer' => $previewCustomer,
            'message' => $message,
            'messageType' => $messageType
        ]);
    }

    /**
     * Define column schemas for different tables.
     */
    private function getColumnDefinitions($table)
    {
        switch ($table) {
            case 'recent_customers':
                return [
                    ['key' => 'id', 'label' => 'ID', 'enabled' => true],
                    ['key' => 'full_name', 'label' => 'Name', 'enabled' => true],
                    ['key' => 'mobile_no', 'label' => 'Mobile', 'enabled' => true],
                    ['key' => 'area', 'label' => 'Area', 'enabled' => true],
                    ['key' => 'package_name', 'label' => 'Package', 'enabled' => true],
                    ['key' => 'payment_id', 'label' => 'Payment ID', 'enabled' => true],
                    ['key' => 'due_amount', 'label' => 'Due', 'enabled' => true],
                    ['key' => 'status', 'label' => 'Status', 'enabled' => true],
                    ['key' => 'created_at', 'label' => 'Date Added', 'enabled' => true],
                    ['key' => 'email', 'label' => 'Email', 'enabled' => false],
                    ['key' => 'district', 'label' => 'District', 'enabled' => false],
                    ['key' => 'thana', 'label' => 'Thana', 'enabled' => false],
                    ['key' => 'monthly_rent', 'label' => 'Monthly Rent', 'enabled' => false],
                ];

            case 'pending_customers':
                return [
                    ['key' => 'id', 'label' => 'ID', 'enabled' => true],
                    ['key' => 'full_name', 'label' => 'Name', 'enabled' => true],
                    ['key' => 'mobile_no', 'label' => 'Mobile', 'enabled' => true],
                    ['key' => 'area', 'label' => 'Area', 'enabled' => true],
                    ['key' => 'package_name', 'label' => 'Package', 'enabled' => true],
                    ['key' => 'created_at', 'label' => 'Request Date', 'enabled' => true],
                    ['key' => 'status', 'label' => 'Status', 'enabled' => true],
                ];

            case 'complain_list':
                return [
                    ['key' => 'id', 'label' => 'ID', 'enabled' => true],
                    ['key' => 'customer_info', 'label' => 'Customer Info', 'enabled' => true],
                    ['key' => 'complain_title', 'label' => 'Issue', 'enabled' => true],
                    ['key' => 'assigned_to', 'label' => 'Assigned To', 'enabled' => true],
                    ['key' => 'status', 'label' => 'Status', 'enabled' => true],
                    ['key' => 'created_at', 'label' => 'Date', 'enabled' => true],
                ];

            case 'collection_report':
                return [
                    ['key' => 'collection_date', 'label' => 'Date', 'enabled' => true],
                    ['key' => 'payment_id', 'label' => 'Payment ID', 'enabled' => true],
                    ['key' => 'customer_id', 'label' => 'ID', 'enabled' => true],
                    ['key' => 'customer_name', 'label' => 'Customer', 'enabled' => true],
                    ['key' => 'collected_by', 'label' => 'Collected By', 'enabled' => true],
                    ['key' => 'amount', 'label' => 'Amount', 'enabled' => true],
                    ['key' => 'status', 'label' => 'Status', 'enabled' => true],
                ];

            case 'customer_summary':
                return [
                    ['key' => 'date', 'label' => 'Date', 'enabled' => true],
                    ['key' => 'description', 'label' => 'Description', 'enabled' => true],
                    ['key' => 'bill_amount', 'label' => 'Bill Amount', 'enabled' => true],
                    ['key' => 'paid_amount', 'label' => 'Paid Amount', 'enabled' => true],
                ];

            case 'due_list':
                return [
                    ['key' => 'id', 'label' => 'ID', 'enabled' => true],
                    ['key' => 'customer_info', 'label' => 'Customer', 'enabled' => true],
                    ['key' => 'mobile_no', 'label' => 'Mobile', 'enabled' => true],
                    ['key' => 'area', 'label' => 'Area', 'enabled' => true],
                    ['key' => 'due_amount', 'label' => 'Due Amount', 'enabled' => true],
                ];

            case 'inactive_list':
                return [
                    ['key' => 'id', 'label' => 'ID', 'enabled' => true],
                    ['key' => 'customer_info', 'label' => 'Customer', 'enabled' => true],
                    ['key' => 'mobile_no', 'label' => 'Mobile', 'enabled' => true],
                    ['key' => 'status', 'label' => 'Status', 'enabled' => true],
                    ['key' => 'expire_date', 'label' => 'Expiry Date', 'enabled' => true],
                ];

            case 'all_customers':
            default:
                return [
                    ['key' => 'id', 'label' => 'ID', 'enabled' => true],
                    ['key' => 'full_name', 'label' => 'Name', 'enabled' => true],
                    ['key' => 'mobile_no', 'label' => 'Mobile', 'enabled' => true],
                    ['key' => 'area', 'label' => 'Area', 'enabled' => true],
                    ['key' => 'package_name', 'label' => 'Package', 'enabled' => true],
                    ['key' => 'payment_id', 'label' => 'Payment ID', 'enabled' => true],
                    ['key' => 'due_amount', 'label' => 'Due', 'enabled' => true],
                    ['key' => 'status', 'label' => 'Status', 'enabled' => true],
                ];
        }
    }
}