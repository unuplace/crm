-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 04 أكتوبر 2024 الساعة 03:53
-- إصدار الخادم: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `talad_real_estate`
--

-- --------------------------------------------------------

--
-- بنية الجدول `attachments`
--

CREATE TABLE `attachments` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `attachment_type` enum('مبيعات','عميل') NOT NULL,
  `name` varchar(100) NOT NULL,
  `path` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `attachments`
--

INSERT INTO `attachments` (`id`, `client_id`, `attachment_type`, `name`, `path`, `created_at`) VALUES
(1, 1, 'مبيعات', 'بطاقة الهوية', '../uploads/sadn.png', '2024-10-02 22:33:46');

-- --------------------------------------------------------

--
-- بنية الجدول `calls`
--

CREATE TABLE `calls` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `call_date` date NOT NULL,
  `call_count` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `client_tasks`
--

CREATE TABLE `client_tasks` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `task_type` varchar(50) NOT NULL,
  `task_date` date NOT NULL,
  `task_time` time NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `client_tasks`
--

INSERT INTO `client_tasks` (`id`, `client_id`, `task_type`, `task_date`, `task_time`, `description`, `created_at`) VALUES
(1, 1, 'اجتماع', '2024-10-05', '03:00:00', 'لا اعرف', '2024-10-02 21:57:39'),
(2, 1, 'زيارة', '2024-10-12', '05:32:00', 'fdsd', '2024-10-02 22:32:55');

-- --------------------------------------------------------

--
-- بنية الجدول `communication`
--

