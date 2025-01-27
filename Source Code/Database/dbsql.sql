CREATE DATABASE `Bank`;

USE `Bank`;

CREATE TABLE `Accounts` (
  `CardNumber` CHAR(16) NOT NULL, 
  `PIN` CHAR(4) DEFAULT NULL,   
  `Balance` DECIMAL(15, 2) DEFAULT NULL,
  `ExpiryDate` DATE DEFAULT NULL,
  PRIMARY KEY (`CardNumber`)      
);

CREATE TABLE `Transactions` (
  `TransactionId` INT AUTO_INCREMENT PRIMARY KEY, 
  `CardNumber` CHAR(16) NOT NULL,                 
  `Date` DATE DEFAULT NULL,                    
  `PreBalance` DECIMAL(15, 2) DEFAULT NULL,       
  `NewBalance` DECIMAL(15, 2) DEFAULT NULL,       
  FOREIGN KEY (`CardNumber`) REFERENCES `Accounts`(`CardNumber`) 
  );

CREATE TABLE `NetworkSimulatorLogs` (
  `SwitchId` INT AUTO_INCREMENT PRIMARY KEY, 
  `CardNumber` CHAR(16) NOT NULL,       
  `TransactionId` CHAR(16) NOT NULL,
  `Date` DATE DEFAULT NULL,                      
  `Balance` DECIMAL(15, 2) DEFAULT NULL,       
  FOREIGN KEY (`CardNumber`) REFERENCES `Accounts`(`CardNumber`),
  FOREIGN KEY (`TransactionId`) REFERENCES `Transactions`(`TransactionId`) 
);

CREATE TABLE `TransactionSwitchLogs` (
  `SwitchId` INT AUTO_INCREMENT PRIMARY KEY, 
  `CardNumber` CHAR(16) NOT NULL,
  `TransactionId` CHAR(16) NOT NULL,
  `Date` DATE DEFAULT NULL,                      
  `Balance` DECIMAL(15, 2) DEFAULT NULL,       
  `Direction` CHAR(16) NOT NULL,		
  FOREIGN KEY (`CardNumber`) REFERENCES `Accounts`(`CardNumber`),
  FOREIGN KEY (`TransactionId`) REFERENCES `Transactions`(`TransactionId`) 
);

INSERT INTO `Accounts` (`CardNumber`, `PIN`, `Balance`, `ExpiryDate`)
VALUES
  ('1234567812345678', '1234', 600.00, '2025-12-31'),
  ('9999999999999999', '9999', 10000.00, '2026-06-30'),
  ('2025202520252025', '2025', 1.20, '2024-03-15'),
  ('3949394939493949', '3949', 39.49, '2027-09-01'),
  ('1111111111111111', '1111', 456.00, '2028-05-20'),
  ('1258125812581258', '1258', 2222.00, '2025-11-11');
