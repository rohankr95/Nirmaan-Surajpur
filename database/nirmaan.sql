-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.6.14-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.5.0.6677
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for nirmaan_server
CREATE DATABASE IF NOT EXISTS `nirmaan_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `nirmaan_db`;

-- Dumping structure for table nirmaan_server.activities
CREATE TABLE IF NOT EXISTS `activities` (
  `activity_id` bigint(20) unsigned NOT NULL,
  `activity_name` varchar(100) NOT NULL,
  `event_type_id` int(11) NOT NULL,
  `activity_date` date NOT NULL,
  `activity_end_date` date NOT NULL,
  `activity_image` text DEFAULT NULL,
  `activity_description` text NOT NULL,
  `club_id` int(11) NOT NULL,
  `financial_year_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table nirmaan_server.activity_media
CREATE TABLE IF NOT EXISTS `activity_media` (
  `id` bigint(20) unsigned NOT NULL,
  `type` enum('image','video') NOT NULL DEFAULT 'image',
  `activity_id` bigint(20) NOT NULL,
  `file` text NOT NULL,
  `thumb` text DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table nirmaan_server.administrative_sanctions
CREATE TABLE IF NOT EXISTS `administrative_sanctions` (
  `as_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `govt_or_district` varchar(50) NOT NULL,
  `as_no` varchar(255) NOT NULL,
  `submission_date` date NOT NULL,
  `approval_date` date DEFAULT NULL,
  `as_amount` double NOT NULL,
  `upload_file` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `work_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`as_id`),
  KEY `administrative_sanctions_work_id_index` (`work_id`),
  CONSTRAINT `administrative_sanctions_work_id_foreign` FOREIGN KEY (`work_id`) REFERENCES `works` (`work_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4497 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table nirmaan_server.assembly_constituencies
CREATE TABLE IF NOT EXISTS `assembly_constituencies` (
  `assembly_constituency_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `assembly_constituency_name` varchar(255) NOT NULL,
  `assembly_constituency_name_en` varchar(255) NOT NULL,
  `parliamentary_constituency_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`assembly_constituency_id`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table nirmaan_server.blocks
CREATE TABLE IF NOT EXISTS `blocks` (
  `block_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `block_name` varchar(50) NOT NULL,
  `block_name_en` varchar(50) NOT NULL,
  `subdivision_id` int(11) NOT NULL,
  `district_id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`block_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3640 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table nirmaan_server.cities
CREATE TABLE IF NOT EXISTS `cities` (
  `city_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `city_name` varchar(50) NOT NULL,
  `city_name_en` varchar(50) NOT NULL,
  `city_type_id` int(11) NOT NULL,
  `subdivision_id` int(11) NOT NULL,
  `district_id` int(11) NOT NULL,
  `assembly_constituency_id` int(10) NOT NULL,
  `state_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`city_id`)
) ENGINE=InnoDB AUTO_INCREMENT=296877 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table nirmaan_server.city_types
CREATE TABLE IF NOT EXISTS `city_types` (
  `city_type_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `city_type_name` varchar(50) NOT NULL,
  `city_type_name_en` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`city_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table nirmaan_server.departments
CREATE TABLE IF NOT EXISTS `departments` (
  `department_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `department_name` varchar(255) NOT NULL,
  `department_name_en` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`department_id`)
) ENGINE=InnoDB AUTO_INCREMENT=132 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table nirmaan_server.districts
CREATE TABLE IF NOT EXISTS `districts` (
  `district_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `district_name` varchar(50) NOT NULL,
  `district_name_en` varchar(50) NOT NULL,
  `state_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`district_id`)
) ENGINE=InnoDB AUTO_INCREMENT=764 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table nirmaan_server.employees
CREATE TABLE IF NOT EXISTS `employees` (
  `emp_id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_name` varchar(255) DEFAULT NULL,
  `emp_mobile` varchar(12) DEFAULT NULL,
  `emp_email` varchar(100) DEFAULT NULL,
  `emp_designation_id` int(11) DEFAULT NULL,
  `office_id` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1=Active \r\n0=Inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`emp_id`),
  KEY `emp_designation_id` (`emp_designation_id`),
  KEY `office_id` (`office_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table nirmaan_server.employees_designation
CREATE TABLE IF NOT EXISTS `employees_designation` (
  `designation_id` int(11) NOT NULL AUTO_INCREMENT,
  `designation_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`designation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table nirmaan_server.employees_new
CREATE TABLE IF NOT EXISTS `employees_new` (
  `emp_id` int(11) NOT NULL DEFAULT 0,
  `emp_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emp_mobile` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emp_email` varchar(255) NOT NULL,
  `emp_designation_id` int(11) DEFAULT NULL,
  `office_id` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1=Active \r\n0=Inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  UNIQUE KEY `emp_email` (`emp_email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table nirmaan_server.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table nirmaan_server.financial_years
CREATE TABLE IF NOT EXISTS `financial_years` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table nirmaan_server.grampanchayats
CREATE TABLE IF NOT EXISTS `grampanchayats` (
  `grampanchayat_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `grampanchayat_name` varchar(50) NOT NULL,
  `grampanchayat_name_en` varchar(50) NOT NULL,
  `block_id` int(11) NOT NULL,
  `subdivision_id` int(11) NOT NULL,
  `district_id` int(11) NOT NULL,
  `assembly_constituency_id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`grampanchayat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=296253 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table nirmaan_server.location_types
CREATE TABLE IF NOT EXISTS `location_types` (
  `location_type_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `location_type_name` varchar(50) NOT NULL,
  `location_type_name_en` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`location_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table nirmaan_server.log_activities
CREATE TABLE IF NOT EXISTS `log_activities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `method` varchar(255) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `agent` varchar(255) DEFAULT NULL,
  `module` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36492 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table nirmaan_server.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=169 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table nirmaan_server.offices
CREATE TABLE IF NOT EXISTS `offices` (
  `office_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `office_name` varchar(255) NOT NULL,
  `office_name_en` varchar(255) NOT NULL,
  `department_id` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`office_id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table nirmaan_server.parliamentary_constituencies
CREATE TABLE IF NOT EXISTS `parliamentary_constituencies` (
  `parliamentary_constituency_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parliamentary_constituency_name` varchar(255) NOT NULL,
  `parliamentary_constituency_name_en` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`parliamentary_constituency_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table nirmaan_server.password_resets
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table nirmaan_server.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table nirmaan_server.schemes
CREATE TABLE IF NOT EXISTS `schemes` (
  `scheme_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `scheme_name` varchar(500) NOT NULL,
  `department_id` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`scheme_id`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table nirmaan_server.states
CREATE TABLE IF NOT EXISTS `states` (
  `state_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `state_name` varchar(50) NOT NULL,
  `state_name_en` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`state_id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table nirmaan_server.subdivisions
CREATE TABLE IF NOT EXISTS `subdivisions` (
  `subdivision_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `subdivision_name` varchar(50) NOT NULL,
  `subdivision_name_en` varchar(50) NOT NULL,
  `district_id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`subdivision_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9902 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table nirmaan_server.technical_sanctions
CREATE TABLE IF NOT EXISTS `technical_sanctions` (
  `ts_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ts_no` varchar(255) DEFAULT NULL,
  `submission_date` date NOT NULL,
  `ts_amount` double NOT NULL,
  `approval_date` date DEFAULT NULL,
  `upload_file` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `work_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ts_id`),
  KEY `technical_sanctions_work_id_index` (`work_id`),
  CONSTRAINT `technical_sanctions_work_id_foreign` FOREIGN KEY (`work_id`) REFERENCES `works` (`work_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4336 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table nirmaan_server.tenders
CREATE TABLE IF NOT EXISTS `tenders` (
  `tender_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tender_no` varchar(255) NOT NULL,
  `tender_release_date` date DEFAULT NULL,
  `tender_opening_date` date NOT NULL,
  `work_order_date` date DEFAULT NULL,
  `upload_file` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `work_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`tender_id`),
  KEY `tenders_work_id_index` (`work_id`),
  CONSTRAINT `tenders_work_id_foreign` FOREIGN KEY (`work_id`) REFERENCES `works` (`work_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2999 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table nirmaan_server.users
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `login_id` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `landline` varchar(20) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `user_role_id` int(11) NOT NULL,
  `office_id` bigint(11) NOT NULL,
  `emp_id` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table nirmaan_server.user_roles
CREATE TABLE IF NOT EXISTS `user_roles` (
  `user_role_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) NOT NULL,
  `role_name_en` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table nirmaan_server.villages
CREATE TABLE IF NOT EXISTS `villages` (
  `village_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `village_name` varchar(255) NOT NULL,
  `village_name_en` varchar(255) NOT NULL,
  `grampanchayat_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`village_id`)
) ENGINE=InnoDB AUTO_INCREMENT=945399 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table nirmaan_server.wards
CREATE TABLE IF NOT EXISTS `wards` (
  `ward_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ward_no` int(11) NOT NULL,
  `ward_name` varchar(50) NOT NULL,
  `ward_name_en` varchar(50) NOT NULL,
  `city_id` int(11) NOT NULL,
  `district_id` int(11) NOT NULL,
  `assembly_constituency_id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ward_id`)
) ENGINE=InnoDB AUTO_INCREMENT=293 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table nirmaan_server.works
CREATE TABLE IF NOT EXISTS `works` (
  `work_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `work_name` varchar(255) DEFAULT NULL,
  `units_of_work` int(11) DEFAULT NULL,
  `work_type_id` int(11) NOT NULL,
  `scheme_id` int(11) NOT NULL,
  `office_id` bigint(20) NOT NULL,
  `department_id` bigint(20) DEFAULT NULL,
  `location_type_id` int(11) NOT NULL,
  `village_id` int(11) DEFAULT NULL,
  `ward_id` int(11) DEFAULT NULL,
  `ts_id` int(11) DEFAULT NULL,
  `as_id` int(11) DEFAULT NULL,
  `tender_id` int(11) DEFAULT NULL,
  `work_status` int(11) NOT NULL DEFAULT 0,
  `work_stage` int(11) DEFAULT NULL,
  `employee_id` int(20) NOT NULL DEFAULT 0,
  `financial_year_id` int(11) NOT NULL,
  `dpr_startDate` date DEFAULT NULL,
  `dpr_endDate` date DEFAULT NULL,
  `ts_startDate` date DEFAULT NULL,
  `ts_endDate` date DEFAULT NULL,
  `as_startDate` date DEFAULT NULL,
  `as_endDate` date DEFAULT NULL,
  `tenderChecked` int(11) DEFAULT 0,
  `tender_startDate` date DEFAULT NULL,
  `tender_endDate` date DEFAULT NULL,
  `workOrder_startDate` date DEFAULT NULL,
  `workOrder_endDate` date DEFAULT NULL,
  `agreement_startDate` date DEFAULT NULL,
  `agreement_endDate` date DEFAULT NULL,
  `workStart_startDate` date DEFAULT NULL,
  `workStart_endDate` date DEFAULT NULL,
  `stages` int(11) DEFAULT NULL,
  `stage_days` int(255) DEFAULT NULL,
  `stage_startDate` date DEFAULT NULL,
  `stage_endDate` date DEFAULT NULL,
  `workComplete_endDate` date DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `created_at` date NOT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `updated_at` date NOT NULL,
  `deleted_by` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`work_id`),
  KEY `employee_id` (`employee_id`),
  KEY `employee_id_2` (`employee_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5694 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table nirmaan_server.work_closeds
CREATE TABLE IF NOT EXISTS `work_closeds` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `close_date` date NOT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `work_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `work_closeds_work_id_index` (`work_id`),
  CONSTRAINT `work_closeds_work_id_foreign` FOREIGN KEY (`work_id`) REFERENCES `works` (`work_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table nirmaan_server.work_completes
CREATE TABLE IF NOT EXISTS `work_completes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `completion_date` date NOT NULL,
  `upload_file` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `work_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `work_completes_work_id_index` (`work_id`),
  CONSTRAINT `work_completes_work_id_foreign` FOREIGN KEY (`work_id`) REFERENCES `works` (`work_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=894 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table nirmaan_server.work_progress
CREATE TABLE IF NOT EXISTS `work_progress` (
  `wp_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `estimated_completion_date` date DEFAULT NULL,
  `work_status_id` int(11) NOT NULL,
  `mb_stages_id` int(11) NOT NULL,
  `expenditure_amount` double DEFAULT NULL,
  `upload_file` varchar(255) DEFAULT NULL,
  `status_update_date` date NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `work_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`wp_id`),
  KEY `work_progress_work_id_foreign` (`work_id`),
  CONSTRAINT `work_progress_work_id_foreign` FOREIGN KEY (`work_id`) REFERENCES `works` (`work_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2426 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table nirmaan_server.work_rejects
CREATE TABLE IF NOT EXISTS `work_rejects` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `rejected_date` date NOT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `work_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `work_rejects_work_id_index` (`work_id`),
  CONSTRAINT `work_rejects_work_id_foreign` FOREIGN KEY (`work_id`) REFERENCES `works` (`work_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table nirmaan_server.work_statuses
CREATE TABLE IF NOT EXISTS `work_statuses` (
  `work_status_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `work_status_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`work_status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=238 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table nirmaan_server.work_types
CREATE TABLE IF NOT EXISTS `work_types` (
  `work_type_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `work_type_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`work_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=118 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table nirmaan_server.work_type_stages
CREATE TABLE IF NOT EXISTS `work_type_stages` (
  `work_type_stage_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `work_type_stage_name` text NOT NULL,
  `work_type_id` int(11) NOT NULL,
  `stage_number` int(2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`work_type_stage_id`)
) ENGINE=InnoDB AUTO_INCREMENT=189 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
