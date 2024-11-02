/*
 Navicat Premium Data Transfer

 Source Server         : _localhost8.4
 Source Server Type    : MySQL
 Source Server Version : 80403 (8.4.3)
 Source Host           : localhost:3308
 Source Schema         : realty_tender

 Target Server Type    : MySQL
 Target Server Version : 80403 (8.4.3)
 File Encoding         : 65001

 Date: 02/11/2024 04:24:37
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for realty_apartment
-- ----------------------------
DROP TABLE IF EXISTS `realty_apartment`;
CREATE TABLE `realty_apartment` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `active` tinyint unsigned NOT NULL DEFAULT '1',
  `number` int unsigned NOT NULL,
  `house_id` bigint unsigned NOT NULL,
  `status` tinyint unsigned NOT NULL DEFAULT '0',
  `price` int unsigned NOT NULL,
  `price_discount` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uniq_house_number` (`number`,`house_id`) USING BTREE,
  KEY `fdx_house` (`house_id`) USING BTREE,
  CONSTRAINT `fdx_house` FOREIGN KEY (`house_id`) REFERENCES `realty_house` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of realty_apartment
-- ----------------------------
BEGIN;
INSERT INTO `realty_apartment` (`id`, `active`, `number`, `house_id`, `status`, `price`, `price_discount`) VALUES (23, 1, 111, 6, 1, 111000, 119999);
INSERT INTO `realty_apartment` (`id`, `active`, `number`, `house_id`, `status`, `price`, `price_discount`) VALUES (35, 1, 1, 2, 1, 10000, 9999);
INSERT INTO `realty_apartment` (`id`, `active`, `number`, `house_id`, `status`, `price`, `price_discount`) VALUES (36, 1, 409, 7, 1, 1000000, 999999);
COMMIT;

-- ----------------------------
-- Table structure for realty_apartment_photo
-- ----------------------------
DROP TABLE IF EXISTS `realty_apartment_photo`;
CREATE TABLE `realty_apartment_photo` (
  `apartment_id` bigint unsigned NOT NULL,
  `photo_id` bigint unsigned NOT NULL,
  KEY `fdx_apartment` (`apartment_id`) USING BTREE,
  KEY `fdx_photo` (`photo_id`) USING BTREE,
  CONSTRAINT `fdx_apartment` FOREIGN KEY (`apartment_id`) REFERENCES `realty_apartment` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fdx_photo` FOREIGN KEY (`photo_id`) REFERENCES `realty_photo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of realty_apartment_photo
-- ----------------------------
BEGIN;
INSERT INTO `realty_apartment_photo` (`apartment_id`, `photo_id`) VALUES (23, 33);
INSERT INTO `realty_apartment_photo` (`apartment_id`, `photo_id`) VALUES (23, 34);
INSERT INTO `realty_apartment_photo` (`apartment_id`, `photo_id`) VALUES (23, 38);
INSERT INTO `realty_apartment_photo` (`apartment_id`, `photo_id`) VALUES (23, 39);
INSERT INTO `realty_apartment_photo` (`apartment_id`, `photo_id`) VALUES (35, 52);
INSERT INTO `realty_apartment_photo` (`apartment_id`, `photo_id`) VALUES (36, 54);
INSERT INTO `realty_apartment_photo` (`apartment_id`, `photo_id`) VALUES (36, 55);
COMMIT;

-- ----------------------------
-- Table structure for realty_house
-- ----------------------------
DROP TABLE IF EXISTS `realty_house`;
CREATE TABLE `realty_house` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of realty_house
-- ----------------------------
BEGIN;
INSERT INTO `realty_house` (`id`, `address`) VALUES (1, 'Бутырская, 77');
INSERT INTO `realty_house` (`id`, `address`) VALUES (2, 'Большая Полянка, 15');
INSERT INTO `realty_house` (`id`, `address`) VALUES (3, 'Тверская-Ямская, 22');
INSERT INTO `realty_house` (`id`, `address`) VALUES (6, 'Кадашёвская набережная');
INSERT INTO `realty_house` (`id`, `address`) VALUES (7, 'Садовническая набережная');
COMMIT;

-- ----------------------------
-- Table structure for realty_house_photo
-- ----------------------------
DROP TABLE IF EXISTS `realty_house_photo`;
CREATE TABLE `realty_house_photo` (
  `house_id` bigint unsigned NOT NULL,
  `photo_id` bigint unsigned NOT NULL,
  KEY `fdx_apartment` (`house_id`) USING BTREE,
  KEY `fdx_photo` (`photo_id`) USING BTREE,
  CONSTRAINT `realty_house_photo_ibfk_1` FOREIGN KEY (`house_id`) REFERENCES `realty_house` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `realty_house_photo_ibfk_2` FOREIGN KEY (`photo_id`) REFERENCES `realty_photo` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of realty_house_photo
-- ----------------------------
BEGIN;
INSERT INTO `realty_house_photo` (`house_id`, `photo_id`) VALUES (6, 48);
INSERT INTO `realty_house_photo` (`house_id`, `photo_id`) VALUES (1, 49);
INSERT INTO `realty_house_photo` (`house_id`, `photo_id`) VALUES (2, 50);
INSERT INTO `realty_house_photo` (`house_id`, `photo_id`) VALUES (3, 51);
INSERT INTO `realty_house_photo` (`house_id`, `photo_id`) VALUES (7, 53);
COMMIT;

-- ----------------------------
-- Table structure for realty_photo
-- ----------------------------
DROP TABLE IF EXISTS `realty_photo`;
CREATE TABLE `realty_photo` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of realty_photo
-- ----------------------------
BEGIN;
INSERT INTO `realty_photo` (`id`, `filename`) VALUES (4, 'house_image_1.jpg');
INSERT INTO `realty_photo` (`id`, `filename`) VALUES (5, 'house_image_1.jpg');
INSERT INTO `realty_photo` (`id`, `filename`) VALUES (6, 'house_image_1.jpg');
INSERT INTO `realty_photo` (`id`, `filename`) VALUES (7, 'house_image_1.jpg');
INSERT INTO `realty_photo` (`id`, `filename`) VALUES (8, 'house_image_1.jpg');
INSERT INTO `realty_photo` (`id`, `filename`) VALUES (9, 'house_image_1.jpg');
INSERT INTO `realty_photo` (`id`, `filename`) VALUES (18, 'e73d476922311930e04e4e4715ea09b4.jpg');
INSERT INTO `realty_photo` (`id`, `filename`) VALUES (27, '17bfbc8cd4910ef8e4b4f0144b2769a3.png');
INSERT INTO `realty_photo` (`id`, `filename`) VALUES (28, 'e48caed79a6009d9654ca75b99563342.png');
INSERT INTO `realty_photo` (`id`, `filename`) VALUES (29, '5c46154767208ccb82eef170a4e2fd3d.png');
INSERT INTO `realty_photo` (`id`, `filename`) VALUES (30, '3c377e69bdf01eb6e62de61e792f0614.png');
INSERT INTO `realty_photo` (`id`, `filename`) VALUES (31, '8de5ee842a7bab584364d0cd644b2999.png');
INSERT INTO `realty_photo` (`id`, `filename`) VALUES (32, '20ee5ba967b9f09c254c46db1fa4c032.png');
INSERT INTO `realty_photo` (`id`, `filename`) VALUES (33, '1e644520bdda9e73bd89ee8ec29c6df3.jpg');
INSERT INTO `realty_photo` (`id`, `filename`) VALUES (34, '8f9c5b10d0a6a7562f4353512922f89e.jpg');
INSERT INTO `realty_photo` (`id`, `filename`) VALUES (37, 'd7eab3f259a759bed8229aa8a3cdb404.jpg');
INSERT INTO `realty_photo` (`id`, `filename`) VALUES (38, 'd9dce3091f500832bf02495dca502407.jpg');
INSERT INTO `realty_photo` (`id`, `filename`) VALUES (39, '3698341469474017765388c2ecbfef58.jpg');
INSERT INTO `realty_photo` (`id`, `filename`) VALUES (41, '0ea4caf975762615ef2af1c57425d731.jpg');
INSERT INTO `realty_photo` (`id`, `filename`) VALUES (42, '84f16a46f87844ae029acae4435a3374.jpg');
INSERT INTO `realty_photo` (`id`, `filename`) VALUES (46, '022228187cc8842c7bb9e43fa73dba92.jpg');
INSERT INTO `realty_photo` (`id`, `filename`) VALUES (48, '641edd4e8e9fe4e567f313336418d025.jpg');
INSERT INTO `realty_photo` (`id`, `filename`) VALUES (49, '24774939f6b983e0051660bbdcb9b67b.jpg');
INSERT INTO `realty_photo` (`id`, `filename`) VALUES (50, 'a44e90a893cfa166d365977933989f28.jpg');
INSERT INTO `realty_photo` (`id`, `filename`) VALUES (51, 'a548f4f6a1d4944ebe25ca66771b0250.jpg');
INSERT INTO `realty_photo` (`id`, `filename`) VALUES (52, 'c273c2a287ab90199c82de82270aa687.jpg');
INSERT INTO `realty_photo` (`id`, `filename`) VALUES (53, '103732bf351e2a153645429f023fbaca.jpg');
INSERT INTO `realty_photo` (`id`, `filename`) VALUES (54, '1db3fabf0066b4e23d4fab8b81c19c30.jpg');
INSERT INTO `realty_photo` (`id`, `filename`) VALUES (55, '325b012fde5178d23b634e02f86aff4a.jpg');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