CREATE TABLE `communication` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `potential_client_id` int(11) NOT NULL,
  `communication_date` datetime NOT NULL,
  `notes` text DEFAULT NULL,
  `new_status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `communication`
--

INSERT INTO `communication` (`id`, `employee_id`, `potential_client_id`, `communication_date`, `notes`, `new_status`) VALUES
(1, 3, 30, '2024-09-24 22:42:51', '', 'مهتم'),
(2, 3, 31, '2024-09-25 03:06:02', '', 'مهتم'),
(3, 3, 32, '2024-09-25 16:38:40', '', 'مهتم'),
(4, 3, 32, '2024-09-25 17:26:42', '', 'تم البيع'),
(5, 3, 32, '2024-09-25 19:10:06', '', 'تم الحجز');

-- --------------------------------------------------------

--
-- بنية الجدول `communications`
--

CREATE TABLE `communications` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `communication_date` date DEFAULT NULL,
  `type` enum('call','email','visit','other') DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) NOT NULL,
  `monthly_commitment` decimal(10,2) DEFAULT NULL,
  `bank` varchar(100) DEFAULT NULL,
  `sector` varchar(100) DEFAULT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `source` text NOT NULL,
  `notes` varchar(128) DEFAULT NULL,
  `status` varchar(128) DEFAULT NULL,
  `contact_date` date DEFAULT NULL,
  `salary` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `customers`
--

INSERT INTO `customers` (`id`, `name`, `email`, `phone`, `monthly_commitment`, `bank`, `sector`, `assigned_to`, `created_at`, `source`, `notes`, `status`, `contact_date`, `salary`) VALUES
(1, 'عميل طازه', 'asim1a@hotmail.com', '9999999', 1000.00, 'الراجحي', 'حكومي', 3, '2024-09-25 16:44:43', '', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- بنية الجدول `lost_opportunities`
--

CREATE TABLE `lost_opportunities` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(128) DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `monthly_commitment` decimal(10,2) DEFAULT NULL,
  `bank` text DEFAULT NULL,
  `sector` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `contact_date` date DEFAULT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `lost_opportunities`
--

INSERT INTO `lost_opportunities` (`id`, `name`, `phone`, `email`, `salary`, `monthly_commitment`, `bank`, `sector`, `notes`, `contact_date`, `assigned_to`, `created_at`) VALUES
(1, 'عميل جديد', '059999999', 'engineer@engineer.com', 10000.00, 2000.00, 'الراجحي', 'عسكري', 'لايوجد', '0000-00-00', 3, '2024-10-02 02:24:07'),
(2, 'عميل جديد', '059999999', 'engineer@engineer.com', 10000.00, 2000.00, 'الراجحي', 'عسكري', 'لايوجد', '0000-00-00', 3, '2024-10-02 02:24:22'),
(3, 'عميل جديد', '059999999', 'engineer@engineer.com', 10000.00, 2000.00, 'الراجحي', 'عسكري', 'لايوجد', '0000-00-00', 3, '2024-10-02 02:26:56'),
(4, 'عميل جديد', '059999999', 'engineer@engineer.com', 10000.00, 2000.00, 'الراجحي', 'عسكري', 'لايوجد', '0000-00-00', 3, '2024-10-02 02:31:41'),
(5, 'عميل جديد', '059999999', 'engineer@engineer.com', 10000.00, 2000.00, 'الراجحي', 'عسكري', 'لايوجد', '0000-00-00', 3, '2024-10-02 16:28:48'),
(6, 'عميل جديد', '059999999', 'engineer@engineer.com', 10000.00, 2000.00, 'الراجحي', 'عسكري', 'لايوجد', '0000-00-00', 3, '2024-10-02 16:29:57');

-- --------------------------------------------------------

--
-- بنية الجدول `potential_clients`
--

CREATE TABLE `potential_clients` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `monthly_commitment` decimal(10,2) DEFAULT NULL,
  `bank` text DEFAULT NULL,
  `sector` text DEFAULT NULL,
  `status` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `contact_date` date DEFAULT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `email` varchar(128) DEFAULT NULL,
  `source` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `potential_clients`
--

INSERT INTO `potential_clients` (`id`, `name`, `phone`, `salary`, `monthly_commitment`, `bank`, `sector`, `status`, `notes`, `contact_date`, `assigned_to`, `created_at`, `email`, `source`) VALUES
(30, 'عميل جديد', '059999999', 10000.00, 2000.00, 'الراجحي', 'عسكري', 'مهتم', 'لايوجد', '0000-00-00', 3, '2024-09-24 19:05:43', 'engineer@engineer.com', ''),
(31, 'احمد', '0999599993', 10000.00, 200.00, 'البنك', 'عسكري', 'مهتم', 'مهتم', '0000-00-00', 3, '2024-09-25 00:04:10', 'asim1a@hotmail.com', ''),
(32, 'عميل طازه', '9999999', 40000.00, 1000.00, 'الراجحي', 'حكومي', 'تم البيع', 'جديدث', '0000-00-00', 3, '2024-09-25 13:31:12', 'asim1a@hotmail.com', ''),
(35, 'Asim Alzubaidi', '0599115017', 12000.00, 1253.00, 'الراجحي', 'عسكري', 'جديد', 'بدون تحديثات', '2024-10-16', 3, '2024-10-02 20:17:10', 'asim1a@hotmail.com', 'الهاتف');

-- --------------------------------------------------------

--
-- بنية الجدول `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `city` varchar(50) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `total_units` int(11) NOT NULL,
  `sold_units` int(11) DEFAULT 0,
  `remaining_units` int(11) GENERATED ALWAYS AS (`total_units` - `sold_units`) VIRTUAL,
  `design_count` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `description` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('planned','in_progress','completed','on_hold') DEFAULT 'planned'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `projects`
--

