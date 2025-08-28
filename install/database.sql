-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 24, 2023 at 02:29 PM
-- Server version: 10.3.28-MariaDB
-- PHP Version: 7.4.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lb164`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `al_id` int(11) NOT NULL,
  `al_log` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `al_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `api_keys`
--

CREATE TABLE `api_keys` (
  `id` int(11) NOT NULL,
  `key` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `controller` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `endpoints` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ignore_limits` tinyint(1) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `api_limits`
--

CREATE TABLE `api_limits` (
  `id` int(11) NOT NULL,
  `uri` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `count` int(11) NOT NULL,
  `hour_started` int(11) NOT NULL,
  `api_key` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `api_logs`
--

CREATE TABLE `api_logs` (
  `id` int(11) NOT NULL,
  `api_key` varchar(40) NOT NULL,
  `date` date NOT NULL,
  `count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `app_settings`
--

CREATE TABLE `app_settings` (
  `as_id` int(11) NOT NULL,
  `as_name` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `as_value` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `app_settings`
--

INSERT INTO `app_settings` (`as_id`, `as_name`, `as_value`) VALUES
(1, 'license_code_format', '{[Z]}{[Z]}{[Z]}{[Z]}{[X]} -{[Z]}{[Z]}{[Z]}{[Z]}{[X]} -{[Z]}{[Z]}{[Z]}{[Z]}{[X]} -{[Z]}{[Z]}{[Z]}{[Z]}{[X]}'),
(2, 'keydera_theme', 'material'),
(3, 'envato_api_token', NULL),
(4, 'server_email', 'lb@keydera.app'),
(5, 'blacklisted_ips', NULL),
(6, 'blacklisted_domains', NULL),
(7, 'api_rate_limit_method', 'ip_address'),
(8, 'api_rate_limit', NULL),
(9, 'license_expiring', '<p>Hello,&nbsp;</p><p>Your <strong>{[product]}</strong> license is expiring today, please renew your license if you wish to continue using {[product]}.</p><p><i>Company</i></p>'),
(10, 'support_expiring', '<p>Hello,&nbsp;</p><p>Your <strong>{[product]}</strong> support period is ending today, please renew support to continue receiving a better prioritized support.</p><p><i>Company</i></p>'),
(11, 'updates_expiring', '<p>Hello,&nbsp;</p><p>Your <strong>{[product]}</strong> updates period is ending today, please renew updates and never miss out on our future releases.</p><p><i>Company</i></p>'),
(12, 'new_update', '<p>Hello,&nbsp;</p><p>We are excited to announce our new <strong>{[version]}</strong> update for <strong>{[product]}</strong>, grab the new version now and try it out yourself!&nbsp;</p><p><i>Company</i></p>'),
(13, 'license_expiring_enable', '0'),
(14, 'support_expiring_enable', '0'),
(15, 'updates_expiring_enable', '0'),
(16, 'new_update_enable', '0'),
(17, 'failed_activation_logs', '1'),
(18, 'failed_update_download_logs', '1'),
(19, 'auto_domain_blacklist', NULL),
(20, 'auto_ip_blacklist', NULL),
(21, 'server_timezone', 'Asia/Kolkata'),
(22, 'email_method', 'default'),
(23, 'smtp_connection', 'tls'),
(24, 'smtp_authentication', '0'),
(25, 'smtp_username', NULL),
(26, 'smtp_password', NULL),
(27, 'smtp_host', NULL),
(28, 'smtp_port', NULL),
(29, 'envato_use_limit', NULL),
(30, 'envato_parallel_use_limit', NULL),
(31, 'whitelist_ips', NULL),
(32, 'auto_deactivate_activations', '0'),
(33, 'auto_add_licensed_domain', '0');

-- --------------------------------------------------------

--
-- Table structure for table `auth_users`
--

CREATE TABLE `auth_users` (
  `au_id` int(11) NOT NULL,
  `au_uid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `au_username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `au_password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `au_email` varchar(155) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `au_name` varchar(155) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `au_date_created` date NOT NULL,
  `au_reset_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `au_reset_exp` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

INSERT INTO `auth_users` (`au_id`, `au_uid`, `au_username`, `au_password`, `au_email`, `au_name`, `au_date_created`, `au_reset_key`, `au_reset_exp`) VALUES
(1, 'lbgstyu7g12', 'Keydera', '$2y$10$iZo1naAkQVU0CI1/B5dPNuzazTEwHpFFWB41Th8GjVt3HUZFAo41e', 'admin@example.com', 'Administrator', '2025-08-13', '$2y$10$YhAg6pMz67Mg0z2xZt60bexJJjFoP20zYgRcGyzJDIut/6pyJbxrm', '2022-06-24 15:20:29');

--
-- Table structure for table `cron_mails`
--

CREATE TABLE `cron_mails` (
  `id` int(11) NOT NULL,
  `license` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_email` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mail_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_sent` datetime NOT NULL DEFAULT current_timestamp(),
  `version` varchar(155) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_activations`
--

CREATE TABLE `product_activations` (
  `pi_id` int(11) NOT NULL,
  `pi_product` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pi_iid` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pi_client` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `pi_license_code` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pi_url` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `pi_ip` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `pi_date` datetime NOT NULL DEFAULT current_timestamp(),
  `pi_agent` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pi_isvalid` tinyint(1) NOT NULL,
  `pi_isactive` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_details`
--

CREATE TABLE `product_details` (
  `pd_id` int(11) NOT NULL,
  `pd_pid` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `envato_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pd_name` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `pd_details` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `license_update` tinyint(1) NOT NULL,
  `serve_latest_updates` tinyint(4) NOT NULL DEFAULT 0,
  `pd_status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_licenses`
--

CREATE TABLE `product_licenses` (
  `id` int(11) NOT NULL,
  `pid` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `license_code` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `license_type` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_envato` tinyint(4) DEFAULT NULL,
  `added_on` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `client` varchar(155) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(155) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comments` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ips` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `domains` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supported_till` datetime DEFAULT NULL,
  `updates_till` datetime DEFAULT NULL,
  `expiry` datetime DEFAULT NULL,
  `expiry_days` int(11) DEFAULT NULL,
  `uses` int(11) DEFAULT NULL,
  `uses_left` int(11) DEFAULT NULL,
  `parallel_uses` int(11) DEFAULT NULL,
  `parallel_uses_left` int(11) DEFAULT NULL,
  `validity` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_versions`
--

CREATE TABLE `product_versions` (
  `id` int(11) NOT NULL,
  `vid` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pid` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `version` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `release_date` date NOT NULL,
  `summary` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `changelog` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `main_file` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sql_file` varchar(155) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `update_downloads`
--

CREATE TABLE `update_downloads` (
  `id` int(11) NOT NULL,
  `did` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vid` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `download_date` datetime NOT NULL DEFAULT current_timestamp(),
  `isvalid` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `api_keys`
--
ALTER TABLE `api_keys`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key` (`key`);

--
-- Indexes for table `api_limits`
--
ALTER TABLE `api_limits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `api_logs`
--
ALTER TABLE `api_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_settings`
--
ALTER TABLE `app_settings`
  ADD PRIMARY KEY (`as_id`);

--
-- Indexes for table `cron_mails`
--
ALTER TABLE `cron_mails`
  ADD PRIMARY KEY (`id`),
  ADD KEY `license` (`license`);

--
-- Indexes for table `product_activations`
--
ALTER TABLE `product_activations`
  ADD PRIMARY KEY (`pi_id`),
  ADD UNIQUE KEY `pi_iid` (`pi_iid`);

--
-- Indexes for table `product_details`
--
ALTER TABLE `product_details`
  ADD PRIMARY KEY (`pd_id`),
  ADD UNIQUE KEY `pd_pid` (`pd_pid`);

--
-- Indexes for table `product_licenses`
--
ALTER TABLE `product_licenses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `license_code` (`license_code`);

--
-- Indexes for table `product_versions`
--
ALTER TABLE `product_versions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vid` (`vid`);

--
-- Indexes for table `update_downloads`
--
ALTER TABLE `update_downloads`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `did` (`did`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `api_keys`
--
ALTER TABLE `api_keys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `api_limits`
--
ALTER TABLE `api_limits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `api_logs`
--
ALTER TABLE `api_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `app_settings`
--
ALTER TABLE `app_settings`
  MODIFY `as_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `cron_mails`
--
ALTER TABLE `cron_mails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_activations`
--
ALTER TABLE `product_activations`
  MODIFY `pi_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_details`
--
ALTER TABLE `product_details`
  MODIFY `pd_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_licenses`
--
ALTER TABLE `product_licenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_versions`
--
ALTER TABLE `product_versions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `update_downloads`
--
ALTER TABLE `update_downloads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

