<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\WhatsappService;
use Database;
use PDO;

class ReceiptController extends Controller
{
    public function __construct()
    {
        $this->db = (new Database())->getConnection();
    }

    /**
     * Default index method for Receipt Controller
     */
    public function index()
    {
        return $this->areaWise();
    }

    /**
     * Area Wise Receipt View
     */
    public function areaWise()
    {
        // 1. Fetch Filter Options
        $areas = $this->db->query("SELECT DISTINCT area FROM customers WHERE area IS NOT NULL AND area != '' ORDER BY area")->fetchAll(PDO::FETCH_COLUMN);
        // 2. Build Query based on filters
        $area = $_GET['area'] ?? '';
        $package_id = $_GET['package_id'] ?? '';
        $status = $_GET['status'] ?? '';
        $connected_by = $_GET['connected_by'] ?? '';

        $query = "SELECT c.*, p.name as package_name, ip.prefix_code 
                  FROM customers c 
                  LEFT JOIN packages p ON c.package_id = p.id 
                  LEFT JOIN id_prefixes ip ON c.prefix_id = ip.id 
                  WHERE 1=1";
        $params = [];

        if ($area) {
            $query .= " AND c.area = ?";
            $params[] = $area;
        }

        if ($package_id) {
            $query .= " AND c.package_id = ?";
            $params[] = $package_id;
        }

        if ($status) {
            $query .= " AND c.status = ?";
            $params[] = $status;
        }

        if ($connected_by) {
            $query .= " AND c.connected_by = ?";
            $params[] = $connected_by;
        }

        $query .= " ORDER BY c.full_name ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch filter options
        $areas = $this->db->query("SELECT DISTINCT area FROM customers WHERE area IS NOT NULL AND area != '' ORDER BY area")->fetchAll(PDO::FETCH_COLUMN);
        $packages = $this->db->query("SELECT id, name FROM packages ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
        $employees = $this->db->query("SELECT id, display_name FROM users WHERE role = 'Employee' OR role = 'Admin' ORDER BY display_name ASC")->fetchAll(PDO::FETCH_ASSOC);

        // 3. Fetch Print Settings
        $printSettings = $this->db->query("SELECT * FROM print_settings WHERE id = 1")->fetch(PDO::FETCH_ASSOC);

        $this->view('receipt/area_wise', [
            'title' => 'Area Wise Receipt',
            'path' => '/receipt/area-wise',
            'customers' => $customers,
            'areas' => $areas,
            'packages' => $packages,
            'employees' => $employees,
            'selectedArea' => $area,
            'selectedPackage' => $package_id,
            'selectedStatus' => $status,
            'selectedEmployee' => $connected_by,
            'printSettings' => $printSettings
        ]);
    }

    /**
     * Customer Wise Receipt View
     */
    public function customerWise()
    {
        $search = $_GET['search'] ?? '';
        $month = $_GET['month'] ?? ''; 
        $customers = [];
        
        if ($search) {
            $query = "SELECT c.*, p.name as package_name, ip.prefix_code 
                      FROM customers c 
                      LEFT JOIN packages p ON c.package_id = p.id 
                      LEFT JOIN id_prefixes ip ON c.prefix_id = ip.id 
                      WHERE c.full_name LIKE ? OR c.mobile_no LIKE ? OR c.pppoe_name LIKE ? OR c.id LIKE ? 
                      ORDER BY c.full_name ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute(["%$search%", "%$search%", "%$search%", "%$search%"]);
            $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // Fetch Print Settings
        $printSettings = $this->db->query("SELECT * FROM print_settings WHERE id = 1")->fetch(PDO::FETCH_ASSOC);

        $this->view('receipt/customer_wise', [
            'title' => 'Customer Wise Receipt',
            'path' => '/receipt/customer-wise',
            'search' => $search,
            'selectedMonth' => $month,
            'customers' => $customers,
            'printSettings' => $printSettings
        ]);
    }

    /**
     * Update Bill View and Form Processing Handler
     */
    public function updateBill()
    {
        $message = '';
        $messageType = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $customerId = $_POST['customer_id'] ?? null;
            $newRent = $_POST['monthly_rent'] ?? null;
            $newDue = $_POST['due_amount'] ?? null;

            if ($customerId) {
                // Fetch customer mobile number before updating
                $custStmt = $this->db->prepare("SELECT mobile_no FROM customers WHERE id = ?");
                $custStmt->execute([$customerId]);
                $customer = $custStmt->fetch(PDO::FETCH_ASSOC);

                $stmt = $this->db->prepare("UPDATE customers SET monthly_rent = ?, due_amount = ? WHERE id = ?");
                if ($stmt->execute([$newRent, $newDue, $customerId])) {
                    $message = "Bill updated successfully for Customer ID: " . $customerId;
                    $messageType = "success";

                    // Trigger WhatsApp Notification if mobile exists
                    if (!empty($customer['mobile_no'])) {
                        $customerMobile = $customer['mobile_no'];
                        WhatsappService::sendMessage($customerMobile, "Dear Customer, your bill has been updated. Monthly Rent: {$newRent}, Due: {$newDue}. Thank you for staying with HK ISP.");
                    }
                } else {
                    $message = "Failed to update bill.";
                    $messageType = "error";
                }
            }
        }

        $customers = $this->db->query("SELECT id, full_name, mobile_no, monthly_rent, due_amount FROM customers ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

        $this->view('receipt/update_bill', [
            'title' => 'Update Bill',
            'path' => '/receipt/updateBill',
            'customers' => $customers,
            'message' => $message,
            'messageType' => $messageType
        ]);
    }

    public function searchAjax()
    {
        $q = $_GET['q'] ?? '';
        if (strlen($q) < 2) {
            echo json_encode([]);
            return;
        }

        $query = "SELECT c.id, c.full_name, c.mobile_no, c.pppoe_name, c.area, c.monthly_rent, ip.prefix_code, p.name as package_name 
                  FROM customers c 
                  LEFT JOIN packages p ON c.package_id = p.id 
                  LEFT JOIN id_prefixes ip ON c.prefix_id = ip.id 
                  WHERE c.full_name LIKE ? OR c.mobile_no LIKE ? OR c.pppoe_name LIKE ? OR c.id LIKE ? 
                  ORDER BY c.full_name ASC LIMIT 50";
        $stmt = $this->db->prepare($query);
        $stmt->execute(["%$q%", "%$q%", "%$q%", "%$q%"]);
        $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($customers);
    }

    /**
     * Batch Print Receipts
     */
    public function print()
    {
        $idsStr = $_GET['ids'] ?? '';
        $month = $_GET['month'] ?? date('Y-m');
        if (!$idsStr) {
            die("No IDs provided.");
        }
        $ids = explode(',', $idsStr);
        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        $query = "SELECT c.*, p.name as package_name, ip.prefix_code 
                  FROM customers c 
                  LEFT JOIN packages p ON c.package_id = p.id 
                  LEFT JOIN id_prefixes ip ON c.prefix_id = ip.id 
                  WHERE c.id IN ($placeholders)";
        $stmt = $this->db->prepare($query);
        $stmt->execute($ids);
        $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $printSettings = $this->db->query("SELECT * FROM print_settings WHERE id = 1")->fetch(PDO::FETCH_ASSOC);

        $this->view('receipt/print', [
            'customers' => $customers,
            'settings' => $printSettings,
            'billingMonth' => $month
        ]);
    }
}