INSERT INTO `projects` (`id`, `name`, `city`, `logo`, `total_units`, `sold_units`, `design_count`, `created_at`, `description`, `start_date`, `end_date`, `status`) VALUES
(1, 'درة السدن', 'جدة', 'sadn.png', 609, 150, 3, '2024-09-22 15:09:17', 'وصف المشروع', '2024-09-01', '2025-09-30', ''),
(2, 'مشروع الرياض', '', NULL, 0, 0, 0, '2024-09-22 21:22:54', 'مشروع سكني في شمال الرياض', '2023-01-01', '2024-12-31', ''),
(3, 'مشروع جدة', '', NULL, 0, 0, 0, '2024-09-22 21:22:54', 'مجمع تجاري في وسط جدة', '2023-03-15', '2025-06-30', ''),
(4, 'مشروع الدمام', '', NULL, 0, 0, 0, '2024-09-22 21:22:54', 'برج سكني في الدمام', '2023-05-01', '2024-11-30', ''),
(5, 'مشروع الرياض', '', NULL, 0, 0, 0, '2024-09-22 21:23:48', 'مشروع سكني في شمال الرياض', '2023-01-01', '2024-12-31', ''),
(6, 'مشروع جدة', '', NULL, 0, 0, 0, '2024-09-22 21:23:48', 'مجمع تجاري في وسط جدة', '2023-03-15', '2025-06-30', ''),
(7, 'مشروع الدمام', '', NULL, 0, 0, 0, '2024-09-22 21:23:48', 'برج سكني في الدمام', '2023-05-01', '2024-11-30', ''),
(8, 'مشروع الرياض', '', NULL, 0, 0, 0, '2024-09-22 21:25:18', 'مشروع سكني في شمال الرياض', '2023-01-01', '2024-12-31', ''),
(9, 'مشروع جدة', '', NULL, 0, 0, 0, '2024-09-22 21:25:18', 'مجمع تجاري في وسط جدة', '2023-03-15', '2025-06-30', ''),
(10, 'مشروع الدمام', '', NULL, 0, 0, 0, '2024-09-22 21:25:18', 'برج سكني في الدمام', '2023-05-01', '2024-11-30', ''),
(11, 'مشروع الرياض', '', NULL, 0, 0, 0, '2024-09-22 21:26:01', 'مشروع سكني في شمال الرياض', '2023-01-01', '2024-12-31', ''),
(12, 'مشروع جدة', '', NULL, 0, 0, 0, '2024-09-22 21:26:01', 'مجمع تجاري في وسط جدة', '2023-03-15', '2025-06-30', ''),
(13, 'مشروع الدمام', '', NULL, 0, 0, 0, '2024-09-22 21:26:01', 'برج سكني في الدمام', '2023-05-01', '2024-11-30', ''),
(14, 'مشروع الرياض', '', NULL, 0, 0, 0, '2024-09-22 21:27:11', 'مشروع سكني في شمال الرياض', '2023-01-01', '2024-12-31', ''),
(15, 'مشروع جدة', '', NULL, 0, 0, 0, '2024-09-22 21:27:11', 'مجمع تجاري في وسط جدة', '2023-03-15', '2025-06-30', ''),
(16, 'مشروع الدمام', '', NULL, 0, 0, 0, '2024-09-22 21:27:11', 'برج سكني في الدمام', '2023-05-01', '2024-11-30', ''),
(17, 'مشروع الرياض', '', NULL, 0, 0, 0, '2024-09-22 21:28:56', 'مشروع سكني في شمال الرياض', '2023-01-01', '2024-12-31', ''),
(18, 'مشروع جدة', '', NULL, 0, 0, 0, '2024-09-22 21:28:56', 'مجمع تجاري في وسط جدة', '2023-03-15', '2025-06-30', ''),
(19, 'مشروع الدمام', '', NULL, 0, 0, 0, '2024-09-22 21:28:56', 'برج سكني في الدمام', '2023-05-01', '2024-11-30', ''),
(20, 'مشروعي', 'جدة', '', 599, 0, 4, '2024-09-27 11:37:32', 'note\n', NULL, NULL, 'planned'),
(21, 'مشروع ضاحي بن خلفان', 'جدة', '', 4000, 0, 9, '2024-09-27 11:43:10', NULL, NULL, NULL, ''),
(22, 'عاصم', 'جدة', NULL, 499, NULL, 4, '2024-10-03 21:20:03', 'بدون وصف', '2024-10-04', '2024-10-16', 'planned'),
(23, 'KSA', 'مكة', 'sadn.png', 455, 0, 3, '2024-10-03 21:32:41', 'بدون', '2024-10-04', '2027-07-04', 'in_progress');

--
-- القوادح `projects`
--
DELIMITER $$
CREATE TRIGGER `update_remaining_units` BEFORE UPDATE ON `projects` FOR EACH ROW BEGIN
    SET NEW.remaining_units = NEW.total_units - NEW.sold_units;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- بنية الجدول `project_property_types`
--

CREATE TABLE `project_property_types` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `property_type_id` int(11) NOT NULL,
  `available_units` int(11) DEFAULT 0,
  `quantity` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `project_property_types`
--

INSERT INTO `project_property_types` (`id`, `project_id`, `property_type_id`, `available_units`, `quantity`) VALUES
(1, 21, 3, 4, 0),
(2, 21, 2, 4, 0),
(3, 21, 1, 4, 0),
(4, 20, 3, 0, 3),
(5, 20, 2, 0, 6),
(6, 20, 1, 0, 7),
(7, 22, 3, 0, 2),
(8, 22, 2, 0, 2),
(9, 22, 1, 0, 2),
(10, 20, 3, 0, 10),
(11, 20, 2, 0, 2),
(12, 20, 1, 0, 5),
(13, 12, 3, 0, 10),
(14, 12, 2, 0, 2),
(15, 12, 1, 0, 4),
(16, 8, 3, 0, 5),
(17, 8, 2, 0, 5),
(18, 8, 1, 0, 5);

