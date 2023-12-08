-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 08, 2023 at 05:00 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

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
-- Stand-in structure for view `appandlink`
-- (See below for the actual view)
--
CREATE TABLE `appandlink` (
`App_Num` int(11)
,`Link` varchar(50)
);

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `App_Num` int(11) NOT NULL,
  `Program_Num` int(11) NOT NULL,
  `UIN` int(11) NOT NULL,
  `Uncom_Cert` varchar(30) NOT NULL,
  `Com_Cert` varchar(30) NOT NULL,
  `Purpose_Statement` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`App_Num`, `Program_Num`, `UIN`, `Uncom_Cert`, `Com_Cert`, `Purpose_Statement`) VALUES
(5, 3, 12345678, '', 'CSCE 4402', '0'),
(6, 3, 94391, '', 'CSCE 4402', '0');

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
  `Status` varchar(30) NOT NULL,
  `Training_Status` varchar(30) NOT NULL,
  `Program_Num` int(11) NOT NULL,
  `Semester` varchar(30) NOT NULL,
  `YEAR` year(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `Class_ID` int(11) NOT NULL,
  `Name` varchar(30) NOT NULL,
  `Description` varchar(30) NOT NULL,
  `Type` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class_enrollment`
--

CREATE TABLE `class_enrollment` (
  `CE_NUM` int(11) NOT NULL,
  `UIN` int(11) NOT NULL,
  `Class_ID` int(11) NOT NULL,
  `Status` varchar(30) NOT NULL,
  `Semester` varchar(30) NOT NULL,
  `Year` year(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `college_student`
--

CREATE TABLE `college_student` (
  `UIN` int(11) NOT NULL,
  `Gender` varchar(30) NOT NULL,
  `Hispanic_Latino` int(1) NOT NULL,
  `Race` varchar(30) NOT NULL,
  `US_Citizen` int(1) NOT NULL,
  `First_Generation` int(1) NOT NULL,
  `DoB` date NOT NULL,
  `GPA` float NOT NULL,
  `Major` varchar(30) NOT NULL,
  `Minor_1` varchar(30) NOT NULL,
  `Minor_2` varchar(30) NOT NULL,
  `Expected_Graduation` smallint(11) NOT NULL,
  `School` varchar(30) NOT NULL,
  `Classification` varchar(30) NOT NULL,
  `Phone` int(20) NOT NULL,
  `Student_Type` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `college_student`
--

INSERT INTO `college_student` (`UIN`, `Gender`, `Hispanic_Latino`, `Race`, `US_Citizen`, `First_Generation`, `DoB`, `GPA`, `Major`, `Minor_1`, `Minor_2`, `Expected_Graduation`, `School`, `Classification`, `Phone`, `Student_Type`) VALUES
(14903, 'female', 0, 'white', 0, 1, '2023-12-23', 4, 'Computer Science', 'Math', 'Art', 2024, 'Texas A&M', 'Senior', 4394342, 'college'),
(94391, 'male', 0, 'black', 1, 1, '2003-02-04', 3.54, 'Applied Math', '', '', 2025, 'Texas A&M', 'Junior', 34244232, 'k12'),
(99556, 'Male', 1, 'White', 1, 1, '2023-12-03', 4.25, 'Finance', '', 'Art', 2024, 'Texas A&M', 'Senior', 2147483647, 'college'),
(765543, 'male', 0, 'black', 0, 0, '2003-02-12', 3.22, 'Aerospace', 'Media', 'Math', 2027, 'Texas A&M', 'Freshman', 2147483647, 'college'),
(1404075, 'female', 0, 'Asian', 1, 0, '2006-12-13', 0, '', '', '', 2030, 'Katy High School', '', 2147483647, 'k12'),
(12345678, 'Male', 0, 'Asian', 0, 0, '2023-12-04', 3.14, 'CSCE', '', '', 2025, 'Engr', 'Soph', 1111111111, 'New'),
(130007950, 'male', 0, 'white', 1, 0, '1977-09-21', 4, 'Sports Science', '', '', 2026, 'Texas A&M', 'Sophomore', 2147483647, 'college');

-- --------------------------------------------------------

--
-- Table structure for table `document`
--

CREATE TABLE `document` (
  `Doc_Num` int(11) NOT NULL,
  `App_Num` int(11) NOT NULL,
  `Link` varchar(50) NOT NULL,
  `Doc_Type` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `document`
--

INSERT INTO `document` (`Doc_Num`, `App_Num`, `Link`, `Doc_Type`) VALUES
(2, 5, 'bobby.bobby', 'pdf'),
(3, 6, 'bobby.bobby', 'pdf');

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `Event_ID` int(11) NOT NULL,
  `UIN` int(11) NOT NULL,
  `Program_Num` int(11) NOT NULL,
  `Start_Date` date NOT NULL,
  `Start_Time` time NOT NULL,
  `Location` varchar(30) NOT NULL,
  `End_Date` date NOT NULL,
  `End_Time` time NOT NULL,
  `Event_Type` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`Event_ID`, `UIN`, `Program_Num`, `Start_Date`, `Start_Time`, `Location`, `End_Date`, `End_Time`, `Event_Type`) VALUES
(1, 5554444, 2, '2023-11-30', '21:47:00', 'Mcdonalds', '2023-12-08', '21:48:00', 'camp');

--
-- Triggers `event`
--
DELIMITER $$
CREATE TRIGGER `deleteEventTracking` AFTER DELETE ON `event` FOR EACH ROW DELETE FROM event_tracking WHERE Event_ID = OLD.Event_ID
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `insertEventTracking` AFTER INSERT ON `event` FOR EACH ROW BEGIN
    DECLARE newETNum INT;

    -- Get the last ET_Num and add 1 to it
    SELECT COALESCE(MAX(ET_Num), 0) + 1 INTO newETNum FROM event_tracking;

    -- Insert into event_tracking table
    INSERT INTO event_tracking (ET_Num, Event_ID, UIN)
    VALUES (newETNum, NEW.Event_ID, NEW.UIN);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `eventattendance`
-- (See below for the actual view)
--
CREATE TABLE `eventattendance` (
`ET_Num` int(11)
,`Event_ID` int(11)
,`Program_Name` varchar(30)
,`First_Name` varchar(30)
,`Last_Name` varchar(30)
,`User_Type` varchar(30)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `eventview`
-- (See below for the actual view)
--
CREATE TABLE `eventview` (
`Event_ID` int(11)
,`Program_Name` varchar(30)
,`Program_Num` int(11)
,`Start_Date` date
,`Start_Time` time
,`Location` varchar(30)
,`End_Date` date
,`End_Time` time
,`Event_Type` varchar(30)
);

-- --------------------------------------------------------

--
-- Table structure for table `event_tracking`
--

CREATE TABLE `event_tracking` (
  `ET_Num` int(11) NOT NULL,
  `Event_ID` int(11) NOT NULL,
  `UIN` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event_tracking`
--

INSERT INTO `event_tracking` (`ET_Num`, `Event_ID`, `UIN`) VALUES
(1, 1, 5554444),
(2, 1, 94391);

-- --------------------------------------------------------

--
-- Table structure for table `internship`
--

CREATE TABLE `internship` (
  `Intern_ID` int(11) NOT NULL,
  `Name` varchar(30) NOT NULL,
  `Description` varchar(30) NOT NULL,
  `Is_Gov` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `intern_app`
--

CREATE TABLE `intern_app` (
  `IA_Num` int(11) NOT NULL,
  `UIN` int(11) NOT NULL,
  `Intern_ID` int(11) NOT NULL,
  `Status` varchar(30) NOT NULL,
  `Year` year(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `Program_Num` int(11) NOT NULL,
  `Name` varchar(30) NOT NULL,
  `Description` varchar(30) NOT NULL,
  `Visible` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`Program_Num`, `Name`, `Description`, `Visible`) VALUES
(2, 'small', 'it small', 0),
(3, 'bobby', 'bob\'s prog', 1);

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `report_id` int(11) NOT NULL,
  `report_date` datetime DEFAULT current_timestamp(),
  `report_content` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`report_id`, `report_date`, `report_content`) VALUES
(1, '2023-12-06 01:04:09', 'Report Generated on: 2023-12-06 08:04:09\n\nTotal Students: 1\n'),
(2, '2023-12-06 15:57:46', 'Report Generated on: 2023-12-06 22:57:46\n\nTotal Students: 1\n# of students with completed certs: 1\n# of Hispanic/Latino students: 0\n# of male students students: 1\n# of female students students: 0\nMajors Distribution: CSCE:1\n'),
(3, '2023-12-06 15:57:47', 'Report Generated on: 2023-12-06 22:57:47\n\nTotal Students: 1\n# of students with completed certs: 1\n# of Hispanic/Latino students: 0\n# of male students students: 1\n# of female students students: 0\nMajors Distribution: CSCE:1\n');

-- --------------------------------------------------------

--
-- Stand-in structure for view `studentprogramapplicationinfo`
-- (See below for the actual view)
--
CREATE TABLE `studentprogramapplicationinfo` (
`Student_UIN` int(11)
,`Program_Num` int(11)
,`Program_Name` varchar(30)
,`Program_Description` varchar(30)
,`App_Num` int(11)
,`Uncom_Cert` varchar(30)
,`Com_Cert` varchar(30)
,`Purpose_Statement` longtext
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `studenttable`
-- (See below for the actual view)
--
CREATE TABLE `studenttable` (
`UIN` int(11)
,`First_Name` varchar(30)
,`M_Initial` char(30)
,`Last_Name` varchar(30)
,`Username` varchar(30)
,`Passwords` varchar(30)
,`User_Type` varchar(30)
,`Email` varchar(30)
,`Discord_Name` varchar(30)
,`access` int(1)
,`Gender` varchar(30)
,`Hispanic_Latino` int(1)
,`Race` varchar(30)
,`US_Citizen` int(1)
,`First_Generation` int(1)
,`DoB` date
,`GPA` float
,`Major` varchar(30)
,`Minor_1` varchar(30)
,`Minor_2` varchar(30)
,`Expected_Graduation` smallint(11)
,`School` varchar(30)
,`Classification` varchar(30)
,`Phone` int(20)
,`Student_Type` varchar(30)
);

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
  `First_Name` varchar(30) NOT NULL,
  `M_Initial` char(30) NOT NULL,
  `Last_Name` varchar(30) NOT NULL,
  `Username` varchar(30) NOT NULL,
  `Passwords` varchar(30) NOT NULL,
  `User_Type` varchar(30) NOT NULL,
  `Email` varchar(30) NOT NULL,
  `Discord_Name` varchar(30) NOT NULL,
  `access` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UIN`, `First_Name`, `M_Initial`, `Last_Name`, `Username`, `Passwords`, `User_Type`, `Email`, `Discord_Name`, `access`) VALUES
(11, 'sa', 'blah', 'blah', 'test123', '123', 'admin', 'test@gmail.com', 'tester', 1),
(22, 'test2', 'm', 'testing', 'hellotest', 'password', 'tester2', 'tester2@gmail.com', 'tester2341', 1),
(1438, 'Petrino', 'E', 'Scott', 'Adam.Scott', 'password', 'admin', 'adam@tamu.edu', 'adam123', 0),
(3434, 'test', 'test2', 'test3', 'testete', '123', 'admin', 'sam@gmail.com', '23232', 1),
(12345, 'L', '', 'C', 'LC', '123', 'student', 'lc@gmail.co', 'lc123', 1),
(14903, 'Caleb', 'A', 'Williams', 'CalebWilliams', '123', 'student', 'caleb@tamu.edu', 'caleb01', 0),
(94391, 'Jakub', 'B', 'Turner', 'JakubTurner', 'hello', 'student', 'JBT@tamu.edu', 'jTurner', 1),
(99556, 'Billy', 'R', 'Boone', 'Billy.Boone', '123', 'student', 'billy@tamu.edu', 'billyboy23', 1),
(112233, 'Jake', 'C', 'Carter', 'jcarter007', '123', 'admin', 'jcarter2001@gmail.com', 'jcarter99', 0),
(765543, 'Carter', 'P', 'Matthews', 'carter.matthews', '123', 'student', 'carter.matthews@tamu.edu', 'carterm33', 1),
(1404075, 'Jessica', 'E', 'Zamora', 'Chris.Zamora', 'goodpass', 'student', 'jessica.zamora@tamu.edu', 'jesszam55', 1),
(5554444, 'Carter', 'A', 'Johnson', 'cjohnson', '123', 'admin', 'carter.johnson@tamu.edu', 'cj23', 1),
(123456788, 'Jack', 'Q', 'Smith', 'jacksmith', 'pass', 'admin', 'jsmith@tamu', 'user123', 1),
(123456789, 'Sam', 'E', 'Hirvilampi', 'samhirvilam', '$2y$10$qKOy', 'admin', 'samuli.hirv', 'samh', 0),
(130007950, 'Mike', 'R', 'Elko', 'Mike.Elko', 'password123', 'student', 'mike.elko@tamu.edu', 'mikeElko23', 1),
(834784924, 'steven', '', '', '', '', 'admin', '', '', 0);

--
-- Triggers `user`
--
DELIMITER $$
CREATE TRIGGER `deleteUser` AFTER DELETE ON `user` FOR EACH ROW IF OLD.User_Type = 'student' THEN
        DELETE FROM college_student WHERE UIN = OLD.UIN;
    END IF
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `userinternships`
-- (See below for the actual view)
--
CREATE TABLE `userinternships` (
`Student_UIN` int(11)
,`Internship_ID` int(11)
,`Name` varchar(30)
,`Description` varchar(30)
,`Government_Internship` int(1)
,`Application_Number` int(11)
,`Application_Status` varchar(30)
,`Year_Applied` year(4)
);

-- --------------------------------------------------------

--
-- Structure for view `appandlink`
--
DROP TABLE IF EXISTS `appandlink`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `appandlink`  AS SELECT `applications`.`App_Num` AS `App_Num`, `document`.`Link` AS `Link` FROM (`applications` join `document` on(`applications`.`App_Num` = `document`.`App_Num`)) ;

-- --------------------------------------------------------

--
-- Structure for view `eventattendance`
--
DROP TABLE IF EXISTS `eventattendance`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `eventattendance`  AS SELECT `et`.`ET_Num` AS `ET_Num`, `et`.`Event_ID` AS `Event_ID`, `p`.`Name` AS `Program_Name`, `u`.`First_Name` AS `First_Name`, `u`.`Last_Name` AS `Last_Name`, `u`.`User_Type` AS `User_Type` FROM (((`event_tracking` `et` join `event` `e` on(`et`.`Event_ID` = `e`.`Event_ID`)) join `user` `u` on(`et`.`UIN` = `u`.`UIN`)) join `programs` `p` on(`e`.`Program_Num` = `p`.`Program_Num`)) ;

-- --------------------------------------------------------

--
-- Structure for view `eventview`
--
DROP TABLE IF EXISTS `eventview`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `eventview`  AS SELECT `e`.`Event_ID` AS `Event_ID`, `p`.`Name` AS `Program_Name`, `e`.`Program_Num` AS `Program_Num`, `e`.`Start_Date` AS `Start_Date`, `e`.`Start_Time` AS `Start_Time`, `e`.`Location` AS `Location`, `e`.`End_Date` AS `End_Date`, `e`.`End_Time` AS `End_Time`, `e`.`Event_Type` AS `Event_Type` FROM (`event` `e` join `programs` `p` on(`e`.`Program_Num` = `p`.`Program_Num`)) ;

-- --------------------------------------------------------

--
-- Structure for view `studentprogramapplicationinfo`
--
DROP TABLE IF EXISTS `studentprogramapplicationinfo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `studentprogramapplicationinfo`  AS SELECT `a`.`UIN` AS `Student_UIN`, `p`.`Program_Num` AS `Program_Num`, `p`.`Name` AS `Program_Name`, `p`.`Description` AS `Program_Description`, `a`.`App_Num` AS `App_Num`, `a`.`Uncom_Cert` AS `Uncom_Cert`, `a`.`Com_Cert` AS `Com_Cert`, `a`.`Purpose_Statement` AS `Purpose_Statement` FROM (`applications` `a` join `programs` `p` on(`a`.`Program_Num` = `p`.`Program_Num`)) ;

-- --------------------------------------------------------

--
-- Structure for view `studenttable`
--
DROP TABLE IF EXISTS `studenttable`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `studenttable`  AS SELECT `a`.`UIN` AS `UIN`, `a`.`First_Name` AS `First_Name`, `a`.`M_Initial` AS `M_Initial`, `a`.`Last_Name` AS `Last_Name`, `a`.`Username` AS `Username`, `a`.`Passwords` AS `Passwords`, `a`.`User_Type` AS `User_Type`, `a`.`Email` AS `Email`, `a`.`Discord_Name` AS `Discord_Name`, `a`.`access` AS `access`, `b`.`Gender` AS `Gender`, `b`.`Hispanic_Latino` AS `Hispanic_Latino`, `b`.`Race` AS `Race`, `b`.`US_Citizen` AS `US_Citizen`, `b`.`First_Generation` AS `First_Generation`, `b`.`DoB` AS `DoB`, `b`.`GPA` AS `GPA`, `b`.`Major` AS `Major`, `b`.`Minor_1` AS `Minor_1`, `b`.`Minor_2` AS `Minor_2`, `b`.`Expected_Graduation` AS `Expected_Graduation`, `b`.`School` AS `School`, `b`.`Classification` AS `Classification`, `b`.`Phone` AS `Phone`, `b`.`Student_Type` AS `Student_Type` FROM (`user` `a` join `college_student` `b` on(`a`.`UIN` = `b`.`UIN`)) ;

-- --------------------------------------------------------

--
-- Structure for view `userinternships`
--
DROP TABLE IF EXISTS `userinternships`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `userinternships`  AS SELECT `x`.`UIN` AS `Student_UIN`, `i`.`Intern_ID` AS `Internship_ID`, `i`.`Name` AS `Name`, `i`.`Description` AS `Description`, `i`.`Is_Gov` AS `Government_Internship`, `x`.`IA_Num` AS `Application_Number`, `x`.`Status` AS `Application_Status`, `x`.`Year` AS `Year_Applied` FROM (`intern_app` `x` join `internship` `i` on(`x`.`Intern_ID` = `i`.`Intern_ID`)) ;

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
  ADD KEY `fk UIN` (`UIN`),
  ADD KEY `academicSem` (`Semester`,`Year`) USING BTREE;

--
-- Indexes for table `college_student`
--
ALTER TABLE `college_student`
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
  ADD KEY `studentSignedUp` (`UIN`) USING BTREE;

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
  ADD PRIMARY KEY (`Program_Num`),
  ADD UNIQUE KEY `ProgNumAndName` (`Program_Num`,`Name`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`report_id`);

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
  ADD PRIMARY KEY (`UIN`),
  ADD KEY `admin_search` (`User_Type`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `App_Num` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `Program_Num` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `fk program_num` FOREIGN KEY (`Program_Num`) REFERENCES `programs` (`Program_Num`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk uin app` FOREIGN KEY (`UIN`) REFERENCES `college_student` (`UIN`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cert_enrollment`
--
ALTER TABLE `cert_enrollment`
  ADD CONSTRAINT `fk_cert_id` FOREIGN KEY (`Cert_ID`) REFERENCES `certification` (`Cert_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `foreign key uin` FOREIGN KEY (`UIN`) REFERENCES `college_student` (`UIN`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `class_enrollment`
--
ALTER TABLE `class_enrollment`
  ADD CONSTRAINT `fk UIN` FOREIGN KEY (`UIN`) REFERENCES `college_student` (`UIN`) ON DELETE CASCADE ON UPDATE CASCADE,
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
  ADD CONSTRAINT `fk_UIN` FOREIGN KEY (`UIN`) REFERENCES `college_student` (`UIN`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `track`
--
ALTER TABLE `track`
  ADD CONSTRAINT `fk program` FOREIGN KEY (`Program_Num`) REFERENCES `programs` (`Program_Num`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk uin track` FOREIGN KEY (`UIN`) REFERENCES `college_student` (`UIN`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
