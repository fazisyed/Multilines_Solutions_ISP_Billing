-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 12, 2026 at 02:29 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hk_isp_billing`
--

-- --------------------------------------------------------

--
-- Table structure for table `address_setup`
--

CREATE TABLE `address_setup` (
  `id` int(11) NOT NULL,
  `district` varchar(100) NOT NULL,
  `thana` varchar(100) NOT NULL,
  `area` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `collections`
--

CREATE TABLE `collections` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `invoice_no` varchar(100) DEFAULT NULL,
  `collection_date` datetime DEFAULT current_timestamp(),
  `next_expire_date` date DEFAULT NULL,
  `collected_by` int(11) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `complain_types`
--

CREATE TABLE `complain_types` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `identification_no` varchar(100) DEFAULT NULL,
  `mobile_no` varchar(20) NOT NULL,
  `alt_mobile_no` varchar(20) DEFAULT NULL,
  `professional_detail` varchar(255) DEFAULT NULL,
  `district` varchar(100) DEFAULT NULL,
  `thana` varchar(100) DEFAULT NULL,
  `area` varchar(100) DEFAULT NULL,
  `building_name` varchar(100) DEFAULT NULL,
  `floor` varchar(50) DEFAULT NULL,
  `tj_box` varchar(50) DEFAULT NULL,
  `house_no` varchar(50) DEFAULT NULL,
  `fiber_code` varchar(100) DEFAULT NULL,
  `onu_mac` varchar(100) DEFAULT NULL,
  `group_name` varchar(100) DEFAULT NULL,
  `lazar_info` varchar(100) DEFAULT NULL,
  `server_info` varchar(100) DEFAULT NULL,
  `connection_date` date DEFAULT NULL,
  `mikrotik_id` int(11) DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `mac_address` varchar(50) DEFAULT NULL,
  `bandwidth` varchar(50) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `package_name` varchar(100) DEFAULT NULL,
  `monthly_rent` decimal(10,2) DEFAULT 0.00,
  `due_amount` decimal(10,2) DEFAULT 0.00,
  `additional_charge` decimal(10,2) DEFAULT 0.00,
  `discount` decimal(10,2) DEFAULT 0.00,
  `advance_amount` decimal(10,2) DEFAULT 0.00,
  `vat_percent` decimal(5,2) DEFAULT 0.00,
  `total_amount` decimal(10,2) DEFAULT 0.00,
  `billing_type` enum('Prepaid','Postpaid') DEFAULT 'Prepaid',
  `connectivity_type` enum('Shared','Dedicated') DEFAULT 'Shared',
  `connection_type` enum('Fiber','Cat5') DEFAULT 'Fiber',
  `client_type` enum('Home','Corporate') DEFAULT 'Home',
  `distribution_point` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `note` text DEFAULT NULL,
  `connected_by` varchar(100) DEFAULT NULL,
  `security_deposit` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` varchar(50) DEFAULT 'Active',
  `prefix_id` int(11) DEFAULT NULL,
  `package_id` int(11) DEFAULT NULL,
  `payment_id` varchar(100) DEFAULT NULL,
  `expire_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_complains`
--

CREATE TABLE `customer_complains` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `complain_type_id` int(11) NOT NULL,
  `assigned_to` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`assigned_to`)),
  `description` text DEFAULT NULL,
  `status` enum('Pending','In Progress','Resolved') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_form_fields`
--

CREATE TABLE `customer_form_fields` (
  `id` int(11) NOT NULL,
  `section_id` int(11) DEFAULT NULL,
  `field_key` varchar(100) NOT NULL,
  `label` varchar(255) NOT NULL,
  `placeholder` varchar(255) DEFAULT NULL,
  `type` enum('text','textarea','dropdown','number','date','email','tel','password','select') DEFAULT 'text',
  `required` tinyint(1) DEFAULT 0,
  `is_visible` tinyint(1) DEFAULT 1,
  `is_standard` tinyint(1) DEFAULT 0,
  `order_index` int(11) DEFAULT 0,
  `options` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer_form_fields`
--

