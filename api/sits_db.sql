-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 31, 2025 at 03:11 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sits_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `AplID` int(9) NOT NULL,
  `ScholarshipID` int(9) NOT NULL DEFAULT 0,
  `UserID` int(11) NOT NULL,
  `ReviewerID` int(9) NOT NULL DEFAULT 0,
  `HHIncome` double NOT NULL DEFAULT 0 COMMENT 'For house hold income',
  `ReceivingFinancialAid` tinyint(1) DEFAULT 1 COMMENT '0 - Yes, 1 - No',
  `ExistingScholarship` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0 - Yes, 1 - No',
  `AplSchID` int(9) NOT NULL DEFAULT 0,
  `AplDate` date NOT NULL DEFAULT current_timestamp(),
  `AplDT` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `FieldStudy` varchar(255) DEFAULT NULL,
  `Gpa` double NOT NULL DEFAULT 0,
  `PreviousAcademic` varchar(255) NOT NULL DEFAULT '',
  `NoIndependents` double NOT NULL DEFAULT 0,
  `ReasonFinancial` varchar(255) DEFAULT NULL,
  `WhyApply` text DEFAULT NULL,
  `Messages` text DEFAULT NULL,
  `AplStatus` int(9) NOT NULL DEFAULT 0 COMMENT '0 - submitted, 1 - Under Review, 2- Awaiting Documents, 3 - Approved, 4 - Rejected, 5 - Hold',
  `FileSubmitted` int(9) NOT NULL DEFAULT 0,
  `Remarks` text NOT NULL,
  `Deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`AplID`, `ScholarshipID`, `UserID`, `ReviewerID`, `HHIncome`, `ReceivingFinancialAid`, `ExistingScholarship`, `AplSchID`, `AplDate`, `AplDT`, `FieldStudy`, `Gpa`, `PreviousAcademic`, `NoIndependents`, `ReasonFinancial`, `WhyApply`, `Messages`, `AplStatus`, `FileSubmitted`, `Remarks`, `Deleted`) VALUES
(1, 20, 20, 1, 3000, 1, 1, 0, '2025-01-31', '2025-01-31 21:29:37', 'Test', 1, 'Test', 3, '', 'Test', 'Test', 3, 0, 'Test', 0);

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `CourseID` int(11) NOT NULL,
  `CourseName` varchar(100) NOT NULL,
  `CourseCode` varchar(255) DEFAULT NULL,
  `Description` text DEFAULT NULL,
  `Major` varchar(255) DEFAULT NULL,
  `Credits` int(11) NOT NULL,
  `Deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`CourseID`, `CourseName`, `CourseCode`, `Description`, `Major`, `Credits`, `Deleted`) VALUES
