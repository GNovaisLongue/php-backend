-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 18, 2023 at 03:14 AM
-- Server version: 10.5.20-MariaDB
-- PHP Version: 7.3.33
SET
  SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

START TRANSACTION;

SET
  time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;

/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;

/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;

/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `id20249478_juniortest_ecommerce`
--
-- --------------------------------------------------------
--
-- Table structure for table `product`
--
CREATE TABLE
  IF NOT EXISTS `product` (
    `sku` VARCHAR(25) PRIMARY KEY NOT NULL UNIQUE,
    `name` VARCHAR(100) NOT NULL,
    `price` VARCHAR(25) NOT NULL,
    `product_type` VARCHAR(25) NOT NULL,
    `product_attribute` VARCHAR(25) NOT NULL
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;

--
-- Dumping data for table `product`
--
INSERT INTO
  `product` (`sku`, `name`, `price`, `product_type`, `product_attribute`)
VALUES
  ('SKUTestSKU000', 'NameTest000', '25', 'DVD', '200'),
  ('SKUTestSKU001', 'NameTest001', '80', 'Book', '200'),
  ('SKUTestSKU002', 'NameTestTable', '199.99', 'Furniture', '123x123x123'),
  ('SKUTestSKU003', 'NameTest002', '123.59', 'Furniture', '200x200x200');

--
-- Indexes for dumped tables
--
--
-- Indexes for table `product`
--
-- --------------------------------------------------------
--
-- Table structure for table `users`
--
CREATE TABLE
  IF NOT EXISTS `user` (
    `id` BIGINT PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `role` VARCHAR(20) NOT NULL,
    `date_created` datetime NOT NULL,
    `date_last_updated` datetime NOT NULL
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;

--
-- Dumping data for table `user`
--
INSERT INTO
  `user` (`name`, `email`, `role`, `date_created`, `date_last_updated`)
VALUES
  ('Name test 001', 'test01test@test.com', 'admin', '2022-08-31 11:23:12', '2023-10-31 12:05:14'),
  ('Name test 002', 'test02test@test.com', 'user', '2020-08-31 11:23:12', '2023-10-31 12:05:14'),
  ('Name test 003', 'test03test@test.com', 'admin', '2022-08-31 11:23:12', '2022-10-31 12:05:14'),
  ('Ambrosio Nascimento', 'ambrosionasci@test.com', 'verified', '2020-08-31 11:23:12', '2023-10-31 12:05:14'),
  ('Benjamin Carmine', 'b.carmine@test.com', 'verified', '2021-08-31 00:00:00', '2023-10-31 12:05:14');

--
-- Indexes for dumped tables
--
-- ----
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;

/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;