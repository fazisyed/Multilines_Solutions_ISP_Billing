<?php
namespace App\Controllers;

use App\Core\Controller;
use Database;
use PDO;

class ResellerController extends Controller
{
    protected $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->initDatabase();
    }

    private function initDatabase()
    {
        // 1. Resellers Table
        $this->db->exec("CREATE TABLE IF NOT EXISTS resellers (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            company_name VARCHAR(255),
            email VARCHAR(255),
            mobile VARCHAR(20) NOT NULL,
            balance DECIMAL(10,2) DEFAULT 0.00,
            status ENUM('Active', 'Inactive') DEFAULT 'Active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        // 2. Reseller Packages Table
        $this->db->exec("CREATE TABLE IF NOT EXISTS reseller_packages (
            id INT AUTO_INCREMENT PRIMARY KEY,
            package_name VARCHAR(255) NOT NULL,
            bandwidth VARCHAR(100) NOT NULL,
            price DECIMAL(10,2) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        // 3. Reseller Balance Transactions Table
        $this->db->exec("CREATE TABLE IF NOT EXISTS reseller_transactions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            reseller_id INT NOT NULL,
            amount DECIMAL(10,2) NOT NULL,
            type ENUM('Add', 'Deduct') DEFAULT 'Add',
            note TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
    }

    // Default router endpoint
    public function index()
    {
        return $this->resellerList();
    }

    // 1. Reseller List Page & Store Handler
    public function resellerList()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $company = $_POST['company_name'] ?? '';
            $email = $_POST['email'] ?? '';
            $mobile = $_POST['mobile'] ?? '';
            $balance = $_POST['balance'] ?? 0.00;

            if ($name && $mobile) {
                $stmt = $this->db->prepare("INSERT INTO resellers (name, company_name, email, mobile, balance) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$name, $company, $email, $mobile, $balance]);
            }
            return $this->redirect('/reseller/resellerList');
        }

        $resellers = $this->db->query("SELECT * FROM resellers ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

        $this->view('reseller/list', [
            'title' => 'Reseller List',
            'path' => '/reseller/resellerList',
            'resellers' => $resellers
        ]);
    }

    // 2. Reseller Package Page & Store Handler
    public function resellerPackage()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['package_name'] ?? '';
            $bw = $_POST['bandwidth'] ?? '';
            $price = $_POST['price'] ?? 0;

            if ($name && $price) {
                $stmt = $this->db->prepare("INSERT INTO reseller_packages (package_name, bandwidth, price) VALUES (?, ?, ?)");
                $stmt->execute([$name, $bw, $price]);
            }
            return $this->redirect('/reseller/resellerPackage');
        }

        $packages = $this->db->query("SELECT * FROM reseller_packages ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

        $this->view('reseller/package', [
            'title' => 'Reseller Package',
            'path' => '/reseller/resellerPackage',
            'packages' => $packages
        ]);
    }

    // 3. Reseller Balance Page & Transaction Handler
    public function resellerBalance()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $resellerId = $_POST['reseller_id'] ?? '';
            $amount = $_POST['amount'] ?? 0;
            $type = $_POST['type'] ?? 'Add';
            $note = $_POST['note'] ?? '';

            if ($resellerId && $amount > 0) {
                // Record Transaction
                $stmt = $this->db->prepare("INSERT INTO reseller_transactions (reseller_id, amount, type, note) VALUES (?, ?, ?, ?)");
                $stmt->execute([$resellerId, $amount, $type, $note]);

                // Adjust Reseller Balance
                if ($type === 'Add') {
                    $upd = $this->db->prepare("UPDATE resellers SET balance = balance + ? WHERE id = ?");
                } else {
                    $upd = $this->db->prepare("UPDATE resellers SET balance = balance - ? WHERE id = ?");
                }
                $upd->execute([$amount, $resellerId]);
            }
            return $this->redirect('/reseller/resellerBalance');
        }

        $resellers = $this->db->query("SELECT id, name, balance FROM resellers ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
        $transactions = $this->db->query("SELECT t.*, r.name as reseller_name FROM reseller_transactions t LEFT JOIN resellers r ON t.reseller_id = r.id ORDER BY t.id DESC LIMIT 50")->fetchAll(PDO::FETCH_ASSOC);

        $this->view('reseller/balance', [
            'title' => 'Reseller Balance Management',
            'path' => '/reseller/resellerBalance',
            'resellers' => $resellers,
            'transactions' => $transactions
        ]);
    }

    // 4. Reseller Balance Summary Page
    public function resellerBalanceSummary()
    {
        $resellers = $this->db->query("SELECT r.*, 
            (SELECT COALESCE(SUM(amount), 0) FROM reseller_transactions WHERE reseller_id = r.id AND type = 'Add') as total_added,
            (SELECT COALESCE(SUM(amount), 0) FROM reseller_transactions WHERE reseller_id = r.id AND type = 'Deduct') as total_deducted
            FROM resellers r ORDER BY r.name ASC")->fetchAll(PDO::FETCH_ASSOC);

        $this->view('reseller/summary', [
            'title' => 'Reseller Balance Summary',
            'path' => '/reseller/resellerBalanceSummary',
            'resellers' => $resellers
        ]);
    }

    // Legacy delete method support
    public function delete($id)
    {
        if ($id) {
            $stmt = $this->db->prepare("DELETE FROM resellers WHERE id = ?");
            $stmt->execute([$id]);
        }
        return $this->redirect('/reseller/resellerList');
    }
}