-- --------------------------------------------------------

--
-- بنية الجدول `properties`
--

CREATE TABLE `properties` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `property_type_id` int(11) NOT NULL,
  `serial_number` varchar(4) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` enum('متاح','محجوز','تم البيع') NOT NULL,
  `readiness` enum('لم يتم البدء','تحت الانشاء','مكتمل') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `properties`
--

INSERT INTO `properties` (`id`, `project_id`, `property_type_id`, `serial_number`, `price`, `status`, `readiness`) VALUES
(1, 22, 1, '9181', 0.00, 'محجوز', ''),
(2, 22, 1, '4019', 0.00, 'متاح', ''),
(3, 22, 2, '2865', 0.00, 'متاح', ''),
(4, 22, 2, '2403', 0.00, 'متاح', 'لم يتم البدء'),
(5, 22, 3, '7936', 0.00, 'متاح', 'لم يتم البدء'),
(6, 22, 3, '5525', 0.00, 'متاح', 'لم يتم البدء'),
(7, 22, 1, '7863', 0.00, 'متاح', 'لم يتم البدء'),
(8, 22, 1, '3151', 0.00, 'متاح', 'لم يتم البدء'),
(9, 22, 2, '1042', 0.00, 'متاح', 'لم يتم البدء'),
(10, 22, 2, '1787', 0.00, 'متاح', 'لم يتم البدء'),
(11, 22, 3, '1762', 0.00, 'متاح', 'لم يتم البدء'),
(12, 22, 3, '9686', 0.00, 'متاح', 'لم يتم البدء'),
(13, 20, 3, '7402', 0.00, 'متاح', 'لم يتم البدء'),
(14, 20, 3, '6332', 0.00, 'متاح', 'لم يتم البدء'),
(15, 20, 3, '3601', 0.00, 'متاح', 'لم يتم البدء'),
(16, 20, 2, '7754', 0.00, 'متاح', 'لم يتم البدء'),
(17, 20, 2, '3710', 0.00, 'متاح', 'لم يتم البدء'),
(18, 20, 2, '5600', 0.00, 'متاح', 'لم يتم البدء'),
(19, 20, 2, '3659', 0.00, 'متاح', 'لم يتم البدء'),
(20, 20, 2, '9131', 0.00, 'متاح', 'لم يتم البدء'),
(21, 20, 2, '7247', 0.00, 'متاح', 'لم يتم البدء'),
(22, 20, 1, '4130', 0.00, 'متاح', 'لم يتم البدء'),
(23, 20, 1, '9563', 0.00, 'متاح', 'لم يتم البدء'),
(24, 20, 1, '3400', 0.00, 'متاح', 'لم يتم البدء'),
(25, 20, 1, '5801', 0.00, 'متاح', 'لم يتم البدء'),
(26, 20, 1, '9598', 0.00, 'متاح', 'لم يتم البدء'),
(27, 20, 1, '4650', 0.00, 'متاح', 'لم يتم البدء'),
(28, 20, 1, '9912', 0.00, 'متاح', 'لم يتم البدء'),
(29, 20, 3, '5256', 0.00, 'متاح', 'لم يتم البدء'),
(30, 20, 3, '0044', 0.00, 'متاح', 'لم يتم البدء'),
(31, 20, 3, '9446', 0.00, 'متاح', 'لم يتم البدء'),
(32, 20, 3, '5450', 0.00, 'متاح', 'لم يتم البدء'),
(33, 20, 3, '7307', 0.00, 'متاح', 'لم يتم البدء'),
(34, 20, 3, '0965', 0.00, 'متاح', 'لم يتم البدء'),
(35, 20, 3, '6472', 0.00, 'متاح', 'لم يتم البدء'),
(36, 20, 3, '0788', 0.00, 'متاح', 'لم يتم البدء'),
(37, 20, 3, '1725', 0.00, 'متاح', 'لم يتم البدء'),
(38, 20, 3, '5442', 0.00, 'متاح', 'لم يتم البدء'),
(39, 20, 2, '1259', 0.00, 'متاح', 'لم يتم البدء'),
(40, 20, 2, '6593', 0.00, 'متاح', 'لم يتم البدء'),
(41, 20, 1, '3348', 0.00, 'متاح', 'لم يتم البدء'),
(42, 20, 1, '7262', 0.00, 'متاح', 'لم يتم البدء'),
(43, 20, 1, '3689', 0.00, 'متاح', 'لم يتم البدء'),
(44, 20, 1, '1566', 0.00, 'متاح', 'لم يتم البدء'),
(45, 20, 1, '1320', 0.00, 'متاح', 'لم يتم البدء'),
(46, 12, 1, '1755', 0.00, 'متاح', 'لم يتم البدء'),
(47, 12, 1, '0669', 0.00, 'متاح', 'لم يتم البدء'),
(48, 12, 1, '5680', 0.00, 'متاح', 'لم يتم البدء'),
(49, 12, 1, '8279', 0.00, 'متاح', 'لم يتم البدء'),
(50, 12, 2, '8409', 0.00, 'متاح', 'لم يتم البدء'),
(51, 12, 2, '1863', 0.00, 'متاح', 'لم يتم البدء'),
(52, 12, 3, '1598', 0.00, 'متاح', 'لم يتم البدء'),
(53, 12, 3, '3712', 0.00, 'متاح', 'لم يتم البدء'),
(54, 12, 3, '5718', 0.00, 'متاح', 'لم يتم البدء'),
(55, 12, 3, '7933', 0.00, 'متاح', 'لم يتم البدء'),
(56, 12, 3, '8142', 0.00, 'متاح', 'لم يتم البدء'),
(57, 12, 3, '5251', 0.00, 'متاح', 'لم يتم البدء'),
(58, 12, 3, '8004', 0.00, 'متاح', 'لم يتم البدء'),
(59, 12, 3, '7482', 0.00, 'متاح', 'لم يتم البدء'),
(60, 12, 3, '9763', 0.00, 'متاح', 'لم يتم البدء'),
(61, 12, 3, '2291', 0.00, 'متاح', 'لم يتم البدء'),
(62, 8, 1, '4305', 0.00, 'متاح', 'لم يتم البدء'),
(63, 8, 1, '3394', 0.00, 'متاح', 'لم يتم البدء'),
(64, 8, 1, '6359', 0.00, 'متاح', 'لم يتم البدء'),
(65, 8, 1, '1594', 0.00, 'متاح', 'لم يتم البدء'),
(66, 8, 1, '8015', 0.00, 'متاح', 'لم يتم البدء'),
(67, 8, 2, '0752', 0.00, 'متاح', 'لم يتم البدء'),
(68, 8, 2, '8833', 0.00, 'متاح', 'لم يتم البدء'),
(69, 8, 2, '1930', 0.00, 'متاح', 'لم يتم البدء'),
(70, 8, 2, '4393', 0.00, 'متاح', 'لم يتم البدء'),
(71, 8, 2, '6429', 0.00, 'متاح', 'لم يتم البدء'),
(72, 8, 3, '2448', 0.00, 'متاح', 'لم يتم البدء'),
(73, 8, 3, '3129', 0.00, 'متاح', 'لم يتم البدء'),
(74, 8, 3, '2031', 0.00, 'متاح', 'لم يتم البدء'),
(75, 8, 3, '0568', 0.00, 'متاح', 'لم يتم البدء'),
(76, 8, 3, '7001', 0.00, 'متاح', 'لم يتم البدء'),
(77, 8, 1, '3044', 0.00, 'متاح', 'لم يتم البدء'),
(78, 8, 1, '7142', 0.00, 'متاح', 'لم يتم البدء'),
(79, 8, 1, '0477', 0.00, 'متاح', 'لم يتم البدء'),
(80, 8, 1, '6726', 0.00, 'متاح', 'لم يتم البدء'),
(81, 8, 1, '2339', 0.00, 'متاح', 'لم يتم البدء'),
(82, 8, 2, '4610', 0.00, 'متاح', 'لم يتم البدء'),
(83, 8, 2, '0511', 0.00, 'متاح', 'لم يتم البدء'),
(84, 8, 2, '5831', 0.00, 'متاح', 'لم يتم البدء'),
(85, 8, 2, '3833', 0.00, 'متاح', 'لم يتم البدء'),
(86, 8, 2, '5693', 0.00, 'متاح', 'لم يتم البدء'),
(87, 8, 3, '7842', 0.00, 'متاح', 'لم يتم البدء'),
(88, 8, 3, '5245', 0.00, 'متاح', 'لم يتم البدء'),
(89, 8, 3, '0001', 0.00, 'متاح', 'لم يتم البدء'),
(90, 8, 3, '6724', 0.00, 'متاح', 'لم يتم البدء'),
(91, 8, 3, '6620', 0.00, 'متاح', 'لم يتم البدء'),
(92, 22, 1, '9347', 0.00, 'متاح', 'لم يتم البدء'),
(93, 22, 1, '0006', 0.00, 'متاح', 'لم يتم البدء'),
(94, 22, 2, '0799', 0.00, 'متاح', 'لم يتم البدء'),
(95, 22, 2, '0461', 0.00, 'متاح', 'لم يتم البدء'),
(96, 22, 3, '6085', 0.00, 'متاح', 'لم يتم البدء'),
(97, 22, 3, '1021', 0.00, 'متاح', 'لم يتم البدء');

-- --------------------------------------------------------

--
-- بنية الجدول `property_types`
--

CREATE TABLE `property_types` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `property_usage` enum('سكني','تجاري') NOT NULL,
  `unit_type` varchar(11) DEFAULT NULL,
  `land_area` decimal(10,2) DEFAULT NULL,
  `building_area` decimal(10,2) DEFAULT NULL,
  `floors` int(11) DEFAULT NULL,
  `bedrooms` int(11) DEFAULT NULL,
  `halls` int(11) DEFAULT NULL,
  `bathrooms` int(11) DEFAULT NULL,
  `kitchen` varchar(255) DEFAULT NULL,
  `plan_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `living_rooms` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `property_types`
