-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 17, 2023 at 08:38 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `csce310_team14`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `App_Num` int(11) NOT NULL,
  `Program_Num` int(11) NOT NULL,
  `UIN` int(11) NOT NULL,
  `Uncom_Cert` varchar(11) NOT NULL,
  `Com_Cert` varchar(11) NOT NULL,
  `Purpose_Statement` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `certification`
--

CREATE TABLE `certification` (
  `Cert_ID` int(11) NOT NULL,
  `Level` varchar(11) NOT NULL,
  `Name` varchar(11) NOT NULL,
  `Description` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cert_enrollment`
--

CREATE TABLE `cert_enrollment` (
  `CertE_Num` int(11) NOT NULL,
  `UIN` int(11) NOT NULL,
  `Cert_ID` int(11) NOT NULL,
  `Status` varchar(11) NOT NULL,
  `Training_Status` varchar(11) NOT NULL,
  `Program_Num` int(11) NOT NULL,
  `Semester` varchar(11) NOT NULL,
  `YEAR` year(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `Class_ID` int(11) NOT NULL,
  `Name` varchar(11) NOT NULL,
  `Description` varchar(11) NOT NULL,
  `Type` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class_enrollment`
--

CREATE TABLE `class_enrollment` (
  `CE_NUM` int(11) NOT NULL,
  `UIN` int(11) NOT NULL,
  `Class_ID` int(11) NOT NULL,
  `Status` varchar(11) NOT NULL,
  `Semester` varchar(11) NOT NULL,
  `Year` year(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `college student`
--

CREATE TABLE `college_student` (
  `UIN` int(11) NOT NULL,
  `Gender` varchar(11) NOT NULL,
  `Hispanic/Latino` binary(11) NOT NULL,
  `Race` varchar(11) NOT NULL,
  `U.S. Citizen` binary(11) NOT NULL,
  `First_Generation` binary(11) NOT NULL,
  `DoB` date NOT NULL,
  `GPA` float NOT NULL,
  `Major` varchar(11) NOT NULL,
  `Minor #1` varchar(11) NOT NULL,
  `Minor #2` varchar(11) NOT NULL,
  `Expected_Graduation` smallint(11) NOT NULL,
  `School` varchar(11) NOT NULL,
  `Classification` varchar(11) NOT NULL,
  `Phone` int(11) NOT NULL,
  `Student_Type` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `document`
--

CREATE TABLE `document` (
  `Doc_Num` int(11) NOT NULL,
  `App_Num` int(11) NOT NULL,
  `Link` varchar(11) NOT NULL,
  `Doc_Type` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `Event_ID` int(11) NOT NULL,
  `UIN` int(11) NOT NULL,
  `Program_Num` int(11) NOT NULL,
  `Start_Date` date NOT NULL,
  `Time` time(6) NOT NULL,
  `Location` varchar(11) NOT NULL,
  `End_Date` date NOT NULL,
  `Event_Type` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_tracking`
--

CREATE TABLE `event_tracking` (
  `ET_Num` int(11) NOT NULL,
  `Event_ID` int(11) NOT NULL,
  `UIN` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `internship`
--

CREATE TABLE `internship` (
  `Intern_ID` int(11) NOT NULL,
  `Name` varchar(11) NOT NULL,
  `Description` varchar(11) NOT NULL,
  `Is_Gov` binary(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `intern_app`
--

CREATE TABLE `intern_app` (
  `IA_Num` int(11) NOT NULL,
  `UIN` int(11) NOT NULL,
  `Intern_ID` int(11) NOT NULL,
  `Status` varchar(11) NOT NULL,
  `Year` year(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `Program_Num` int(11) NOT NULL,
  `Name` varchar(11) NOT NULL,
  `Description` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `track`
--

CREATE TABLE `track` (
  `Program_Num` int(11) NOT NULL,
  `UIN` int(11) NOT NULL,
  `Tracking_Num` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `UIN` int(11) NOT NULL,
  `First_Name` varchar(11) NOT NULL,
  `M_Initial` char(11) NOT NULL,
  `Last_Name` varchar(11) NOT NULL,
  `Username` varchar(11) NOT NULL,
  `Passwords` varchar(11) NOT NULL,
  `User_Type` varchar(11) NOT NULL,
  `Email` varchar(11) NOT NULL,
  `Discord_Name` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`App_Num`),
  ADD KEY `fk program_num` (`Program_Num`),
  ADD KEY `fk uin app` (`UIN`);

--
-- Indexes for table `certification`
--
ALTER TABLE `certification`
  ADD PRIMARY KEY (`Cert_ID`);

--
-- Indexes for table `cert_enrollment`
--
ALTER TABLE `cert_enrollment`
  ADD PRIMARY KEY (`CertE_Num`),
  ADD KEY `fk_cert_id` (`Cert_ID`),
  ADD KEY `foreign key uin` (`UIN`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`Class_ID`);

--
-- Indexes for table `class_enrollment`
--
ALTER TABLE `class_enrollment`
  ADD PRIMARY KEY (`CE_NUM`),
  ADD KEY `fk class_id` (`Class_ID`),
  ADD KEY `fk UIN` (`UIN`);

--
-- Indexes for table `college student`
--
ALTER TABLE `college student`
  ADD PRIMARY KEY (`UIN`);

--
-- Indexes for table `document`
--
ALTER TABLE `document`
  ADD PRIMARY KEY (`Doc_Num`),
  ADD KEY `fk app_num` (`App_Num`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`Event_ID`),
  ADD KEY `fk_program` (`Program_Num`),
  ADD KEY `fk_uin_user` (`UIN`);

--
-- Indexes for table `event_tracking`
--
ALTER TABLE `event_tracking`
  ADD PRIMARY KEY (`ET_Num`),
  ADD KEY `fk event` (`Event_ID`),
  ADD KEY `fk uin user` (`UIN`);

--
-- Indexes for table `internship`
--
ALTER TABLE `internship`
  ADD PRIMARY KEY (`Intern_ID`);

--
-- Indexes for table `intern_app`
--
ALTER TABLE `intern_app`
  ADD PRIMARY KEY (`IA_Num`),
  ADD KEY `fk_UIN` (`UIN`),
  ADD KEY `fk_Intern_ID` (`Intern_ID`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`Program_Num`);

--
-- Indexes for table `track`
--
ALTER TABLE `track`
  ADD PRIMARY KEY (`Tracking_Num`),
  ADD KEY `fk program` (`Program_Num`),
  ADD KEY `fk uin track` (`UIN`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UIN`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `fk program_num` FOREIGN KEY (`Program_Num`) REFERENCES `programs` (`Program_Num`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk uin app` FOREIGN KEY (`UIN`) REFERENCES `college student` (`UIN`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cert_enrollment`
--
ALTER TABLE `cert_enrollment`
  ADD CONSTRAINT `fk_cert_id` FOREIGN KEY (`Cert_ID`) REFERENCES `certification` (`Cert_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `foreign key uin` FOREIGN KEY (`UIN`) REFERENCES `college student` (`UIN`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `class_enrollment`
--
ALTER TABLE `class_enrollment`
  ADD CONSTRAINT `fk UIN` FOREIGN KEY (`UIN`) REFERENCES `college student` (`UIN`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk class_id` FOREIGN KEY (`Class_ID`) REFERENCES `classes` (`Class_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `document`
--
ALTER TABLE `document`
  ADD CONSTRAINT `fk app_num` FOREIGN KEY (`App_Num`) REFERENCES `applications` (`App_Num`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `fk_program` FOREIGN KEY (`Program_Num`) REFERENCES `programs` (`Program_Num`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_uin_user` FOREIGN KEY (`UIN`) REFERENCES `user` (`UIN`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `event_tracking`
--
ALTER TABLE `event_tracking`
  ADD CONSTRAINT `fk event` FOREIGN KEY (`Event_ID`) REFERENCES `event` (`Event_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk uin user` FOREIGN KEY (`UIN`) REFERENCES `user` (`UIN`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `intern_app`
--
ALTER TABLE `intern_app`
  ADD CONSTRAINT `fk_Intern_ID` FOREIGN KEY (`Intern_ID`) REFERENCES `internship` (`Intern_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_UIN` FOREIGN KEY (`UIN`) REFERENCES `college student` (`UIN`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `track`
--
ALTER TABLE `track`
  ADD CONSTRAINT `fk program` FOREIGN KEY (`Program_Num`) REFERENCES `programs` (`Program_Num`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk uin track` FOREIGN KEY (`UIN`) REFERENCES `college student` (`UIN`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
