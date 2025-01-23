DROP DATABASE IF EXISTS `Bank`;

CREATE DATABASE `Bank`;

USE `Bank`;

-- Dump of table Accounts
-- ------------------------------------------------------------

DROP TABLE IF EXISTS `Accounts`;

CREATE TABLE `Accounts` (
  `CardNumber` CHAR(16) NOT NULL, 
  `PIN` CHAR(4) DEFAULT NULL,   
  `Balance` DECIMAL(15, 2) DEFAULT NULL,
  PRIMARY KEY (`CardNumber`)      
);

LOCK TABLES `Accounts` WRITE;

DROP TABLE IF EXISTS `Transactions`;

CREATE TABLE `Transactions` (
  `TransactionId` INT AUTO_INCREMENT PRIMARY KEY, 
  `CardNumber` CHAR(16) NOT NULL,                 -- Foreign key to reference Accounts
  `Date` DATE DEFAULT NULL,                    
  `PreBalance` DECIMAL(15, 2) DEFAULT NULL,       -- Balance before the transaction
  `NewBalance` DECIMAL(15, 2) DEFAULT NULL,       -- Balance after the transaction
  FOREIGN KEY (`CardNumber`) REFERENCES `Accounts`(`CardNumber`) 
  );

CREATE TABLE `NetworKSwitchLogs` (
  `SwitchId` INT AUTO_INCREMENT PRIMARY KEY, 
  `CardNumber` CHAR(16) NOT NULL,                 -- Foreign key to reference Accounts
  `Date` DATE DEFAULT NULL,                      
  `Balance` DECIMAL(15, 2) DEFAULT NULL,       -- Current Balance
  `Destination` CHAR(16) NOT NULL,		-- Direction, either NETWORK or ATM
  FOREIGN KEY (`CardNumber`) REFERENCES `Accounts`(`CardNumber`) 
);

LOCK TABLES `Accounts` WRITE;

INSERT INTO `Accounts` (`CardNumber`, `PIN`, `Balance`)
VALUES
  ('1234567812345678', '1234', 600.00),
  ('9999999999999999', '9999', 10000.00),
  ('2025202520252025', '2025', 1.20),
  ('3949394939493949', '3949', 39.49),
  ('1111111111111111', '1111', 456.00),
  ('1258125812581258', '1258', 2222.00);

UNLOCK TABLES;
