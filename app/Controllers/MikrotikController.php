<?php
namespace App\Controllers;

use App\Core\Controller;
use Database;
use PDO;

class MikrotikController extends Controller
{
    protected $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->initDatabase();
    }

    private function initDatabase()
    {
        // 1. Routers Table
        $this->db->exec("CREATE TABLE IF NOT EXISTS mikrotik_routers (
            id INT AUTO_INCREMENT PRIMARY KEY,
            router_name VARCHAR(255) NOT NULL,
            ip_address VARCHAR(50) NOT NULL,
            api_port INT DEFAULT 8728,
            username VARCHAR(100) NOT NULL,
            password VARCHAR(255) NOT NULL,
            status ENUM('Online', 'Offline') DEFAULT 'Online',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        // 2. Mikrotik Users Sync Table
        $this->db->exec("CREATE TABLE IF NOT EXISTS mikrotik_users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            router_id INT NOT NULL,
            username VARCHAR(100) NOT NULL,
            profile VARCHAR(100),
            ip_address VARCHAR(50),
            status ENUM('Active', 'Disabled') DEFAULT 'Active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (router_id) REFERENCES mikrotik_routers(id) ON DELETE CASCADE
        )");
    }

    // Default redirect to router config
    public function index()
    {
        return $this->routerConfig();
    }

    // Page 1: Router Config
    public function routerConfig()
    {
        $routers = $this->db->query("SELECT * FROM mikrotik_routers ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

        $this->view('mikrotik/router_config', [
            'title' => 'MikroTik Router Configuration',
            'path' => '/mikrotik/router-config',
            'routers' => $routers
        ]);
    }

    // Page 2: User List (PPPoE / Active Users)
    public function userList()
    {
        $users = $this->db->query("SELECT u.*, r.router_name FROM mikrotik_users u LEFT JOIN mikrotik_routers r ON u.router_id = r.id ORDER BY u.id DESC")->fetchAll(PDO::FETCH_ASSOC);
        $routers = $this->db->query("SELECT id, router_name FROM mikrotik_routers")->fetchAll(PDO::FETCH_ASSOC);

        $this->view('mikrotik/user_list', [
            'title' => 'MikroTik User List',
            'path' => '/mikrotik/user-list',
            'users' => $users,
            'routers' => $routers
        ]);
    }

    // Handle Adding Router
    public function storeRouter()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['router_name'] ?? '';
            $ip = $_POST['ip_address'] ?? '';
            $port = $_POST['api_port'] ?? 8728;
            $user = $_POST['username'] ?? '';
            $pass = $_POST['password'] ?? '';

            if ($name && $ip) {
                $stmt = $this->db->prepare("INSERT INTO mikrotik_routers (router_name, ip_address, api_port, username, password) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$name, $ip, $port, $user, $pass]);
            }
            return $this->redirect('/mikrotik/router-config');
        }
    }

    // Handle Deleting Router
    public function deleteRouter($id)
    {
        if ($id) {
            $stmt = $this->db->prepare("DELETE FROM mikrotik_routers WHERE id = ?");
            $stmt->execute([$id]);
        }
        return $this->redirect('/mikrotik/router-config');
    }
}