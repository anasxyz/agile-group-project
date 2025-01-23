DROP DATABASE IF EXISTS `Bank`;

CREATE DATABASE `Bank`;

USE `Bank`;

-- Create Accounts Table
DROP TABLE IF EXISTS `Accounts`;

CREATE TABLE `Accounts` (
  `CardNumber` CHAR(16) NOT NULL, 
  `PIN` CHAR(4) DEFAULT NULL,   
  `Balance` DECIMAL(15, 2) DEFAULT NULL,
  PRIMARY KEY (`CardNumber`)      
);

-- Insert Data into Accounts Table
INSERT INTO `Accounts` (`CardNumber`, `PIN`, `Balance`)
VALUES
  ('1234567812345678', '1234', 600.00),
  ('9999999999999999', '9999', 10000.00),
  ('2025202520252025', '2025', 1.20),
  ('3949394939493949', '3949', 39.49),
  ('1111111111111111', '1111', 456.00),
  ('1258125812581258', '1258', 2222.00);

-- Create Transactions Table
DROP TABLE IF EXISTS `Transactions`;

CREATE TABLE `Transactions` (
  `TransactionId` INT AUTO_INCREMENT PRIMARY KEY, 
  `CardNumber` CHAR(16) NOT NULL,                 
  `Date` DATE DEFAULT NULL,                    
  `PreBalance` DECIMAL(15, 2) DEFAULT NULL,      
  `NewBalance` DECIMAL(15, 2) DEFAULT NULL,      
  FOREIGN KEY (`CardNumber`) REFERENCES `Accounts`(`CardNumber`) 
);

-- Create NetworkSwitchLogs Table
DROP TABLE IF EXISTS `NetworKSwitchLogs`;

CREATE TABLE `NetworKSwitchLogs` (
  `SwitchId` INT AUTO_INCREMENT PRIMARY KEY, 
  `CardNumber` CHAR(16) NOT NULL,                 
  `Date` DATE DEFAULT NULL,                      
  `Balance` DECIMAL(15, 2) DEFAULT NULL,       
  `Destination` CHAR(16) NOT NULL,		
  FOREIGN KEY (`CardNumber`) REFERENCES `Accounts`(`CardNumber`) 
);