INSERT INTO `customer_form_fields` (`id`, `section_id`, `field_key`, `label`, `placeholder`, `type`, `required`, `is_visible`, `is_standard`, `order_index`, `options`) VALUES
(1, 1, 'full_name', 'Customer Name', 'Mr. John Doe', 'text', 1, 1, 1, 0, NULL),
(2, 1, 'email', 'Email Address', 'john@example.com', 'email', 0, 1, 1, 1, NULL),
(3, 1, 'identification_no', 'Identification No', 'NID/Birth Certificate', 'text', 0, 1, 1, 2, NULL),
(4, 1, 'mobile_no', 'Mobile No', '8801xxxxxxxxx', 'tel', 1, 1, 1, 3, NULL),
(5, 1, 'alt_mobile_no', 'Alt Mobile No', '018xxxxxxxx', 'tel', 0, 1, 1, 4, NULL),
(6, 1, 'professional_detail', 'Professional Detail', 'Software Engineer', 'text', 0, 1, 1, 5, NULL),
(7, 2, 'district', 'District', NULL, 'text', 0, 1, 1, 0, NULL),
(8, 2, 'thana', 'Thana', NULL, 'text', 0, 1, 1, 1, NULL),
(9, 2, 'area', 'Area', NULL, 'text', 0, 1, 1, 2, NULL),
(10, 2, 'building_name', 'Building Name', NULL, 'text', 0, 1, 1, 3, NULL),
(11, 2, 'floor', 'Floor', NULL, 'text', 0, 1, 1, 4, NULL),
(12, 2, 'tj_box', 'TJ Box', NULL, 'text', 0, 1, 1, 5, NULL),
(13, 2, 'house_no', 'House Info / No', NULL, 'text', 0, 1, 1, 6, NULL),
(14, 2, 'latitude', 'Latitude', NULL, 'text', 0, 1, 1, 7, NULL),
(15, 2, 'longitude', 'Longitude', NULL, 'text', 0, 1, 1, 8, NULL),
(16, 3, 'fiber_code', 'Fiber Code', NULL, 'text', 0, 1, 1, 0, NULL),
(17, 3, 'onu_mac', 'ONU Info', NULL, 'text', 0, 1, 1, 1, NULL),
(18, 3, 'group_name', 'Group', NULL, 'text', 0, 1, 1, 2, NULL),
(19, 3, 'lazar_info', 'Lazar Info', NULL, 'text', 0, 1, 1, 3, NULL),
(20, 3, 'server_info', 'Server Info', NULL, 'text', 0, 1, 1, 4, NULL),
(21, 3, 'connection_type_tech', 'Connection Type', NULL, 'select', 0, 1, 1, 5, '[\"PPPoE\", \"Static\"]'),
(22, 3, 'connection_date', 'Connection Date', NULL, 'date', 0, 1, 1, 6, NULL),
(23, 3, 'expire_date', 'Expire Date', NULL, 'date', 0, 1, 1, 7, NULL),
(24, 3, 'auto_disable', 'Auto Temporary Disable', NULL, 'select', 0, 1, 1, 8, '{\"0\": \"Off\", \"1\": \"On\"}'),
(25, 3, 'auto_disable_month', 'Auto Temporary Month', NULL, 'select', 0, 1, 1, 9, '{\"0\": \"Current Month\", \"1\": \"1 Month\", \"2\": \"2 Month\", \"3\": \"3 Month\"}'),
(26, 3, 'extra_days', 'Set Extra Day', NULL, 'select', 0, 1, 1, 10, NULL),
(27, 4, 'mikrotik_id', 'Mikrotik Router', 'Main Router', 'select', 0, 1, 1, 0, '{\"1\": \"Main Router\"}'),
(28, 4, 'pppoe_name', 'PPPoE Name', NULL, 'text', 0, 1, 1, 1, NULL),
(29, 4, 'pppoe_password', 'Password', NULL, 'text', 0, 1, 1, 2, NULL),
(30, 4, 'pppoe_profile', 'Profile', NULL, 'text', 0, 1, 1, 3, NULL),
(31, 4, 'ip_address', 'IP Address', '1.1.1.1', 'text', 0, 1, 1, 4, NULL),
(32, 4, 'mac_address', 'MAC Address', '00:1e:ec:...', 'text', 0, 1, 1, 5, NULL),
(33, 4, 'bandwidth', 'Bandwidth', '2M/4M', 'text', 0, 1, 1, 6, NULL),
(34, 4, 'comment', 'Comment', NULL, 'text', 0, 1, 1, 7, NULL),
(35, 5, 'package_id', 'Package', NULL, 'select', 1, 1, 1, 0, NULL),
(36, 5, 'monthly_rent', 'Monthly Rent', NULL, 'number', 1, 1, 1, 1, NULL),
(37, 5, 'payment_id', 'Payment ID (Customer ID)', 'Gateway ID', 'text', 0, 1, 1, 2, NULL),
(38, 5, 'due_amount', 'Due', NULL, 'number', 0, 1, 1, 3, NULL),
(39, 5, 'additional_charge', 'Additional Charge', NULL, 'number', 0, 1, 1, 4, NULL),
(40, 5, 'discount', 'Discount', NULL, 'number', 0, 1, 1, 5, NULL),
(41, 5, 'advance_amount', 'Advance', NULL, 'number', 0, 1, 1, 6, NULL),
(42, 5, 'vat_percent', 'Vat ( % )', NULL, 'number', 0, 1, 1, 7, NULL),
(43, 5, 'total_amount', 'Total', NULL, 'number', 0, 1, 1, 8, NULL),
(44, 6, 'billing_type', 'Billing Type', 'Pre Paid', 'select', 0, 1, 1, 0, '[\"Pre Paid\", \"Post Paid\"]'),
(45, 6, 'connectivity_type', 'Type of Connectivity', 'Shared', 'select', 0, 1, 1, 1, '[\"Shared\", \"Dedicated\"]'),
(46, 6, 'connection_type', 'Type of Connection', 'Fiber', 'select', 0, 1, 1, 2, '[\"Fiber\", \"Cat5\"]'),
(47, 6, 'client_type', 'Type of Client', 'Home', 'select', 0, 1, 1, 3, '[\"Home\", \"Corporate\"]'),
(48, 6, 'distribution_point', 'Dist. Location Point', 'DC', 'text', 0, 1, 1, 4, NULL),
(49, 6, 'connected_by', 'Connected By', 'Select Employee', 'select', 0, 1, 1, 5, NULL),
(50, 6, 'reference_name', 'Reference Name', 'Reference person name', 'text', 0, 1, 1, 6, NULL),
(51, 6, 'security_deposit', 'Security Deposit', '2000', 'number', 0, 1, 1, 7, NULL),
(52, 6, 'status', 'Status', 'Pending', 'select', 0, 1, 1, 8, '{\"pending\": \"Pending\", \"active\": \"Active\", \"inactive\": \"Inactive\", \"temp_disable\": \"Temporary Disable\", \"free\": \"Free Customer\"}'),
(53, 6, 'description', 'Description', NULL, 'textarea', 0, 1, 1, 9, NULL),
(54, 6, 'note', 'Note', NULL, 'textarea', 0, 1, 1, 10, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customer_form_sections`
--

CREATE TABLE `customer_form_sections` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `order_index` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer_form_sections`
--

INSERT INTO `customer_form_sections` (`id`, `name`, `order_index`, `created_at`) VALUES
(1, 'Personal Information', 0, '2026-09-12 04:15:28'),
(2, 'Address', 1, '2026-09-12 04:15:28'),
(3, 'Technical Information', 2, '2026-09-12 04:15:28'),
(4, 'Mikrotik Configuration', 3, '2026-09-12 04:15:28'),
(5, 'Package & Billing', 4, '2026-09-12 04:15:28'),
(6, 'Official Information', 5, '2026-09-12 04:15:28');

-- --------------------------------------------------------

--
-- Table structure for table `customer_meta`
--

CREATE TABLE `customer_meta` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `field_key` varchar(100) NOT NULL,
  `field_value` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `mikrotik_access` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `id_prefixes`
--

CREATE TABLE `id_prefixes` (
  `id` int(11) NOT NULL,
  `prefix_code` varchar(20) NOT NULL,
  `is_default` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `id_prefixes`
--

INSERT INTO `id_prefixes` (`id`, `prefix_code`, `is_default`, `created_at`) VALUES
(1, 'HK_', 1, '2026-09-12 04:21:30');

-- --------------------------------------------------------

--
-- Table structure for table `merchants`
--

CREATE TABLE `merchants` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `merchants`
--

INSERT INTO `merchants` (`id`, `name`, `created_at`) VALUES
(1, 'HK ISP', '2026-09-12 05:07:34'),
(2, 'Bangla Link', '2026-09-12 05:07:34'),
(3, 'Airtel', '2026-09-12 05:07:34');

-- --------------------------------------------------------

--
-- Table structure for table `mikrotik_routers`
--

CREATE TABLE `mikrotik_routers` (
  `id` int(11) NOT NULL,
  `router_name` varchar(255) NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `api_port` int(11) DEFAULT 8728,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('Online','Offline','Disabled') DEFAULT 'Online',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mikrotik_users`
--

CREATE TABLE `mikrotik_users` (
  `id` int(11) NOT NULL,
  `router_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `profile` varchar(100) DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `status` enum('Active','Disabled') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `olt_setup`
--

CREATE TABLE `olt_setup` (
  `id` int(11) NOT NULL,
  `olt_name` varchar(255) NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `port_count` int(11) DEFAULT 8,
  `location` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `merchant_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_settings`
--

CREATE TABLE `payment_settings` (
  `id` int(11) NOT NULL,
  `gateway_name` varchar(100) NOT NULL,
  `api_key` varchar(255) DEFAULT NULL,
  `secret_key` varchar(255) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_settings`
--

INSERT INTO `payment_settings` (`id`, `gateway_name`, `api_key`, `secret_key`, `status`, `updated_at`) VALUES
(1, 'Bkash', NULL, NULL, 'Inactive', '2026-09-12 05:14:30'),
(2, 'SSLCommerz', NULL, NULL, 'Inactive', '2026-09-12 05:14:30');

-- --------------------------------------------------------

--
-- Table structure for table `print_settings`
--

CREATE TABLE `print_settings` (
  `id` int(11) NOT NULL,
  `company_name` varchar(255) DEFAULT 'HK ISP',
  `company_address` text DEFAULT NULL,
  `company_phone` varchar(50) DEFAULT NULL,
  `company_email` varchar(100) DEFAULT NULL,
  `company_logo` varchar(255) DEFAULT NULL,
  `print_message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `print_settings`
--

INSERT INTO `print_settings` (`id`, `company_name`, `company_address`, `company_phone`, `company_email`, `company_logo`, `print_message`, `created_at`, `updated_at`) VALUES
(1, 'HK ISP', 'Your Company Address', '01234567890', NULL, NULL, 'Thank you for your business!', '2026-09-12 04:25:46', '2026-09-12 04:25:46');

-- --------------------------------------------------------

--
-- Table structure for table `resellers`
--

CREATE TABLE `resellers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `mobile` varchar(20) NOT NULL,
  `balance` decimal(10,2) DEFAULT 0.00,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reseller_packages`
--

CREATE TABLE `reseller_packages` (
  `id` int(11) NOT NULL,
  `package_name` varchar(255) NOT NULL,
  `bandwidth` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reseller_transactions`
--

CREATE TABLE `reseller_transactions` (
  `id` int(11) NOT NULL,
  `reseller_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `type` enum('Add','Deduct') DEFAULT 'Add',
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `permissions` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sms_settings`
--

CREATE TABLE `sms_settings` (
  `id` int(11) NOT NULL,
  `provider_name` varchar(100) DEFAULT 'Custom API',
  `api_url` text DEFAULT NULL,
  `sender_id` varchar(100) DEFAULT NULL,
  `api_key` varchar(255) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Inactive',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sms_settings`
--

INSERT INTO `sms_settings` (`id`, `provider_name`, `api_url`, `sender_id`, `api_key`, `status`, `updated_at`) VALUES
(1, 'Default SMS Gateway', NULL, NULL, NULL, 'Inactive', '2026-09-12 05:14:39');

-- --------------------------------------------------------

--
-- Table structure for table `table_settings`
--

CREATE TABLE `table_settings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `table_name` varchar(100) NOT NULL,
  `columns_json` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` enum('Super Admin','Admin','Employee') DEFAULT 'Employee',
  `status` enum('pending','active','inactive') DEFAULT 'pending',
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `display_name`, `username`, `password`, `email`, `role`, `status`, `phone`, `address`, `bio`, `profile_picture`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'superadmin', '1234', 'nayeemibrahim46@gmail.com', 'Super Admin', 'active', NULL, NULL, NULL, NULL, '2026-09-12 03:39:16', '2026-09-12 03:57:23'),
(2, 'Muhammad Faizan', 'fazi', '$2y$10$697tAw5RLnRGnmbLxr45vu/2OI80sG8zDU0sOqCkTpRFd3rz64rmq', 'fazi.syed@hotmail.com', 'Super Admin', 'active', NULL, NULL, NULL, NULL, '2026-09-12 03:41:34', '2026-09-12 04:17:37');

-- --------------------------------------------------------

--
-- Table structure for table `user_activity`
--

CREATE TABLE `user_activity` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `activity_type` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_activity`
--

INSERT INTO `user_activity` (`id`, `user_id`, `activity_type`, `description`, `ip_address`, `created_at`) VALUES
(1, 2, 'Login', 'User successfully logged in.', '::1', '2026-09-12 04:13:59'),
(2, 2, 'Login', 'User successfully logged in.', '::1', '2026-09-12 04:43:21'),
(3, 2, 'Login', 'User successfully logged in.', '::1', '2026-09-12 04:44:07'),
(4, 2, 'Login', 'User successfully logged in.', '::1', '2026-09-12 10:41:00'),
(5, 2, 'Login', 'User successfully logged in.', '::1', '2026-09-12 10:41:00'),
(6, 2, 'Logout', 'User logged out.', '::1', '2026-09-12 11:15:38'),
(7, 2, 'Login', 'User successfully logged in.', '::1', '2026-09-12 11:15:40'),
(8, 2, 'Login', 'User successfully logged in.', '::1', '2026-09-12 11:15:40'),
(9, 2, 'Logout', 'User logged out.', '::1', '2026-09-12 11:29:31'),
(10, 2, 'Login', 'User successfully logged in.', '::1', '2026-09-12 11:33:12'),
(11, 2, 'Login', 'User successfully logged in.', '::1', '2026-09-12 11:33:12'),
(12, 2, 'Login', 'User successfully logged in.', '::1', '2026-09-12 11:33:12'),
(13, 2, 'Logout', 'User logged out.', '::1', '2026-09-12 11:34:28'),
(14, 2, 'Login', 'User successfully logged in.', '::1', '2026-09-12 11:35:23'),
(15, 2, 'Logout', 'User logged out.', '::1', '2026-09-12 11:37:37'),
(16, 2, 'Login', 'User successfully logged in.', '::1', '2026-09-12 11:38:58'),
(17, 2, 'Login', 'User successfully logged in.', '::1', '2026-09-12 11:38:59'),
(18, 2, 'Logout', 'User logged out.', '::1', '2026-09-12 11:46:25'),
(19, 2, 'Login', 'User successfully logged in.', '::1', '2026-09-12 11:46:29'),
(20, 2, 'Login', 'User successfully logged in.', '::1', '2026-09-12 11:46:29'),
(21, 2, 'Login', 'User successfully logged in.', '::1', '2026-09-12 11:55:37'),
(22, 2, 'Login', 'User successfully logged in.', '::1', '2026-09-12 11:55:37');

-- --------------------------------------------------------

--
-- Table structure for table `whatsapp_settings`
--

CREATE TABLE `whatsapp_settings` (
  `id` int(11) NOT NULL,
  `provider_name` varchar(100) DEFAULT 'Meta Cloud API',
  `api_url` text DEFAULT NULL,
  `phone_number_id` varchar(100) DEFAULT NULL,
  `access_token` text DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Inactive',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `whatsapp_settings`
--

INSERT INTO `whatsapp_settings` (`id`, `provider_name`, `api_url`, `phone_number_id`, `access_token`, `status`, `updated_at`) VALUES
(1, 'Meta Cloud API', NULL, NULL, NULL, 'Inactive', '2026-09-12 10:48:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address_setup`
--
ALTER TABLE `address_setup`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `collections`
--
ALTER TABLE `collections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `complain_types`
--
ALTER TABLE `complain_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_complains`
--
ALTER TABLE `customer_complains`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `complain_type_id` (`complain_type_id`);

--
-- Indexes for table `customer_form_fields`
--
ALTER TABLE `customer_form_fields`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `field_key` (`field_key`),
  ADD KEY `section_id` (`section_id`);

--
-- Indexes for table `customer_form_sections`
--
ALTER TABLE `customer_form_sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_meta`
--
ALTER TABLE `customer_meta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `field_key` (`field_key`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `id_prefixes`
--
ALTER TABLE `id_prefixes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `merchants`
--
ALTER TABLE `merchants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mikrotik_routers`
--
ALTER TABLE `mikrotik_routers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mikrotik_users`
--
ALTER TABLE `mikrotik_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `router_id` (`router_id`);

--
-- Indexes for table `olt_setup`
--
ALTER TABLE `olt_setup`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_settings`
--
ALTER TABLE `payment_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `print_settings`
--
ALTER TABLE `print_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `resellers`
--
ALTER TABLE `resellers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `reseller_packages`
--
ALTER TABLE `reseller_packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reseller_transactions`
--
ALTER TABLE `reseller_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms_settings`
--
ALTER TABLE `sms_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `table_settings`
--
ALTER TABLE `table_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_activity`
--
ALTER TABLE `user_activity`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `whatsapp_settings`
--
ALTER TABLE `whatsapp_settings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address_setup`
--
ALTER TABLE `address_setup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `collections`
--
ALTER TABLE `collections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `complain_types`
--
ALTER TABLE `complain_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_complains`
--
ALTER TABLE `customer_complains`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_form_fields`
--
ALTER TABLE `customer_form_fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `customer_form_sections`
--
ALTER TABLE `customer_form_sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `customer_meta`
--
ALTER TABLE `customer_meta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `id_prefixes`
--
ALTER TABLE `id_prefixes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `merchants`
--
ALTER TABLE `merchants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `mikrotik_routers`
--
ALTER TABLE `mikrotik_routers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mikrotik_users`
--
ALTER TABLE `mikrotik_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `olt_setup`
--
ALTER TABLE `olt_setup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_settings`
--
ALTER TABLE `payment_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `print_settings`
--
ALTER TABLE `print_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `resellers`
--
ALTER TABLE `resellers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reseller_packages`
--
ALTER TABLE `reseller_packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reseller_transactions`
--
ALTER TABLE `reseller_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sms_settings`
--
ALTER TABLE `sms_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `table_settings`
--
ALTER TABLE `table_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_activity`
--
ALTER TABLE `user_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `whatsapp_settings`
--
ALTER TABLE `whatsapp_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `collections`
--
ALTER TABLE `collections`
  ADD CONSTRAINT `collections_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);

--
-- Constraints for table `customer_complains`
--
ALTER TABLE `customer_complains`
  ADD CONSTRAINT `customer_complains_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_complains_ibfk_2` FOREIGN KEY (`complain_type_id`) REFERENCES `complain_types` (`id`);

--
-- Constraints for table `customer_form_fields`
--
ALTER TABLE `customer_form_fields`
  ADD CONSTRAINT `customer_form_fields_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `customer_form_sections` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `mikrotik_users`
--
ALTER TABLE `mikrotik_users`
  ADD CONSTRAINT `mikrotik_users_ibfk_1` FOREIGN KEY (`router_id`) REFERENCES `mikrotik_routers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_activity`
--
ALTER TABLE `user_activity`
  ADD CONSTRAINT `user_activity_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