--

INSERT INTO `property_types` (`id`, `name`, `property_usage`, `unit_type`, `land_area`, `building_area`, `floors`, `bedrooms`, `halls`, `bathrooms`, `kitchen`, `plan_image`, `created_at`, `living_rooms`) VALUES
(1, 'A2', 'سكني', 'فيلا', 300.00, 290.00, 2, 4, 2, 5, '1', NULL, '2024-10-02 23:59:21', 0),
(2, 'A2', 'سكني', 'فيلا', 300.00, 290.00, 2, 4, 2, 5, '1', NULL, '2024-10-02 23:59:27', 0),
(3, 'A1', 'سكني', 'فيلا', 200.00, 190.00, 2, 4, 1, 4, '1', '66fe8af4da6aa_villa_14 (1).jpg', '2024-10-03 12:15:48', 1);

-- --------------------------------------------------------

--
-- بنية الجدول `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `reservation_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `reservations`
--

INSERT INTO `reservations` (`id`, `employee_id`, `project_id`, `reservation_date`, `created_at`) VALUES
(1, 1, 1, '2024-09-23', '2024-09-22 22:00:51'),
(2, 3, 1, '2024-09-23', '2024-09-22 22:00:51');

-- --------------------------------------------------------

--
-- بنية الجدول `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `sale_date` date NOT NULL,
  `amount` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `units_sold` int(11) DEFAULT 1,
  `reservations` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `sales`
--

INSERT INTO `sales` (`id`, `employee_id`, `project_id`, `sale_date`, `amount`, `created_at`, `units_sold`, `reservations`) VALUES
(1, 3, 1, '2024-09-23', 5, '2024-09-22 22:04:07', 1, 3),
(2, 6, 1, '2024-09-22', 9, '2024-09-22 22:04:07', 1, 1),
(3, 3, 9, '2024-09-22', 9, '2024-09-22 22:04:07', 1, 2);

-- --------------------------------------------------------

--
-- بنية الجدول `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `role` enum('admin','employee') NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `daily_call_target` int(11) DEFAULT NULL,
  `monthly_sales_target` int(11) DEFAULT NULL,
  `monthly_visit_target` int(11) DEFAULT NULL,
  `monthly_call_target` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `full_name`, `email`, `phone`, `role`, `project_id`, `created_at`, `daily_call_target`, `monthly_sales_target`, `monthly_visit_target`, `monthly_call_target`) VALUES
