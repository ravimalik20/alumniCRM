-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 03, 2018 at 11:12 PM
-- Server version: 5.7.21-0ubuntu0.16.04.1
-- PHP Version: 7.0.25-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `alumnislim`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id_customer` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` enum('current','alumni') COLLATE utf8_unicode_ci NOT NULL,
  `preferred_mail_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `salutation` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `home_street_1` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `home_street_2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `zipcode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `home_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `gender` enum('male','female','other') COLLATE utf8_unicode_ci NOT NULL,
  `birthday` date NOT NULL,
  `school` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `degree` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `major` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `major_year_start` date NOT NULL,
  `major_year_end` date NOT NULL,
  `version_num_customer` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id_customer`, `first_name`, `last_name`, `type`, `preferred_mail_name`, `salutation`, `active`, `home_street_1`, `home_street_2`, `country`, `city`, `state`, `zipcode`, `home_number`, `phone_number`, `email`, `gender`, `birthday`, `school`, `degree`, `major`, `major_year_start`, `major_year_end`, `version_num_customer`) VALUES
(1, 'Ravi', 'Malik', 'current', 'Ravi Malik', 'Mr.', 1, '110 Englewood Avenue', '', 'United States', 'Buffalo', 'New York', '14214', '', '2019324926', 'ravimali@buffalo.edu', 'male', '1992-12-20', 'Engineering', 'Masters', 'Computer Science', '2017-08-27', '2018-12-20', '0'),
(4, 'Ravi', 'Malik', 'current', 'Ravi Malik', 'Mr.', 1, '110 Englewood Avenue', '', 'United States', 'Buffalo', 'New York', '14214', '', '2019324926', 'ravimali@buffalo.edu', 'male', '1992-12-20', 'Engineering and Applied Sciences', 'Masters (MS)', 'Computer Science', '2017-08-27', '2018-12-20', '1');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_admin_user` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `last_login` datetime DEFAULT NULL,
  `login_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_admin_user`, `name`, `email`, `password`, `active`, `last_login`, `login_token`) VALUES
(1, 'Ravi Malik', 'ravimali@buffalo.edu', '$2y$10$K8TzvbD0DxQ8TWjZz3QZ6eLeK.9jJjAYvmC/PFYSrIKImYeQ0.reW', 1, '2018-03-03 19:56:56', 'LaemJFeZ4AMKmNZ28jYR9BO50zLHQxTBI7YrMcqRNcB90Bc9Ua04LOaMnVtetnQb'),
(3, 'Test', 'testuser@buffalo.edu', '$2y$10$fMtTJ99MIAg7UUXlohX3aO4Fw5JWIPWxLpDIF545GI7cpx2riMCH.', 1, '2018-03-02 21:09:56', '6745c16fead4f3972a500d9c94d3645bd727281d31d01ff97e65abeee5bf'),
(5, 'Test User One', 'testone@buffalo.edu', '$2y$10$MUO/G5CYv31jNLoZ6ur66OAmMZBi/4K44sc17kQmuSy3t.HtTKFLy', 1, NULL, NULL),
(6, 'Test', 'test2@buffalo.edu', '$2y$10$CwEUCXIo3MIQILPKUXUenO99E3JVRnaWpcd0G5/O0XqDrc29MkyGm', 1, NULL, NULL),
(7, 'jklasjkasjkl', 'jklasljka@asjljkas.com', '$2y$10$uek4KKPLsRssM5WtRBQ9HOynNvGyWORLHDOTl5HBiL516tsJw/ojG', 1, NULL, NULL),
(8, 'sasasasa', 'sasasasa@asasaasas.com', '$2y$10$JzR9jHyrHnRecdxCLjxeWus81Xj1lUFxkrd.7iSdvP7V4.Ov13Voe', 1, NULL, NULL),
(9, 'asassasa', 'assasasasasaa@sasassasasa.com', '$2y$10$htK1glp/m2jItUgJOFC54OEW8ZbnNHLCEkv13PRpHxj9OlQx7dqIG', 1, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id_customer`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_admin_user`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id_customer` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_admin_user` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
