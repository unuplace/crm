-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 25 سبتمبر 2024 الساعة 18:57
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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `customers`
--

INSERT INTO `customers` (`id`, `name`, `email`, `phone`, `monthly_commitment`, `bank`, `sector`, `assigned_to`, `created_at`) VALUES
(1, 'عميل طازه', 'asim1a@hotmail.com', '9999999', 1000.00, 'الراجحي', 'حكومي', 3, '2024-09-25 16:44:43');

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
  `email` varchar(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `potential_clients`
--

INSERT INTO `potential_clients` (`id`, `name`, `phone`, `salary`, `monthly_commitment`, `bank`, `sector`, `status`, `notes`, `contact_date`, `assigned_to`, `created_at`, `email`) VALUES
(30, 'عميل جديد', '059999999', 10000.00, 2000.00, 'الراجحي', 'عسكري', 'مهتم', 'لايوجد', '0000-00-00', 3, '2024-09-24 19:05:43', 'engineer@engineer.com'),
(31, 'احمد', '0999599993', 10000.00, 200.00, 'البنك', 'عسكري', 'مهتم', 'مهتم', '0000-00-00', 3, '2024-09-25 00:04:10', 'asim1a@hotmail.com'),
(32, 'عميل طازه', '9999999', 40000.00, 1000.00, 'الراجحي', 'حكومي', 'تم البيع', 'بدون ب', '0000-00-00', 3, '2024-09-25 13:31:12', 'asim1a@hotmail.com');

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
(19, 'مشروع الدمام', '', NULL, 0, 0, 0, '2024-09-22 21:28:56', 'برج سكني في الدمام', '2023-05-01', '2024-11-30', '');

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
  `monthly_sales_target` decimal(10,2) DEFAULT 0.00,
  `monthly_visit_target` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `full_name`, `email`, `phone`, `role`, `project_id`, `created_at`, `daily_call_target`, `monthly_sales_target`, `monthly_visit_target`) VALUES
(1, 'Asim', '$2y$10$JIu/2E4Uk44D8FJ0ZR4m1esQkTjOJnr/axs600oOUp.s60htzKWr.', 'Asim Admin', 'asim@talad.com', '1234567890', 'admin', NULL, '2024-09-22 12:50:05', NULL, 0.00, 0),
(3, 'waled', '$2y$10$mQuh/O3YTJm0wtw27dRWXuPXBqIutJkIGKpeYNSv6baAvyoOy13Aq', 'وليد', 'asim1a@hotmail.com', '0599115017', 'employee', 1, '2024-09-22 16:26:07', 50, 7.00, 4),
(5, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'مدير النظام', 'admin@telad.com', '', 'admin', NULL, '2024-09-22 20:54:06', NULL, NULL, NULL),
(6, 'employee1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'موظف واحد', 'employee1@telad.com', '', 'employee', 1, '2024-09-22 20:54:06', 20, 28.00, 15),
(7, 'employee2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'موظف اثنين', 'employee2@telad.com', '', 'employee', 2, '2024-09-22 20:54:06', 25, 16.00, 20);

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
-- Indexes for table `calls`
--
ALTER TABLE `calls`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

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
-- AUTO_INCREMENT for table `calls`
--
ALTER TABLE `calls`
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
-- AUTO_INCREMENT for table `potential_clients`
--
ALTER TABLE `potential_clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `visits`
--
ALTER TABLE `visits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- قيود الجداول المُلقاة.
--

--
-- قيود الجداول `calls`
--
ALTER TABLE `calls`
  ADD CONSTRAINT `calls_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`);

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
