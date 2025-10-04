-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3309
-- Generation Time: Aug 30, 2025 at 11:07 AM
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
-- Database: `msmeglobal`
--

-- --------------------------------------------------------

--
-- Table structure for table `booster_survey`
--

CREATE TABLE `booster_survey` (
  `id` int(11) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `attend_meets` varchar(10) DEFAULT NULL,
  `whatsapp_groups` varchar(10) DEFAULT NULL,
  `sponsor_events` varchar(10) DEFAULT NULL,
  `display_expo` varchar(10) DEFAULT NULL,
  `mentorship` varchar(10) DEFAULT NULL,
  `blog_write` varchar(10) DEFAULT NULL,
  `communication_mode` varchar(20) DEFAULT NULL,
  `collaborate` varchar(10) DEFAULT NULL,
  `best_time_online` varchar(20) DEFAULT NULL,
  `receive_referrals` varchar(10) DEFAULT NULL,
  `featured_website` varchar(10) DEFAULT NULL,
  `paid_promotions` varchar(10) DEFAULT NULL,
  `training_interest` varchar(10) DEFAULT NULL,
  `vip_invites` varchar(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `booster_survey`
--

INSERT INTO `booster_survey` (`id`, `user_email`, `attend_meets`, `whatsapp_groups`, `sponsor_events`, `display_expo`, `mentorship`, `blog_write`, `communication_mode`, `collaborate`, `best_time_online`, `receive_referrals`, `featured_website`, `paid_promotions`, `training_interest`, `vip_invites`, `created_at`, `updated_at`) VALUES
(1, 'testuser@example.com', 'Yes', 'Yes', '', '', '', 'Yes', 'Phone', '', 'Morning', '', 'Yes', '', 'Yes', '', '2025-08-27 18:51:58', '2025-08-27 18:51:58'),
(2, 'bhajaghosh@gmail.com', 'Yes', 'Yes', '', '', '', 'Yes', 'Phone', '', '', '', 'Yes', '', 'No', '', '2025-08-29 18:08:34', '2025-08-29 18:08:34');

-- --------------------------------------------------------

--
-- Table structure for table `business_categories`
--

CREATE TABLE `business_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `business_categories`
--

INSERT INTO `business_categories` (`id`, `name`) VALUES
(1, 'Retail & E-commerce'),
(2, 'Hospitality & Food Services'),
(3, 'Healthcare & Wellness'),
(4, 'Education & Training'),
(5, 'Real Estate & Construction'),
(6, 'Finance & Insurance'),
(7, 'Information Technology & Software'),
(8, 'Manufacturing & Production'),
(9, 'Transport & Logistics'),
(10, 'Media & Entertainment');

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `industries`
--

CREATE TABLE `industries` (
  `id` int(11) NOT NULL,
  `code` varchar(20) DEFAULT NULL,
  `name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `industries`
--

INSERT INTO `industries` (`id`, `code`, `name`) VALUES
(1, 'A', 'Agriculture, Forestry and Fishing'),
(2, 'B', 'Mining and Quarrying'),
(3, 'C', 'Manufacturing'),
(4, 'D', 'Electricity, Gas, Steam and Air Conditioning Supply'),
(5, 'E', 'Water Supply; Sewerage, Waste Management'),
(6, 'F', 'Construction'),
(7, 'G', 'Wholesale and Retail Trade'),
(8, 'H', 'Transportation and Storage'),
(9, 'I', 'Accommodation and Food Service Activities'),
(10, 'J', 'Information and Communication');

-- --------------------------------------------------------

--
-- Table structure for table `logins`
--

CREATE TABLE `logins` (
  `login_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `non_business_users`
--

CREATE TABLE `non_business_users` (
  `user_id` int(11) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `name` varchar(150) NOT NULL,
  `whatsapp` varchar(15) NOT NULL,
  `alternate` varchar(15) DEFAULT NULL,
  `email` varchar(190) DEFAULT NULL,
  `state` varchar(100) NOT NULL,
  `city` varchar(120) NOT NULL,
  `pincode` varchar(6) NOT NULL,
  `dob` date NOT NULL,
  `occupation` varchar(60) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','success','failed') DEFAULT 'pending',
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `promocodes`
--

CREATE TABLE `promocodes` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `discount_type` enum('percentage','fixed') NOT NULL,
  `discount_value` int(11) NOT NULL,
  `max_uses` int(11) DEFAULT 0,
  `used_count` int(11) DEFAULT 0,
  `valid_from` date DEFAULT NULL,
  `valid_until` date DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `promocodes`
--

INSERT INTO `promocodes` (`id`, `code`, `discount_type`, `discount_value`, `max_uses`, `used_count`, `valid_from`, `valid_until`, `is_active`, `created_at`) VALUES
(1, 'SAVE10', 'percentage', 10, 100, 0, NULL, NULL, 1, '2025-08-22 03:58:36'),
(2, 'WELCOME', 'fixed', 50, 50, 0, NULL, NULL, 1, '2025-08-22 03:58:36'),
(3, 'FIRST20', 'percentage', 20, 30, 0, NULL, NULL, 1, '2025-08-22 03:58:36'),
(4, 'NEWUSER', 'fixed', 100, 20, 0, NULL, NULL, 1, '2025-08-22 03:58:36');

-- --------------------------------------------------------

--
-- Table structure for table `requirements`
--

CREATE TABLE `requirements` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `requirement` text NOT NULL,
  `priority` varchar(50) NOT NULL,
  `budget_min` decimal(10,2) NOT NULL,
  `budget_max` decimal(10,2) NOT NULL,
  `quantity` varchar(100) NOT NULL,
  `requirement_type` varchar(100) NOT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `created_at` datetime DEFAULT current_timestamp(),
  `city` varchar(100) DEFAULT 'Unknown'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requirements`
--

INSERT INTO `requirements` (`id`, `email`, `requirement`, `priority`, `budget_min`, `budget_max`, `quantity`, `requirement_type`, `brand`, `message`, `status`, `created_at`, `city`) VALUES
(1, 'user860@example.com', 'Construction', 'Medium', 142162.00, 494452.00, '9', 'Type2', 'BrandA', 'This is a sample message', 'Closed', '2025-08-26 12:24:55', 'Unknown');

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `name`) VALUES
(1, 'Andhra Pradesh'),
(2, 'Arunachal Pradesh'),
(3, 'Assam'),
(4, 'Bihar'),
(5, 'Chhattisgarh'),
(6, 'Goa'),
(7, 'Gujarat'),
(8, 'Haryana'),
(9, 'Himachal Pradesh'),
(10, 'Jharkhand'),
(11, 'Karnataka'),
(12, 'Kerala'),
(13, 'Madhya Pradesh'),
(14, 'Maharashtra'),
(15, 'Manipur'),
(16, 'Meghalaya'),
(17, 'Mizoram'),
(18, 'Nagaland'),
(19, 'Odisha'),
(20, 'Punjab'),
(21, 'Rajasthan'),
(22, 'Sikkim'),
(23, 'Tamil Nadu'),
(24, 'Telangana'),
(25, 'Tripura'),
(26, 'Uttar Pradesh'),
(27, 'Uttarakhand'),
(28, 'West Bengal'),
(29, 'Andaman and Nicobar Islands'),
(30, 'Chandigarh'),
(31, 'Dadra and Nagar Haveli and Daman and Diu'),
(32, 'Delhi'),
(33, 'Jammu and Kashmir'),
(34, 'Ladakh'),
(35, 'Lakshadweep'),
(36, 'Puducherry');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `reference_id` varchar(50) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `blood_group` varchar(5) DEFAULT NULL,
  `whatsapp` varchar(20) DEFAULT NULL,
  `alternate` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `industry` varchar(100) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `pincode` varchar(10) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `plan` varchar(20) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expiry` datetime DEFAULT NULL,
  `reset_token_hash` char(64) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `dob` date DEFAULT NULL,
  `marriage_date` date DEFAULT NULL,
  `entity_status` varchar(50) DEFAULT NULL,
  `entity_name` varchar(255) DEFAULT NULL,
  `nature` varchar(100) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `products` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `gives` text DEFAULT NULL,
  `asks` text DEFAULT NULL,
  `team_size` varchar(50) DEFAULT NULL,
  `branches` varchar(50) DEFAULT NULL,
  `years_business` varchar(50) DEFAULT NULL,
  `turnover` varchar(100) DEFAULT NULL,
  `base_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `promocode` varchar(100) DEFAULT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `logo_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `reference_id`, `name`, `gender`, `blood_group`, `whatsapp`, `alternate`, `email`, `industry`, `category`, `state`, `city`, `pincode`, `photo`, `logo`, `plan`, `amount`, `password_hash`, `reset_token`, `reset_expiry`, `reset_token_hash`, `created_at`, `dob`, `marriage_date`, `entity_status`, `entity_name`, `nature`, `website`, `address`, `products`, `description`, `gives`, `asks`, `team_size`, `branches`, `years_business`, `turnover`, `base_amount`, `discount_amount`, `promocode`, `photo_path`, `logo_path`) VALUES
(2, 'ref123', 'Test User', 'Male', 'B+', '9999999999', '8888888888', 'bhajaghosh@gmail.com', 'IT', 'Software', 'West Bengal', 'Kolkata', '700001', 'default.jpg', 'default_logo.png', 'Free', 0.00, '$2y$10$ICtxJmewtee8R/rJxdPOQ.tZlpm08ZxUx46wc.77X.YQ7rYF3yIpm', NULL, NULL, NULL, '2025-08-25 18:36:27', '2025-08-05', '2025-08-20', 'Proprietorship', 'hello', 'Manufacturing', '', 'ssasas', '', '', '', '', '', '', '2-5', '', 0.00, 0.00, NULL, NULL, NULL),
(3, '9876543210', 'Priyanjan Ghosh', NULL, NULL, '9647158045', '8888888888', 'rahulsubho12345@gmail.com', 'IT', 'SAS', 'West Bengal', 'Kolkata', '700032', NULL, NULL, '0', 1000.00, '$2y$10$1f/ITrqWqH4yR0UwWH1HtOrr4M7wWNHj7atbECZit5cWRc1Crux2O', NULL, NULL, NULL, '2025-08-30 07:06:13', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1000.00, 0.00, '', 'uploads/users/photo_1756537573_5819.png', NULL),
(9, '9876543210', 'Priyanjan Ghosh2', NULL, NULL, '9647158045', '8888888888', 'bhajaghosh2@gmail.com', 'IT', 'SAS', 'West Bengal', 'Kolkata', '700032', NULL, NULL, 'trusted', 1000.00, '$2y$10$98lck/9uZU9N5Z4mKhzoceOoq9UlAaYtrkLZXEIBeTwF4pJbAvpgu', NULL, NULL, NULL, '2025-08-30 07:17:49', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1000.00, 0.00, '', 'uploads/users/photo_1756538269_9556.png', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booster_survey`
--
ALTER TABLE `booster_survey`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_email_unique` (`user_email`),
  ADD KEY `idx_user_email` (`user_email`);

--
-- Indexes for table `business_categories`
--
ALTER TABLE `business_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `state_id` (`state_id`);

--
-- Indexes for table `industries`
--
ALTER TABLE `industries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logins`
--
ALTER TABLE `logins`
  ADD PRIMARY KEY (`login_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `non_business_users`
--
ALTER TABLE `non_business_users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `promocodes`
--
ALTER TABLE `promocodes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `requirements`
--
ALTER TABLE `requirements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_users_reset_token_hash` (`reset_token_hash`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booster_survey`
--
ALTER TABLE `booster_survey`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `business_categories`
--
ALTER TABLE `business_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `industries`
--
ALTER TABLE `industries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `logins`
--
ALTER TABLE `logins`
  MODIFY `login_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `non_business_users`
--
ALTER TABLE `non_business_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `promocodes`
--
ALTER TABLE `promocodes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `requirements`
--
ALTER TABLE `requirements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cities`
--
ALTER TABLE `cities`
  ADD CONSTRAINT `cities_ibfk_1` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `logins`
--
ALTER TABLE `logins`
  ADD CONSTRAINT `logins_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
