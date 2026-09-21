
-- --------------------------------------------------------
--
-- Table structure for table `product`
--
CREATE TABLE IF NOT EXISTS `product` (
  `sku` VARCHAR(64) NOT NULL PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `product_type` VARCHAR(20) NOT NULL,
  `product_attribute` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `chk_product_price_positive` CHECK (`price` > 0),
  CONSTRAINT `chk_product_type` CHECK (`product_type` IN ('DVD','Book','Furniture'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `product` (`sku`, `name`, `price`, `product_type`, `product_attribute`) VALUES
('SKUTestSKU000', 'NameTest000', 25.00, 'DVD', '200'),
('SKUTestSKU001', 'NameTest001', 25.00, 'Book', '200'),
('SKUTestSKU002', 'NameTest002', 25.00, 'Furniture', '200x200x200');

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

INSERT INTO
  `user` (`name`, `email`, `role`, `date_created`, `date_last_updated`)
VALUES
  ('Name test 001', 'test01test@test.com', 'admin', '2022-08-31 11:23:12', '2023-10-31 12:05:14'),
  ('Name test 002', 'test02test@test.com', 'user', '2020-08-31 11:23:12', '2023-10-31 12:05:14'),
  ('Name test 003', 'test03test@test.com', 'admin', '2022-08-31 11:23:12', '2022-10-31 12:05:14'),
  ('Ambrosio Nascimento', 'ambrosionasci@test.com', 'verified', '2020-08-31 11:23:12', '2023-10-31 12:05:14'),
  ('Benjamin Carmine', 'b.carmine@test.com', 'verified', '2021-08-31 00:00:00', '2023-10-31 12:05:14');


COMMIT;