(1, 'Bachilor in Elementary Education', 'BEED', 'Bachilor in Elementary Education', 'English', 3, 0),
(2, 'asd', 'asd', 'asd', 'asd', 6, 1),
(3, 'b', 'b', 'b', 'b', 1, 1),
(4, '', '', '', '', 0, 1),
(5, 'asd', 'asd', 'asd', 'asd', 2, 1),
(6, 'sd', 'asd', 'asd', 'asd', 5, 1),
(7, 'asd', 'asd', 'asd', 'asd', 6, 1),
(8, 'Bachelor of Science in Computer Science', 'BSCS', 'This program focuses on the study of computer systems, algorithms, and software development. It prepares students for careers in software engineering, systems analysis, and research.', 'Computer Science', 1, 0),
(9, 'Bachelor of Science in Business Administration', 'BSBA', 'This course provides comprehensive knowledge in business management, finance, marketing, and entrepreneurship, equipping students to become effective business leaders and managers.', 'Business Administration', 0, 0),
(10, 'Bachelor of Science in Nursing', 'BSN', 'A program that trains students in patient care, health assessment, and nursing procedures, preparing them for roles in various healthcare settings.', 'Nursing', 0, 0),
(11, 'Bachelor of Science in Civil Engineering', 'BSCE', 'This course covers the design, construction, and maintenance of infrastructure projects like roads, bridges, and buildings, emphasizing structural integrity and sustainability.', 'Civil Engineering', 0, 0),
(12, 'Bachelor of Arts in Communication', 'BACOMM', 'A program that delves into various communication theories and practices, including media studies, journalism, and public relations, preparing students for careers in media and corporate communications.', 'Communication', 0, 0),
(13, 'Bachilor of Science Information Systems', 'BSIS', 'An introductory course focusing on the fundamentals of information systems, their components, and how they are used in businesses.', 'BSIS', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `DepartmentID` int(9) NOT NULL,
  `DeptName` varchar(255) NOT NULL,
  `DeptCode` varchar(25) NOT NULL,
  `UserID` int(9) NOT NULL DEFAULT 0,
  `Descriptions` varchar(255) NOT NULL,
  `Deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`DepartmentID`, `DeptName`, `DeptCode`, `UserID`, `Descriptions`, `Deleted`) VALUES
(1, 'Department of Computer Science', 'CS', 0, 'Focuses on the study and research of computer systems, software development, algorithms, and emerging technologies in computing.', 0),
(2, 'Department of Business Administration', 'BA', 0, 'Provides education and training in management, finance, marketing, and entrepreneurship to prepare students for leadership roles in business.', 0),
(3, 'Department of Nursing', 'NUR', 0, 'Offers courses in patient care, healthcare management, and medical practices, preparing students to work in hospitals, clinics, and other healthcare settings.', 0),
(4, 'Department of Civil Engineering', 'CE', 0, 'Specializes in the design, construction, and maintenance of infrastructure projects such as roads, bridges, and buildings, with an emphasis on sustainability and safety.', 0),
(5, 'Department of Arts and Communication', 'AC', 0, 'Focuses on communication studies, media, journalism, and public relations, equipping students with skills for careers in the media and corporate communications industries.', 0),
(6, 'Department of Information System', 'IS', 0, 'Focuses on the intersection of information technology and business management, preparing students to effectively use technology to solve organizational problems and improve business processes.', 0),
(7, 'Department of Scholarship', '', 0, '', 0),
(8, 'Department of Sports', '', 0, '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `email_notifications`
--

CREATE TABLE `email_notifications` (
  `ENID` int(9) NOT NULL,
  `UserID` int(9) NOT NULL DEFAULT 0,
  `RecipientEmail` varchar(255) DEFAULT NULL,
  `FromUserID` int(9) NOT NULL DEFAULT 0,
  `FromEmail` varchar(255) DEFAULT NULL,
  `Subjects` text DEFAULT NULL,
  `EmailBody` text NOT NULL,
  `SentDate` date NOT NULL DEFAULT current_timestamp(),
  `SentTimeStamped` datetime NOT NULL DEFAULT current_timestamp(),
  `AttachementInfo` varchar(255) DEFAULT NULL,
  `EmailType` int(9) NOT NULL DEFAULT 0 COMMENT '1 - autosent, 2 - from concern',
  `EmailStatus` int(9) NOT NULL DEFAULT 0 COMMENT '0 - Draft, 1 - Sent, 2 - Received',
  `Method` varchar(25) NOT NULL DEFAULT 'SMTP',
  `Deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `email_notifications`
--

INSERT INTO `email_notifications` (`ENID`, `UserID`, `RecipientEmail`, `FromUserID`, `FromEmail`, `Subjects`, `EmailBody`, `SentDate`, `SentTimeStamped`, `AttachementInfo`, `EmailType`, `EmailStatus`, `Method`, `Deleted`) VALUES
(1, 20, 'temporaryml222@gmail.com', 0, 'scholarlysync320@gmail.com', 'Congratulations! You`ve Qualified for the Dependent Scholarship', '<div style=`color: black;`>\n            <p>Dear Poraryml222,</p>\n            <p>We are thrilled to inform you that you have automatically qualified for the Dependent Scholarship \n            based on your parent’s association with ScholarlySync. As a result, we’ve created a \n            scholarship for you to support your education.</p>\n            <ul>\n                <li>\n                    <strong>Scholarship Details:</strong>\n                    <ul>\n                        <li><strong>Scholarship: </strong> Dependent First Child Scholarship</li>\n                        <li><strong>Scholarship Type:</strong> Employee Dependent (100%)</li>\n                    </ul>\n                </li>\n            </ul>\n\n            <p>If you have any questions or need assistance, please don’t hesitate to reach out to us at Scholarship Admin Office</p>\n            <p>Congratulations again, and we look forward to supporting your academic journey!!</p>\n\n            <p>Best regards,<br>\n            Colegio De Sta Ana De Victorias<br>\n            399-3286</p>\n          </div>', '2025-01-31', '2025-01-31 21:04:47', '', 2, 2, 'SMTP', 0),
(2, 20, 'temporaryml222@gmail.com', 0, 'scholarlysync320@gmail.com', 'Your Scholarship Application Has Been Submitted', '<div style=`color: black; font-family: Arial, sans-serif;`>\n          <p>Hello Poraryml222,</p>\n\n          <p>We hope you`re doing well!</p>\n\n          <p>Scholarship Applied Philippine Business for Social Progress (PBSP) Scholarship <p>\n          \n          <p>Your application has been successfully <strong>submitted</strong>.</p>\n\n          \n\n          <p>If you have any questions, feel free to reach out to us at <strong>+399-3286</strong>.</p>\n\n          <p>Best regards,<br>\n          <strong>Colegio De Sta Ana De Victorias</strong><br>\n          +399-3286</p>\n      </div>', '2025-01-31', '2025-01-31 21:27:38', 'None', 2, 2, 'SMTP', 0),
(3, 20, 'temporaryml222@gmail.com', 0, 'scholarlysync320@gmail.com', 'Submission Status Update', '<div style=`color: black; font-family: Arial, sans-serif;`>\n          <p>Hello Poraryml222,</p>\n\n          <p>We hope you`re doing well!</p>\n\n          <p>Scholarship Applied Philippine Business for Social Progress (PBSP) Scholarship <p>\n          \n          <p>We wanted to let you know that your submission has been successfully received and is now <strong>under review</strong>.</p><p>We appreciate your patience and will notify you as soon as there are further updates.</p>\n\n          <p><strong>Remarks:</strong> Test</p>\n\n          <p>If you have any questions, feel free to reach out to us at <strong>+399-3286</strong>.</p>\n\n          <p>Best regards,<br>\n          <strong>Colegio De Sta Ana De Victorias</strong><br>\n          +399-3286</p>\n      </div>', '2025-01-31', '2025-01-31 21:29:01', 'None', 2, 2, 'SMTP', 0),
(4, 20, 'temporaryml222@gmail.com', 0, 'scholarlysync320@gmail.com', 'Submission Status Update', '<div style=`color: black; font-family: Arial, sans-serif;`>\n          <p>Hello Poraryml222,</p>\n\n          <p>We hope you`re doing well!</p>\n\n          <p>Scholarship Applied Philippine Business for Social Progress (PBSP) Scholarship <p>\n          \n          <p>Your document has been successfully received and is now <strong>reviewing your documents</strong>.</p>\n\n          <p><strong>Remarks:</strong> Test</p>\n\n          <p>If you have any questions, feel free to reach out to us at <strong>+399-3286</strong>.</p>\n\n          <p>Best regards,<br>\n          <strong>Colegio De Sta Ana De Victorias</strong><br>\n          +399-3286</p>\n      </div>', '2025-01-31', '2025-01-31 21:29:17', 'None', 2, 2, 'SMTP', 0),
(5, 20, 'temporaryml222@gmail.com', 0, 'scholarlysync320@gmail.com', 'Submission Status Update', '<div style=`color: black; font-family: Arial, sans-serif;`>\n          <p>Hello Poraryml222,</p>\n\n          <p>We hope you`re doing well!</p>\n\n          <p>Scholarship Applied Philippine Business for Social Progress (PBSP) Scholarship <p>\n          \n          <p>Congratulations! Your application has been <strong>approved</strong>.</p>\n\n          <p><strong>Remarks:</strong> Test</p>\n\n          <p>If you have any questions, feel free to reach out to us at <strong>+399-3286</strong>.</p>\n\n          <p>Best regards,<br>\n          <strong>Colegio De Sta Ana De Victorias</strong><br>\n          +399-3286</p>\n      </div>', '2025-01-31', '2025-01-31 21:29:41', 'None', 2, 2, 'SMTP', 0),
(6, 20, 'temporaryml222@gmail.com', 0, 'scholarlysync320@gmail.com', 'Contract', '<div style=`color: black;`>\n        <p>Dear Poraryml222,</p>\n\n        <p>I hope this message finds you well.</p>\n\n        <p><strong>Submitted Information:</strong></p>\n        \n        <p>I am pleased to inform you that we have finalized the details of your sports scholarship. \n        Attached to this email, you will find the scholarship form. Please take the time to read through the document carefully.</p>\n        \n        <p>If you have any questions or require further clarification regarding any terms, feel free to reach out to me. \n        Once you have reviewed the contract, kindly sign and return it at your earliest convenience to secure your scholarship.</p>\n      \n        <p>Thank you, and congratulations once again on your achievement!</p>\n\n        <p>Best regards,</p>\n        Colegio De Sta Ana De Victorias<br>\n        399-3286</p>\n    </div>', '2025-01-31', '2025-01-31 21:32:32', 'FORM7.pdf', 1, 2, 'SMTP', 0),
(8, 0, NULL, 9, 'michaeljohnson@gmail.com', NULL, '', '2025-01-31', '2025-01-31 22:09:24', NULL, 2, 0, 'SMTP', 0);

-- --------------------------------------------------------

--
-- Table structure for table `filemanager`
--

CREATE TABLE `filemanager` (
  `FileID` int(9) NOT NULL,
  `UserID` int(9) NOT NULL DEFAULT 0,
  `ScholarshipID` int(9) NOT NULL DEFAULT 0,
  `AplID` int(9) NOT NULL DEFAULT 0,
  `ENID` int(9) NOT NULL DEFAULT 0,
  `FileNames` varchar(255) DEFAULT NULL,
  `FileSize` varchar(255) DEFAULT NULL,
  `FileType` varchar(255) DEFAULT NULL,
  `FileDate` date DEFAULT NULL,
  `FileLocation` varchar(255) DEFAULT NULL,
  `UploadAt` datetime NOT NULL DEFAULT current_timestamp(),
  `Deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `filemanager`
--

INSERT INTO `filemanager` (`FileID`, `UserID`, `ScholarshipID`, `AplID`, `ENID`, `FileNames`, `FileSize`, `FileType`, `FileDate`, `FileLocation`, `UploadAt`, `Deleted`) VALUES
(1, 5, 0, 0, 3, '6799e1782ad75.png', '51009', 'image/png', '2025-01-29', '../uploads/filesmanager/6799e1782ad75.png', '2025-01-29 16:06:16', 1),
(2, 5, 0, 0, 3, '6799e1c0b4b5f.png', '51009', 'image/png', '2025-01-29', '../uploads/filesmanager/6799e1c0b4b5f.png', '2025-01-29 16:07:28', 1),
(3, 5, 0, 0, 4, '6799e301370d0.png', '51009', 'image/png', '2025-01-29', '../uploads/filesmanager/6799e301370d0.png', '2025-01-29 16:12:49', 1),
(4, 5, 0, 0, 4, '6799e30384b56.png', '661985', 'image/png', '2025-01-29', '../uploads/filesmanager/6799e30384b56.png', '2025-01-29 16:12:51', 1),
(5, 5, 0, 0, 1, '6799e61e26aa8.png', '51009', 'image/png', '2025-01-29', '../uploads/filesmanager/6799e61e26aa8.png', '2025-01-29 16:26:06', 0),
(6, 5, 1, 2, 0, '6799ec1351bda.png', '51009', 'image/png', '2025-01-29', '../uploads/filesmanager/6799ec1351bda.png', '2025-01-29 16:51:31', 1),
(7, 5, 1, 2, 0, '6799ed17273fd.png', '51009', 'image/png', '2025-01-29', '../uploads/filesmanager/6799ed17273fd.png', '2025-01-29 16:55:51', 1),
(8, 5, 1, 2, 0, '6799ed77f1ead.png', '51009', 'image/png', '2025-01-29', '../uploads/filesmanager/6799ed77f1ead.png', '2025-01-29 16:57:27', 0),
(9, 20, 20, 1, 0, '679ccfa3b104b.pdf', '13264', 'application/pdf', '2025-01-31', '../uploads/filesmanager/679ccfa3b104b.pdf', '2025-01-31 21:26:59', 0),
(10, 20, 20, 1, 0, '679ccfb026aff.pdf', '13264', 'application/pdf', '2025-01-31', '../uploads/filesmanager/679ccfb026aff.pdf', '2025-01-31 21:27:12', 0);

-- --------------------------------------------------------

--
-- Table structure for table `forms`
--

CREATE TABLE `forms` (
  `FormID` int(9) NOT NULL,
  `ScholarshipID` int(9) NOT NULL DEFAULT 0,
  `FormType` int(9) NOT NULL DEFAULT 0 COMMENT '0 - Contract, 1 Rules and Regulations',
  `Title` text NOT NULL,
  `Body` text NOT NULL,
  `FormLocation` varchar(255) NOT NULL,
  `Deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `forms`
--

INSERT INTO `forms` (`FormID`, `ScholarshipID`, `FormType`, `Title`, `Body`, `FormLocation`, `Deleted`) VALUES
(1, 21, 0, 'Student Scholarship Rules & Regulations', '<p style=\"line-height: 1;\"><span style=\"font-size: 8pt;\">This agreement specifies the rules and regulations that the Athlete agrees to follow as a scholarship recipient. It is a supplementary document to the Scholarship Contract.</span></p>\n<h3 style=\"line-height: 1;\"><span style=\"font-size: 8pt;\"><strong>Rules and Regulations</strong></span></h3>\n<ol>\n<li style=\"font-size: 8pt;\">\n<p><span style=\"font-size: 8pt;\"><strong>Training and Attendance</strong></span></p>\n<ul>\n<li style=\"font-size: 8pt;\"><span style=\"font-size: 8pt;\">The Athlete must attend all scheduled training sessions, practices, and meetings unless excused for medical or academic reasons.</span></li>\n</ul>\n</li>\n<li style=\"font-size: 8pt;\">\n<p><span style=\"font-size: 8pt;\"><strong>Academic Prioritization</strong></span></p>\n<ul>\n<li style=\"font-size: 8pt;\"><span style=\"font-size: 8pt;\">Academics remain a priority, and the Athlete must ensure that training or competitions do not interfere with academic responsibilities.</span></li>\n</ul>\n</li>\n<li style=\"font-size: 8pt;\">\n<p><span style=\"font-size: 8pt;\"><strong>Social Media and Public Conduct</strong></span></p>\n<ul>\n<li style=\"font-size: 8pt;\"><span style=\"font-size: 8pt;\">The Athlete must represent the institution positively on social media and avoid posting content that could harm the institution`s reputation.</span></li>\n<li style=\"font-size: 8pt;\"><span style=\"font-size: 8pt;\">Confidential information about the team, institution, or scholarship must not be shared publicly.</span></li>\n</ul>\n</li>\n<li style=\"font-size: 8pt;\">\n<p><span style=\"font-size: 8pt;\"><strong>Dress Code and Uniform</strong></span></p>\n<ul>\n<li style=\"font-size: 8pt;\"><span style=\"font-size: 8pt;\">The Athlete must adhere to the specified dress code for official events, competitions, and team appearances.</span></li>\n</ul>\n</li>\n<li style=\"font-size: 8pt;\">\n<p><span style=\"font-size: 8pt;\"><strong>Equipment and Facility Usage</strong></span></p>\n<ul>\n<li style=\"font-size: 8pt;\"><span style=\"font-size: 8pt;\">Equipment and facilities provided under the scholarship should be used responsibly. Any damage due to negligence may result in financial penalties.</span></li>\n</ul>\n</li>\n<li style=\"font-size: 8pt;\">\n<p><span style=\"font-size: 8pt;\"><strong>Code of Conduct</strong></span></p>\n<ul>\n<li style=\"font-size: 8pt;\"><span style=\"font-size: 8pt;\">The Athlete agrees to uphold respect, sportsmanship, and integrity at all times.</span></li>\n<li style=\"font-size: 8pt;\"><span style=\"font-size: 8pt;\">Any form of bullying, harassment, or discrimination is prohibited and will result in disciplinary action.</span></li>\n</ul>\n</li>\n<li style=\"font-size: 8pt;\">\n<p><span style=\"font-size: 8pt;\"><strong>Disciplinary Actions</strong></span></p>\n<ul>\n<li style=\"font-size: 8pt;\"><span style=\"font-size: 8pt;\">Violations of these rules may lead to:</span><span style=\"font-size: 8pt;\"><br></span>\n<ul>\n<li style=\"font-size: 8pt;\"><span style=\"font-size: 8pt;\">Probation</span></li>\n<li style=\"font-size: 8pt;\"><span style=\"font-size: 8pt;\">Suspension</span></li>\n<li style=\"font-size: 8pt;\"><span style=\"font-size: 8pt;\">Reduction or termination of scholarship funds<br><br></span></li>\n</ul>\n</li>\n</ul>\n</li>\n</ol>\n<h3 style=\"line-height: 1;\"><span style=\"font-size: 8pt;\"><strong>Acknowledgment and Signature</strong></span></h3>\n<p style=\"line-height: 1;\"><span style=\"font-size: 8pt;\">I, ____________________________ (Athlete&rsquo;s Name), have read and understood the rules and regulations outlined in this agreement. I agree to abide by these conditions as a recipient of the sports scholarship.</span></p>\n<p style=\"line-height: 1;\">&nbsp;</p>\n<p style=\"line-height: 1;\"><span style=\"font-size: 8pt;\"><strong>Provider<br></strong></span><br><span style=\"font-size: 8pt;\">Name: __________________________________<br></span><br><span style=\"font-size: 8pt;\">Signature: _______________________________<br></span><br><span style=\"font-size: 8pt;\">Date: ___________________________________</span></p>\n<p style=\"line-height: 1;\"><span style=\"font-size: 8pt;\"><strong>Recipient<br></strong></span><br><span style=\"font-size: 8pt;\">Name: __________________________________<br></span><br><span style=\"font-size: 8pt;\">Signature: _______________________________<br></span><br><span style=\"font-size: 8pt;\">Date: ___________________________________</span></p>', '', 1),
(2, 21, 1, 'Student Scholarship Rules & Regulations', '<p style=\"line-height: 1;\"><span style=\"font-size: 8pt;\">This agreement specifies the rules and regulations that the Athlete agrees to follow as a scholarship recipient. It is a supplementary document to the Scholarship Contract.</span></p>\n<h3 style=\"line-height: 1;\"><span style=\"font-size: 8pt;\"><strong>Rules and Regulations</strong></span></h3>\n<ol>\n<li style=\"font-size: 8pt;\">\n<p><span style=\"font-size: 8pt;\"><strong>Training and Attendance</strong></span></p>\n<ul>\n<li style=\"font-size: 8pt;\"><span style=\"font-size: 8pt;\">The Athlete must attend all scheduled training sessions, practices, and meetings unless excused for medical or academic reasons.</span></li>\n</ul>\n</li>\n<li style=\"font-size: 8pt;\">\n<p><span style=\"font-size: 8pt;\"><strong>Academic Prioritization</strong></span></p>\n<ul>\n<li style=\"font-size: 8pt;\"><span style=\"font-size: 8pt;\">Academics remain a priority, and the Athlete must ensure that training or competitions do not interfere with academic responsibilities.</span></li>\n</ul>\n</li>\n<li style=\"font-size: 8pt;\">\n<p><span style=\"font-size: 8pt;\"><strong>Social Media and Public Conduct</strong></span></p>\n<ul>\n<li style=\"font-size: 8pt;\"><span style=\"font-size: 8pt;\">The Athlete must represent the institution positively on social media and avoid posting content that could harm the institution`s reputation.</span></li>\n<li style=\"font-size: 8pt;\"><span style=\"font-size: 8pt;\">Confidential information about the team, institution, or scholarship must not be shared publicly.</span></li>\n</ul>\n</li>\n<li style=\"font-size: 8pt;\">\n<p><span style=\"font-size: 8pt;\"><strong>Dress Code and Uniform</strong></span></p>\n<ul>\n<li style=\"font-size: 8pt;\"><span style=\"font-size: 8pt;\">The Athlete must adhere to the specified dress code for official events, competitions, and team appearances.</span></li>\n</ul>\n</li>\n<li style=\"font-size: 8pt;\">\n<p><span style=\"font-size: 8pt;\"><strong>Equipment and Facility Usage</strong></span></p>\n<ul>\n<li style=\"font-size: 8pt;\"><span style=\"font-size: 8pt;\">Equipment and facilities provided under the scholarship should be used responsibly. Any damage due to negligence may result in financial penalties.</span></li>\n</ul>\n</li>\n<li style=\"font-size: 8pt;\">\n<p><span style=\"font-size: 8pt;\"><strong>Code of Conduct</strong></span></p>\n<ul>\n<li style=\"font-size: 8pt;\"><span style=\"font-size: 8pt;\">The Athlete agrees to uphold respect, sportsmanship, and integrity at all times.</span></li>\n<li style=\"font-size: 8pt;\"><span style=\"font-size: 8pt;\">Any form of bullying, harassment, or discrimination is prohibited and will result in disciplinary action.</span></li>\n</ul>\n</li>\n<li style=\"font-size: 8pt;\">\n<p><span style=\"font-size: 8pt;\"><strong>Disciplinary Actions</strong></span></p>\n<ul>\n<li style=\"font-size: 8pt;\"><span style=\"font-size: 8pt;\">Violations of these rules may lead to:</span><span style=\"font-size: 8pt;\"><br></span>\n<ul>\n<li style=\"font-size: 8pt;\"><span style=\"font-size: 8pt;\">Probation</span></li>\n<li style=\"font-size: 8pt;\"><span style=\"font-size: 8pt;\">Suspension</span></li>\n<li style=\"font-size: 8pt;\"><span style=\"font-size: 8pt;\">Reduction or termination of scholarship funds<br><br></span></li>\n</ul>\n</li>\n</ul>\n</li>\n</ol>\n<h3 style=\"line-height: 1;\"><span style=\"font-size: 8pt;\"><strong>Acknowledgment and Signature</strong></span></h3>\n<p style=\"line-height: 1;\"><span style=\"font-size: 8pt;\">I, ____________________________ (Athlete&rsquo;s Name), have read and understood the rules and regulations outlined in this agreement. I agree to abide by these conditions as a recipient of the sports scholarship.</span></p>\n<p style=\"line-height: 1;\">&nbsp;</p>\n<p style=\"line-height: 1;\"><span style=\"font-size: 8pt;\"><strong>Provider<br></strong></span><br><span style=\"font-size: 8pt;\">Name: __________________________________<br></span><br><span style=\"font-size: 8pt;\">Signature: _______________________________<br></span><br><span style=\"font-size: 8pt;\">Date: ___________________________________</span></p>\n<p style=\"line-height: 1;\"><span style=\"font-size: 8pt;\"><strong>Recipient<br></strong></span><br><span style=\"font-size: 8pt;\">Name: __________________________________<br></span><br><span style=\"font-size: 8pt;\">Signature: _______________________________<br></span><br><span style=\"font-size: 8pt;\">Date: ___________________________________</span></p>', '', 1),
(3, 21, 0, 'asdasd', '<p>asdasd</p>', '', 1),
(4, 21, 0, 'asdasd', '<p>asdasd</p>', '', 1),
(5, 1, 0, 'asdasd', '<p>asdasdasd</p>', '', 1),
(6, 2, 0, 'asd', '<p>sd</p>', '', 1),
(7, 21, 0, 'Contract', '<p>asdasdasdasdasdasdasdasdasd</p>', '', 0),
(8, 21, 0, 'Rules and Regulartion', '<p>Test</p>', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `scholars`
--

CREATE TABLE `scholars` (
  `ScholarID` int(9) NOT NULL,
  `ScholarshipID` int(9) NOT NULL DEFAULT 0,
  `UserID` int(9) NOT NULL DEFAULT 0,
  `AplID` int(9) NOT NULL DEFAULT 0,
  `ApprovedDate` date DEFAULT NULL,
  `AddedType` int(9) NOT NULL DEFAULT 0,
  `AddedTypeText` varchar(255) DEFAULT NULL,
  `ScholarStatus` int(9) NOT NULL DEFAULT 0 COMMENT '1-Active, 2, Expired, 3, Maxed Out',
  `CreatedAt` datetime NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `scholars`
--

INSERT INTO `scholars` (`ScholarID`, `ScholarshipID`, `UserID`, `AplID`, `ApprovedDate`, `AddedType`, `AddedTypeText`, `ScholarStatus`, `CreatedAt`, `UpdatedAt`, `Deleted`) VALUES
(1, 20, 20, 1, '2025-01-31', 0, 'Application Form', 1, '2025-01-31 21:29:37', '2025-01-31 21:29:37', 0);

-- --------------------------------------------------------

--
-- Table structure for table `scholarship`
--

CREATE TABLE `scholarship` (
  `ScholarshipID` int(9) NOT NULL,
  `ScholarshipName` varchar(255) DEFAULT NULL,
  `ScholarshipType` int(9) NOT NULL DEFAULT 0 COMMENT '(e.g., merit-based, need-based).',
  `ResponSch` int(9) NOT NULL DEFAULT 0,
  `ScholarshipStatus` int(9) NOT NULL DEFAULT 0,
  `AwardAmount` varchar(255) DEFAULT NULL,
  `AwardDate` date NOT NULL,
  `Criteria` text DEFAULT NULL,
  `DocsRequired` text NOT NULL,
  `FundingSource` varchar(255) NOT NULL,
  `CreatedBy` int(9) NOT NULL DEFAULT 0,
  `CreatedAt` datetime NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `scholarship`
--

INSERT INTO `scholarship` (`ScholarshipID`, `ScholarshipName`, `ScholarshipType`, `ResponSch`, `ScholarshipStatus`, `AwardAmount`, `AwardDate`, `Criteria`, `DocsRequired`, `FundingSource`, `CreatedBy`, `CreatedAt`, `UpdatedAt`, `Deleted`) VALUES
(6, 'Dependent First Child Scholarship', 6, 0, 2, 'Full tuition, allowance for books, monthly stipend of 5,000 - 8,000', '2025-06-29', '<p><strong>Applicants must meet the following requirements to qualify for the Dependent Scholarship:</strong></p>\n<ol>\n<li>\n<p><strong>Dependent Status</strong></p>\n<ul>\n<li>The applicant must be a dependent child or spouse of a current employee of the organization.</li>\n<li>Proof of dependent status (e.g., birth certificate, marriage certificate, or dependent verification documents) must be submitted.</li>\n</ul>\n</li>\n<li>\n<p><strong>Age Limit</strong></p>\n<ul>\n<li>The applicant must be within the age range of 17&ndash;25 years for undergraduate scholarships.</li>\n<li>No age limit for dependents pursuing postgraduate studies or vocational training.</li>\n</ul>\n</li>\n<li>\n<p><strong>Academic Performance</strong></p>\n<ul>\n<li>Must have achieved a minimum cumulative grade point average (GPA) of <strong>3.0</strong> on a 4.0 scale (or equivalent) in their most recent academic year.</li>\n<li>Proof of academic transcripts must be provided.</li>\n</ul>\n</li>\n<li>\n<p><strong>Enrollment</strong></p>\n<ul>\n<li>Must be enrolled or have received an acceptance letter to an accredited college, university, or vocational training institution.</li>\n<li>Scholarships are available for full-time and part-time students.</li>\n</ul>\n</li>\n<li>\n<p><strong>Financial Need</strong></p>\n<ul>\n<li>Applicants must demonstrate financial need through supporting documentation such as family income statements, tax returns, or other relevant documents.</li>\n</ul>\n</li>\n<li>\n<p><strong>Employee Tenure</strong></p>\n<ul>\n<li>The sponsoring employee must have been employed with the organization for a minimum of <strong>2 years</strong> at the time of application.</li>\n</ul>\n</li>\n</ol>', '<h3><strong>Documents Required for Dependent Scholarship Application</strong></h3>\n<h4>1. <strong>Completed Application Form</strong></h4>\n<ul>\n<li>The official scholarship application form, filled out completely and accurately.</li>\n<li>Must include the applicant&rsquo;s personal details, academic history, and other required information.</li>\n</ul>\n<h4>2. <strong>Proof of Dependent Status</strong></h4>\n<ul>\n<li><strong>Birth Certificate</strong>: For children of employees.</li>\n<li><strong>Marriage Certificate</strong>: For spouses of employees.</li>\n<li><strong>Dependent Verification Letter</strong>: From the HR department of the sponsoring employee&rsquo;s organization, confirming the applicant&rsquo;s relationship to the employee.</li>\n</ul>\n<h4>3. <strong>Proof of Sponsoring Employee&rsquo;s Status</strong></h4>\n<ul>\n<li><strong>Employment Verification Letter</strong>: Issued by the employer, confirming the employee&rsquo;s current status and duration of employment (minimum tenure requirement, if applicable).</li>\n</ul>\n<h4>4. <strong>Academic Records</strong></h4>\n<ul>\n<li><strong>Transcripts</strong>: Most recent academic transcripts (high school, college, or vocational institution).</li>\n<li><strong>Acceptance Letter</strong>: Proof of enrollment or admission to an accredited institution for the upcoming academic year.</li>\n<li><strong>GPA Report</strong>: To confirm the applicant meets the minimum academic requirement (e.g., 3.0 GPA).</li>\n</ul>', 'Colegio De Sta Ana De Victorias', 1, '2025-01-23 19:08:24', '2025-01-31 20:57:13', 0),
(7, 'Dependent Second Child Scholarship', 7, 0, 2, 'Full tuition, allowance for books, monthly stipend of 4,000 - 7,000', '0000-00-00', '', '', '', 1, '2025-01-26 15:29:31', '2025-01-31 20:57:34', 0),
(8, 'Dependent Third Child Scholarship', 8, 0, 2, 'Full tuition, allowance for books, monthly stipend of 3,000 - 6,000', '0000-00-00', '', '', '', 1, '2025-01-26 15:29:41', '2025-01-31 20:57:54', 0),
(18, 'DOST Scholarship Program', 1, 0, 1, 'Full tuition, allowance for books, monthly stipend of ?7,000 - ?10,000', '2025-03-30', '<p>Must be a Filipino citizen, must be enrolled in a priority science and technology course, must pass the DOST scholarship exam</p>', '<p>Transcript of records, certificate of enrollment, birth certificate, recommendation letter</p>', 'Department of Science and Technology (DOST)', 1, '2025-01-26 22:24:22', '2025-01-26 22:26:26', 0),
(19, 'CHED Scholarship Program', 9, 0, 1, 'Full or partial tuition, monthly stipend of 5,000 - 10,000', '2025-04-29', '<p>Filipino citizen, enrolled in a CHED-accredited institution, based on financial need and academic performance</p>', '<p>Proof of income, birth certificate, grade transcript, recommendation letter</p>', 'Commission on Higher Education (CHED)', 1, '2025-01-26 22:36:23', '2025-01-26 22:38:48', 0),
(20, 'Philippine Business for Social Progress (PBSP) Scholarship', 10, 0, 1, 'Tuition fees, allowances for books, transportation, and living expenses of up to 10,000 per semester', '2025-07-30', '<p><span style=\"font-size: 8pt;\">Financially disadvantaged students with a high academic standing</span></p>', '<p><span style=\"font-size: 8pt;\">Family income statement, transcript of records, birth certificate</span></p>', 'Philippine Business for Social Progress (PBSP)', 1, '2025-01-26 22:37:55', '2025-01-28 16:03:13', 0),
(21, 'Basketball Scholarship (Junior)', 11, 9, 1, 'Full or partial tuition fee coverage, Sports gear and equipment allowance, Monthly stipend for meals and transportation, Access to professional coaching and training facilities', '2027-02-01', '<ul>\n<li><strong>Age Requirement</strong>: 12&ndash;17 years old</li>\n<li><strong>Academic Performance</strong>: Minimum GPA of 2.5 (or equivalent)</li>\n<li><strong>Basketball Skills</strong>: Demonstrated talent through tournaments or school competitions</li>\n<li><strong>Physical Fitness</strong>: Must pass a fitness and medical evaluation</li>\n<li><strong>Sportsmanship &amp; Discipline</strong>: No history of major disciplinary actions</li>\n<li><strong>Commitment</strong>: Must be willing to train regularly and represent the institution in competitions</li>\n</ul>', '<ul>\n<li>Completed scholarship application form</li>\n<li>Academic transcripts or latest report card</li>\n<li>Recommendation letter from a coach or PE teacher</li>\n<li>Proof of participation in basketball tournaments (certificates, awards, or video highlights)</li>\n<li>Medical certificate verifying physical fitness</li>\n<li>Personal statement or essay on &ldquo;Why I Deserve the Basketball Scholarship&rdquo;</li>\n<li>Proof of financial need (if applicable)</li>\n</ul>', 'Colegio De Sta Ana De Victorias', 1, '2025-01-28 12:09:50', '2025-01-31 20:55:04', 0),
(23, 'Basketball Scholarship  (Seniro High School)', 12, 9, 1, 'Full tuition, allowance for sports equipment, monthly stipend of 5,000 - 8,000', '2027-02-01', '<p>Sample</p>', '<p>Sample</p>', 'Sample', 1, '2025-01-31 20:54:45', '2025-01-31 20:58:48', 0);

-- --------------------------------------------------------

--
-- Table structure for table `scholarship_type`
--

CREATE TABLE `scholarship_type` (
  `TypeID` int(9) NOT NULL,
  `TypeName` varchar(25) DEFAULT NULL,
  `Description` text NOT NULL,
  `Deleted` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `scholarship_type`
--

INSERT INTO `scholarship_type` (`TypeID`, `TypeName`, `Description`, `Deleted`) VALUES
(1, 'Full Merit-Based Scholars', '', 0),
(2, 'Half Merit-Based Scholars', '', 0),
(3, 'Full Scholarship for STEM', '', 0),
(4, 'Industry-Specific Scholar', '', 0),
(5, 'Sports', '', 0),
(6, 'Employee Dependent (100%)', 'This scholarship is designed for 1st child of employees.', 0),
(7, 'Employee Dependent (50%)', 'This scholarship is designed for 2nd child of employees.', 0),
(8, 'Employee Dependent (25%)', 'This scholarship is designed for 3rd child of employees.', 0),
(9, 'Merit-based and need-base', '', 0),
(10, 'Need Based', '', 0),
(11, 'Junior Aspirants', 'Sports Basketball Scholarship for Junior Aspirants', 0),
(12, 'Senior High School', 'Sports Basketball Scholarship for Senior High School', 0),
(13, 'College', 'Sports Basketball Scholarship for College', 0),
(14, 'CHED', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `student_forms`
--

CREATE TABLE `student_forms` (
  `StudFormID` int(9) NOT NULL,
  `UserID` int(9) NOT NULL DEFAULT 0,
  `UserNumber` varchar(255) DEFAULT NULL,
  `FormID` int(9) NOT NULL DEFAULT 0,
  `SFDate` date DEFAULT NULL,
  `SFStatus` int(9) NOT NULL DEFAULT 0,
  `CreatedAt` datetime NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_forms`
--

INSERT INTO `student_forms` (`StudFormID`, `UserID`, `UserNumber`, `FormID`, `SFDate`, `SFStatus`, `CreatedAt`, `UpdatedAt`, `Deleted`) VALUES
(1, 5, '20250126224921', 7, '2025-01-29', 1, '2025-01-29 23:55:48', '2025-01-31 21:32:54', 1),
(2, 5, '20250126224921', 7, '2025-01-29', 0, '2025-01-29 23:55:58', '2025-01-29 23:57:35', 1),
(3, 5, '20250126224921', 7, '2025-01-29', 0, '2025-01-29 23:57:38', '2025-01-29 23:58:01', 1),
(4, 5, '20250126224921', 7, '2025-01-29', 0, '2025-01-29 23:58:05', '2025-01-31 21:30:19', 1),
(5, 20, '20250128113624', -1, '2025-01-31', 1, '2025-01-31 21:30:27', '2025-01-31 21:30:31', 0),
(6, 20, '20250128113624', 7, '2025-01-31', 1, '2025-01-31 21:32:25', '2025-01-31 21:32:32', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `LoginID` int(11) NOT NULL,
  `UserID` int(9) NOT NULL DEFAULT 0,
  `UserName` varchar(20) DEFAULT NULL,
  `PassWD` varchar(250) DEFAULT NULL,
  `PassWDText` varchar(25) NOT NULL,
  `UserTypeText` varchar(20) DEFAULT NULL,
  `UserTypeRID` int(9) UNSIGNED NOT NULL DEFAULT 0 COMMENT '0-administrator, 1 - student, 2 - Coaches, 3 - Department Heads, 4 - Teachers',
  `createdAt` datetime NOT NULL DEFAULT current_timestamp(),
  `updateAt` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `UserLoginStatus` tinyint(1) NOT NULL DEFAULT 0,
  `Deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`LoginID`, `UserID`, `UserName`, `PassWD`, `PassWDText`, `UserTypeText`, `UserTypeRID`, `createdAt`, `updateAt`, `UserLoginStatus`, `Deleted`) VALUES
(1, 1, 'Admin', 'e3afed0047b08059d0fada10f400c1e5', 'Admin', 'Administrator', 0, '2025-01-26 22:58:47', '2025-01-26 22:58:58', 0, 0),
(2, 2, 'Remegio', '3995f4a8d1dfabc51fe08115ffd4939e', '20250126224644', 'Student', 1, '2025-01-26 22:47:22', '2025-01-26 22:47:22', 0, 0),
(3, 3, 'Parel', '10fe386cd15931842b515876eddf755c', '20250126224729', 'Student', 1, '2025-01-26 22:48:28', '2025-01-26 22:48:28', 0, 0),
(4, 4, 'Morales', '417cc5bd2cc05fac0abf2c8f9b87eec0', '20250126224834', 'Student', 1, '2025-01-26 22:49:00', '2025-01-26 22:49:00', 0, 0),
(6, 6, 'Palma', '174b94be6f2773379939be36ad42f498', '20250126224601', 'Student', 1, '2025-01-26 22:46:33', '2025-01-26 22:58:12', 0, 0),
(8, 7, 'Doe', 'c96e8fa207a693148fe62201de9b3c40', '20250126230323', 'Department Heads', 3, '2025-01-26 23:04:20', '2025-01-26 23:04:20', 0, 0),
(9, 8, 'Smith', 'ef5e7173d31b4cb458ecc763648d75e0', '20250126230428', 'Teacher', 4, '2025-01-26 23:05:40', '2025-01-26 23:05:40', 0, 0),
(10, 9, 'Johnson', '11d63cdb53faa5f43cc414bffa26a72a', '20250126230545', 'Coaches', 2, '2025-01-26 23:06:44', '2025-01-28 11:10:01', 0, 0),
(19, 18, 'Chan', '387fabbc00abfc89fa95f7e7e61820f3', '20250128113225', 'Teacher', 4, '2025-01-28 11:32:55', '2025-01-28 11:32:55', 0, 0),
(20, 19, 'Corona', '7d8a729111ac5bad9c4b715de9dc731a', '20250128113331', 'Teacher', 4, '2025-01-28 11:34:07', '2025-01-28 11:34:07', 0, 0),
(21, 20, 'Temp', '45af9427c41f220a24f10febb17369cd', '20250128113624', 'Student', 1, '2025-01-28 11:37:15', '2025-01-31 21:01:04', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_data`
--

CREATE TABLE `user_data` (
  `UserID` int(11) NOT NULL,
  `UserNumber` varchar(255) DEFAULT NULL,
  `FirstName` varchar(25) DEFAULT NULL,
  `MiddleName` varchar(25) DEFAULT NULL,
  `LastName` varchar(25) DEFAULT NULL,
  `DateOfBirth` date DEFAULT NULL,
  `Gender` int(9) NOT NULL DEFAULT 0,
  `EmailAddress` varchar(50) DEFAULT NULL,
  `PhoneNumber` varchar(50) DEFAULT NULL,
  `Address` text NOT NULL,
  `City` int(9) NOT NULL DEFAULT 0,
  `Barangay` varchar(50) DEFAULT NULL,
  `JobTitle` varchar(255) DEFAULT NULL,
  `DepartmentID` int(9) NOT NULL DEFAULT 0,
  `YrSectionID` int(9) NOT NULL DEFAULT 0,
  `CourseID` int(9) NOT NULL DEFAULT 0,
  `BenefID` int(9) NOT NULL DEFAULT 0,
  `ScholarshipID` int(9) NOT NULL DEFAULT 0,
  `UserDate` date DEFAULT NULL,
  `Photo` varchar(250) DEFAULT NULL,
  `UserStatus` int(9) NOT NULL DEFAULT 0 COMMENT '0-active, 1-inactve, 2-Graduated, 3-Transferred, 4-Full Time, 5-Part-Time, 6-Consultant, 7-Probationary',
  `UserType` int(9) NOT NULL DEFAULT 0 COMMENT '0-administrator, 1 - student, 2 - Coaches, 3 - Department Heads, 4 - Teachers',
  `CreatedBy` int(9) NOT NULL DEFAULT 0,
  `CreatedAt` datetime NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_data`
--

INSERT INTO `user_data` (`UserID`, `UserNumber`, `FirstName`, `MiddleName`, `LastName`, `DateOfBirth`, `Gender`, `EmailAddress`, `PhoneNumber`, `Address`, `City`, `Barangay`, `JobTitle`, `DepartmentID`, `YrSectionID`, `CourseID`, `BenefID`, `ScholarshipID`, `UserDate`, `Photo`, `UserStatus`, `UserType`, `CreatedBy`, `CreatedAt`, `UpdatedAt`, `Deleted`) VALUES
(1, '20250126224640', 'Admin', 'Admin', 'Admin', '0000-00-00', 0, 'admin@gmail.com', '', 'Admin', 2, '', 'Scholarship Administrator', 7, 0, 0, 0, 0, '2021-02-01', NULL, 4, 0, 0, '2025-01-26 23:01:12', '2025-01-28 14:49:23', 0),
(2, '20250126224644', 'Arjie', 'test', 'Remegio', '0000-00-00', 0, 'arjieremegio@gmail.com', '', 'N/A', 0, '', NULL, 6, 5, 13, 0, 0, NULL, NULL, 1, 1, 0, '2025-01-26 22:47:22', '2025-01-31 20:05:07', 0),
(3, '20250126224729', 'Ruel', 'Robles', 'Parel', '0000-00-00', 0, 'ruelroblesparel192007@gmail.com', '', 'N/A', 0, '', NULL, 6, 5, 13, 0, 0, NULL, NULL, 1, 1, 0, '2025-01-26 22:48:28', '2025-01-31 20:05:50', 0),
(4, '20250126224834', 'Bhal', 'test', 'Morales', '0000-00-00', 0, 'bhalmorales123@gmail.com', '', 'N/A', 0, '', NULL, 6, 5, 13, 0, 0, NULL, NULL, 1, 1, 0, '2025-01-26 22:49:00', '2025-01-31 20:05:45', 0),
(6, '20250126224601', 'Cris John', 'test', 'Palma', '0000-00-00', 0, 'crisjohnpalma3000@gmail.com', '', 'N/A', 0, '', NULL, 6, 5, 13, 0, 0, NULL, NULL, 1, 1, 0, '2025-01-26 22:46:33', '2025-01-31 20:05:35', 0),
(7, '20250126230323', 'John', 'Ge', 'Doe', '0000-00-00', 0, 'johndoe@gmail.com', '', 'N/A', 0, '', 'Hacker', 1, 0, 0, 0, 0, '2024-03-31', NULL, 4, 3, 0, '2025-01-26 23:04:20', '2025-01-28 14:48:05', 0),
(8, '20250126230428', 'Jane', 'Swal', 'Smith', '0000-00-00', 0, 'jannesmith@gmail.com', '', 'N/A', 1, '', 'Scientist', 5, 0, 0, 0, 0, '1994-02-01', NULL, 4, 4, 0, '2025-01-26 23:05:40', '2025-01-28 14:48:15', 0),
(9, '20250126230545', 'Michael', 'Bol', 'Johnson', '0000-00-00', 0, 'michaeljohnson@gmail.com', '', 'N/A', 2, '', 'Coach', 8, 0, 0, 0, 0, '1784-02-01', NULL, 4, 2, 0, '2025-01-26 23:06:44', '2025-01-28 14:49:58', 0),
(16, '20250128112731', 'Michael', 'asd', 'johnson', '0000-00-00', 0, 'Michaeljohnson@gmail.com', '', 'asd', 0, '', '', 0, 0, 0, 0, 0, '0000-00-00', NULL, -1, -1, 0, '2025-01-28 11:27:49', '2025-01-28 11:31:58', 1),
(17, '20250128112731', 'Michael', 'asd', 'johnson', '0000-00-00', 0, 'Michaeljohnson@gmail.com', '', 'asd', 0, '', '', 0, 0, 0, 0, 0, '0000-00-00', NULL, -1, -1, 0, '2025-01-28 11:27:56', '2025-01-28 11:28:06', 1),
(18, '20250128113225', 'Milan', 'Chou', 'Chan', '0000-00-00', 0, 'temporaryml222@gmail.com', '', 'na', 0, '', 'test', 1, 0, 0, 0, 0, '2023-03-02', NULL, 4, 4, 0, '2025-01-28 11:32:55', '2025-01-28 17:39:51', 0),
(19, '20250128113331', 'Dallas', 'Murillo', 'Corona', '0000-00-00', 0, 'dallascorona@gmail.com', '', 'na', 0, '', 'Test', 2, 0, 0, 0, 0, '2020-03-02', NULL, 4, 4, 0, '2025-01-28 11:34:07', '2025-01-28 11:34:07', 0),
(20, '20250128113624', 'Temp', 'Barr', 'Poraryml222', '1998-02-02', 1, 'temporaryml222@gmail.com', '09613123221', 'NA', 2, 'NA', NULL, 4, 7, 11, 8, 6, NULL, NULL, 1, 1, 0, '2025-01-28 11:37:15', '2025-01-31 21:04:42', 0);

-- --------------------------------------------------------

--
-- Table structure for table `year_section`
--

CREATE TABLE `year_section` (
  `YrSectionID` int(9) NOT NULL,
  `Years` varchar(25) NOT NULL,
  `Section` varchar(25) NOT NULL,
  `Section_name` varchar(25) NOT NULL,
  `Section_code` varchar(25) NOT NULL,
  `Deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `year_section`
--

INSERT INTO `year_section` (`YrSectionID`, `Years`, `Section`, `Section_name`, `Section_code`, `Deleted`) VALUES
(1, '2024', 'B1', 'Maasim', 'M1023', 1),
(2, '1st Year', 'A', '', '', 0),
(3, '2nd Year', 'B', '', '', 0),
(4, '3rd Year', 'C', '', '', 0),
(5, '4th Year', 'D', '', '', 0),
(6, '1st Year', 'B', '', '', 0),
(7, '1st Year', 'C', '', '', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`AplID`),
  ADD KEY `ScholarshipID` (`ScholarshipID`,`UserID`,`ReviewerID`),
  ADD KEY `AplSchID` (`AplSchID`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`CourseID`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`DepartmentID`);

--
-- Indexes for table `email_notifications`
--
ALTER TABLE `email_notifications`
  ADD PRIMARY KEY (`ENID`),
  ADD KEY `FromUserID` (`FromUserID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `EmailStatus` (`EmailStatus`),
  ADD KEY `NotifStatus` (`EmailType`);

--
-- Indexes for table `filemanager`
--
ALTER TABLE `filemanager`
  ADD PRIMARY KEY (`FileID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `ScholarshipID` (`ScholarshipID`),
  ADD KEY `AplID` (`AplID`),
  ADD KEY `ENID` (`ENID`);

--
-- Indexes for table `forms`
--
ALTER TABLE `forms`
  ADD PRIMARY KEY (`FormID`);

--
-- Indexes for table `scholars`
--
ALTER TABLE `scholars`
  ADD PRIMARY KEY (`ScholarID`),
  ADD KEY `ScholarshipID` (`ScholarshipID`,`UserID`,`AplID`);

--
-- Indexes for table `scholarship`
--
ALTER TABLE `scholarship`
  ADD PRIMARY KEY (`ScholarshipID`),
  ADD KEY `ScholarshipType` (`ScholarshipType`),
  ADD KEY `ResponSch` (`ResponSch`);

--
-- Indexes for table `scholarship_type`
--
ALTER TABLE `scholarship_type`
  ADD PRIMARY KEY (`TypeID`);

--
-- Indexes for table `student_forms`
--
ALTER TABLE `student_forms`
  ADD PRIMARY KEY (`StudFormID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`LoginID`),
  ADD KEY `UserID` (`UserID`,`UserTypeRID`);

--
-- Indexes for table `user_data`
--
ALTER TABLE `user_data`
  ADD PRIMARY KEY (`UserID`),
  ADD KEY `DepartmentID` (`DepartmentID`,`YrSectionID`,`CourseID`),
  ADD KEY `BenefID` (`BenefID`),
  ADD KEY `ScholarshipID` (`ScholarshipID`);

--
-- Indexes for table `year_section`
--
ALTER TABLE `year_section`
  ADD PRIMARY KEY (`YrSectionID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `AplID` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `CourseID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `DepartmentID` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `email_notifications`
--
ALTER TABLE `email_notifications`
  MODIFY `ENID` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `filemanager`
--
ALTER TABLE `filemanager`
  MODIFY `FileID` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `forms`
--
ALTER TABLE `forms`
  MODIFY `FormID` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `scholars`
--
ALTER TABLE `scholars`
  MODIFY `ScholarID` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `scholarship`
--
ALTER TABLE `scholarship`
  MODIFY `ScholarshipID` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `scholarship_type`
--
ALTER TABLE `scholarship_type`
  MODIFY `TypeID` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `student_forms`
--
ALTER TABLE `student_forms`
  MODIFY `StudFormID` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `LoginID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `user_data`
--
ALTER TABLE `user_data`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `year_section`
--
ALTER TABLE `year_section`
  MODIFY `YrSectionID` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