(1, 'Asim', '$2y$10$JIu/2E4Uk44D8FJ0ZR4m1esQkTjOJnr/axs600oOUp.s60htzKWr.', 'Asim Admin', 'asim@talad.com', '1234567890', 'admin', NULL, '2024-09-22 12:50:05', NULL, 0, 0, 0),
(3, 'waled', '$2y$10$mQuh/O3YTJm0wtw27dRWXuPXBqIutJkIGKpeYNSv6baAvyoOy13Aq', 'وليد', 'asim1a@hotmail.com', '0599115017', 'employee', 1, '2024-09-22 16:26:07', 50, 7, 4, 300),
(5, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'مدير النظام', 'admin@telad.com', '', 'admin', NULL, '2024-09-22 20:54:06', NULL, NULL, NULL, 0),
(6, 'employee1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'موظف واحد', 'employee1@telad.com', '', 'employee', 1, '2024-09-22 20:54:06', 20, 28, 15, 0),
(9, 'Alzubaidi', '$2y$10$kI3JBEKO8JdWoFfx3g9VY.sohgTWjOwFKPqtiaY3mXMQJQZJok6cC', 'عاصم الزبيدي', 'asim1a@hotmail.com', '0599115017', 'employee', 1, '2024-09-26 23:39:52', 30, 15, 4, 800);

-- --------------------------------------------------------

--
-- بنية الجدول `visits`
--

CREATE TABLE `visits` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `visit_date` datetime NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `department` varchar(100) DEFAULT NULL,
  `contact_name` varchar(100) DEFAULT NULL,
  `contact_phone` varchar(20) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `recommendations` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `project_id` int(11) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `visits`
--

INSERT INTO `visits` (`id`, `employee_id`, `visit_date`, `company_name`, `department`, `contact_name`, `contact_phone`, `description`, `recommendations`, `created_at`, `project_id`, `client_id`, `notes`) VALUES
(9, 3, '2024-09-22 23:32:05', 'شركة الكهرباء السعودية', 'التواصل الداخلي', 'فراس سمير', '55555555', 'رحت لهم وطردوني مع الباب', 'مانروح لهم مره ثانيه', '2024-09-22 21:46:15', 1, NULL, 'بدون ما نكثر هرج '),
(10, 5, '2024-09-22 23:32:05', 'شركة الكهرباء السعودية', 'التواصل الداخلي', 'فراس سمير', '55555555', 'رحت لهم وطردوني مع الباب', 'مانروح لهم مره ثانيه', '2024-09-22 21:46:15', 2, NULL, 'بدون ما نكثر هرج '),
(11, 1, '2024-09-22 23:32:05', 'شركة الكهرباء السعودية', 'التواصل الداخلي', 'فراس سمير', '55555555', 'رحت لهم وطردوني مع الباب', 'مانروح لهم مره ثانيه', '2024-09-22 21:46:15', 1, NULL, 'بدون ما نكثر هرج '),
(12, 3, '2024-09-24 00:00:00', '', NULL, NULL, NULL, NULL, NULL, '2024-09-23 01:46:16', NULL, 6, 'هلا هلا'),
(13, 3, '2024-09-24 00:00:00', '', NULL, NULL, NULL, NULL, NULL, '2024-09-23 12:40:07', NULL, 12, 'طردوني'),
(14, 3, '0000-00-00 00:00:00', '', NULL, NULL, NULL, NULL, NULL, '2024-09-23 13:00:33', NULL, 0, 'وليد'),
(15, 3, '0000-00-00 00:00:00', '', NULL, NULL, NULL, NULL, NULL, '2024-09-23 13:02:05', NULL, 0, 'وليد'),
(16, 3, '0000-00-00 00:00:00', '', NULL, NULL, NULL, NULL, NULL, '2024-09-23 13:02:09', NULL, 0, 'وليد'),
(17, 3, '0000-00-00 00:00:00', '', NULL, NULL, NULL, NULL, NULL, '2024-09-23 13:03:02', NULL, 0, 'وليد الزبيدي'),
(18, 3, '0000-00-00 00:00:00', '', NULL, NULL, NULL, NULL, NULL, '2024-09-23 13:10:48', NULL, 0, 'وليد الزبيدي'),
(19, 3, '0000-00-00 00:00:00', '', NULL, NULL, NULL, NULL, NULL, '2024-09-23 13:11:26', NULL, 0, 'وليد الزبيدي'),
(20, 3, '2024-09-20 00:00:00', '', NULL, NULL, NULL, NULL, NULL, '2024-09-23 13:19:13', NULL, 12, 'يلبليل'),
(21, 3, '2024-09-17 00:00:00', '', NULL, NULL, NULL, NULL, NULL, '2024-09-23 13:19:23', NULL, 12, 'سيشسيبيسب'),
(22, 3, '2024-09-18 00:00:00', 'درة السدن', 'وليد', 'وليد الزبيدي', 'وليد الزبيدي', 'waled@telad.com', 'بلابلابلا', '2024-09-23 14:29:16', NULL, 10, 'هلا هلا'),
(23, 3, '2024-09-19 00:00:00', 'عصامي', 'الادارة', 'وليد', '055555', 'زيارة لا توصف', 'لا', '2024-09-23 14:30:12', NULL, 10, 'بدون'),
(24, 3, '2024-09-22 00:00:00', 'عصامي', 'الادارة', 'وليد', '055555', 'زيارة لا توصف', 'لا', '2024-09-23 14:52:55', NULL, 12, ''),
(25, 3, '2024-09-24 00:00:00', 'شركة الكهرباء', 'الاتصالات الادارية', 'رهف', '059994854', 'كانت حمااس', 'نعيدها مره ثانية في أسرع وقت', '2024-09-25 00:01:55', NULL, 30, 'الزيارة تفوز والله');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attachments`
--
ALTER TABLE `attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `calls`
--
ALTER TABLE `calls`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `client_tasks`
--
ALTER TABLE `client_tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `communication`
--
ALTER TABLE `communication`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `potential_client_id` (`potential_client_id`);

--
-- Indexes for table `communications`
--
ALTER TABLE `communications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assigned_to` (`assigned_to`);

--
-- Indexes for table `lost_opportunities`
--
ALTER TABLE `lost_opportunities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `potential_clients`
--
ALTER TABLE `potential_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assigned_to` (`assigned_to`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project_property_types`
--
ALTER TABLE `project_property_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `property_type_id` (`property_type_id`);

--
-- Indexes for table `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `property_type_id` (`property_type_id`);

--
-- Indexes for table `property_types`
--
ALTER TABLE `property_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `visits`
--
ALTER TABLE `visits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attachments`
--
ALTER TABLE `attachments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `calls`
--
ALTER TABLE `calls`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `client_tasks`
--
ALTER TABLE `client_tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `communication`
--
ALTER TABLE `communication`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `communications`
--
ALTER TABLE `communications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `lost_opportunities`
--
ALTER TABLE `lost_opportunities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `potential_clients`
--
ALTER TABLE `potential_clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `project_property_types`
--
ALTER TABLE `project_property_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `properties`
--
ALTER TABLE `properties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `property_types`
--
ALTER TABLE `property_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `visits`
--
ALTER TABLE `visits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- قيود الجداول المُلقاة.
--

--
-- قيود الجداول `attachments`
--
ALTER TABLE `attachments`
  ADD CONSTRAINT `attachments_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- قيود الجداول `calls`
--
ALTER TABLE `calls`
  ADD CONSTRAINT `calls_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`);

--
-- قيود الجداول `client_tasks`
--
ALTER TABLE `client_tasks`
  ADD CONSTRAINT `client_tasks_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- قيود الجداول `communication`
--
ALTER TABLE `communication`
  ADD CONSTRAINT `communication_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `communication_ibfk_2` FOREIGN KEY (`potential_client_id`) REFERENCES `potential_clients` (`id`);

--
-- قيود الجداول `communications`
--
ALTER TABLE `communications`
  ADD CONSTRAINT `communications_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `communications_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `potential_clients` (`id`);

--
-- قيود الجداول `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customers_ibfk_1` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`);

--
-- قيود الجداول `potential_clients`
--
ALTER TABLE `potential_clients`
  ADD CONSTRAINT `potential_clients_ibfk_1` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`);

--
-- قيود الجداول `project_property_types`
--
ALTER TABLE `project_property_types`
  ADD CONSTRAINT `project_property_types_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `project_property_types_ibfk_2` FOREIGN KEY (`property_type_id`) REFERENCES `property_types` (`id`) ON DELETE CASCADE;

--
-- قيود الجداول `properties`
--
ALTER TABLE `properties`
  ADD CONSTRAINT `properties_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `properties_ibfk_2` FOREIGN KEY (`property_type_id`) REFERENCES `property_types` (`id`) ON DELETE CASCADE;

--
-- قيود الجداول `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`);

--
-- قيود الجداول `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `sales_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`);

--
-- قيود الجداول `visits`
--
ALTER TABLE `visits`
  ADD CONSTRAINT `visits_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
