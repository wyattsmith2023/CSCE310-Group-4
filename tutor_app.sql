-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Nov 15, 2022 at 02:27 AM
-- Server version: 5.7.34
-- PHP Version: 7.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Tutoring`
--

-- --------------------------------------------------------

--
-- Table structure for table `APPOINTMENT`
--

CREATE TABLE `APPOINTMENT` (
  `APPOINTMENT_ID` int(50) NOT NULL,
  `LOCATION` varchar(50) NOT NULL,
  `TIME` time(6) NOT NULL,
  `DATE` date NOT NULL,
  `STUDENT_ID` int(50) NOT NULL,
  `TUTOR_ID` int(50) NOT NULL,
  `SUBJECT_ID` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `APPOINTMENT`
--

INSERT INTO `APPOINTMENT` (`APPOINTMENT_ID`, `LOCATION`, `TIME`, `DATE`, `STUDENT_ID`, `TUTOR_ID`, `SUBJECT_ID`) VALUES
(1, 'The Taco Bell dumpster on Northgate', '07:30:00.000000', '2022-11-04', 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `CLASS`
--

CREATE TABLE `CLASS` (
  `CLASS_ID` int(50) NOT NULL,
  `CLASS_CODE` varchar(50) NOT NULL,
  `CLASS_NUMBER` int(50) NOT NULL,
  `NAME` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `CLASS`
--

INSERT INTO `CLASS` (`CLASS_ID`, `CLASS_CODE`, `CLASS_NUMBER`, `NAME`) VALUES
(1, 'CSCE', 310, 'Database Systems');

-- --------------------------------------------------------

--
-- Table structure for table `CLASS_BRIDGE`
--

CREATE TABLE `CLASS_BRIDGE` (
  `CLASSES_BRIDGE_ID` int(50) NOT NULL,
  `TUTOR_ID` int(50) NOT NULL,
  `CLASS_ID` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `CLASS_BRIDGE`
--

INSERT INTO `CLASS_BRIDGE` (`CLASSES_BRIDGE_ID`, `TUTOR_ID`, `CLASS_ID`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `REVIEW`
--

CREATE TABLE `REVIEW` (
  `REVIEW_ID` int(11) NOT NULL,
  `COMMENT` varchar(100) NOT NULL,
  `STARS` int(1) NOT NULL,
  `TUTOR_ID` int(50) NOT NULL,
  `STUDENT_ID` int(50) NOT NULL,
  `DATE` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `REVIEW`
--

INSERT INTO `REVIEW` (`REVIEW_ID`, `COMMENT`, `STARS`, `TUTOR_ID`, `STUDENT_ID`, `DATE`) VALUES
(1, 'Travis did the job. Mid af.', 5, 1, 1, '2022-11-01'),
(123, 'Terrible', 0, 1, 123, '2022-11-11');

--
-- Triggers `REVIEW`
--
DELIMITER $$
CREATE TRIGGER `update_rating` AFTER INSERT ON `REVIEW` FOR EACH ROW UPDATE TUTOR
SET AVG_RATING = 
(SELECT AVG(STARS)
FROM REVIEW
WHERE TUTOR_ID = NEW.tutor_id)
WHERE USER_ID = new.tutor_id
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `STUDENT`
--

CREATE TABLE `STUDENT` (
  `USER_ID` int(50) NOT NULL,
  `GPA` float NOT NULL,
  `CLASS_YEAR` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `STUDENT`
--

INSERT INTO `STUDENT` (`USER_ID`, `GPA`, `CLASS_YEAR`) VALUES
(1, 0, 0),
(2, 4, 1),
(123, 0, 1876),
(123456, 0, 1876);

-- --------------------------------------------------------

--
-- Table structure for table `SUBJECT`
--

CREATE TABLE `SUBJECT` (
  `SUBJECT_ID` int(50) NOT NULL,
  `NAME` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `SUBJECT_BRIDGE`
--

CREATE TABLE `SUBJECT_BRIDGE` (
  `SUBJECT_BRIDGE_ID` int(50) NOT NULL,
  `USER_ID` int(50) NOT NULL,
  `SUBJECT_ID` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `TAG`
--

CREATE TABLE `TAG` (
  `TAG_ID` int(50) NOT NULL,
  `NAME` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `TAG_BRIDGE`
--

CREATE TABLE `TAG_BRIDGE` (
  `TAG_BRIDGE_ID` int(50) NOT NULL,
  `REVIEW_ID` int(50) NOT NULL,
  `TAG_ID` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `TUTOR`
--

CREATE TABLE `TUTOR` (
  `USER_ID` int(50) NOT NULL,
  `AVG_RATING` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `TUTOR`
--

INSERT INTO `TUTOR` (`USER_ID`, `AVG_RATING`) VALUES
(1, 2.5),
(123, 0);

-- --------------------------------------------------------

--
-- Table structure for table `USER`
--

CREATE TABLE `USER` (
  `USER_ID` int(50) NOT NULL,
  `USERNAME` varchar(50) NOT NULL,
  `PASSWORD` varchar(50) NOT NULL,
  `F_NAME` varchar(50) NOT NULL,
  `L_NAME` varchar(50) NOT NULL,
  `PHONE` varchar(50) NOT NULL,
  `EMAIL` varchar(50) NOT NULL,
  `IS_STUDENT` tinyint(1) NOT NULL,
  `IS_TUTOR` tinyint(1) NOT NULL,
  `IS_ADMIN` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `USER`
--

INSERT INTO `USER` (`USER_ID`, `USERNAME`, `PASSWORD`, `F_NAME`, `L_NAME`, `PHONE`, `EMAIL`, `IS_STUDENT`, `IS_TUTOR`, `IS_ADMIN`) VALUES
(1, 'landonjpalmer', 'password', 'Landon', 'Palmer', '911-555-5555', 'landonjpalmer@tamu.edu', 1, 0, 0),
(100, 'a', 'b', 'Chuck', 'd', 'e', 'f', 0, 0, 0),
(123, 'user', 'pass', 'f', 'l', '12345', 'email', 1, 1, 0),
(500, 'usernmae', 'pass', 'Bill', 'lname', '123', 'email@amam', 0, 0, 0),
(123456, 'WS', 'password', 'Bill', 'Smith', '123456789', 'email', 1, 0, 0);

--
-- Triggers `USER`
--
DELIMITER $$
CREATE TRIGGER `user_type` BEFORE INSERT ON `USER` FOR EACH ROW BEGIN
        IF NEW.is_student = 1 THEN
    		INSERT INTO STUDENT (USER_ID, GPA, CLASS_YEAR)
            VALUES (new.user_id, 0, 1876);
    	END IF; 
        IF NEW.is_tutor = 1 THEN
			INSERT INTO tutor(USER_ID, AVG_RATING)
			VALUES(NEW.user_id, 0);
		END IF;
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `APPOINTMENT`
--
ALTER TABLE `APPOINTMENT`
  ADD PRIMARY KEY (`APPOINTMENT_ID`),
  ADD KEY `student_FK` (`STUDENT_ID`);

--
-- Indexes for table `CLASS`
--
ALTER TABLE `CLASS`
  ADD PRIMARY KEY (`CLASS_ID`);

--
-- Indexes for table `CLASS_BRIDGE`
--
ALTER TABLE `CLASS_BRIDGE`
  ADD PRIMARY KEY (`CLASSES_BRIDGE_ID`),
  ADD KEY `tutor_FK` (`TUTOR_ID`),
  ADD KEY `class_FK` (`CLASS_ID`);

--
-- Indexes for table `REVIEW`
--
ALTER TABLE `REVIEW`
  ADD PRIMARY KEY (`REVIEW_ID`),
  ADD KEY `student_FK` (`STUDENT_ID`),
  ADD KEY `tutor_FK` (`TUTOR_ID`);

--
-- Indexes for table `STUDENT`
--
ALTER TABLE `STUDENT`
  ADD PRIMARY KEY (`USER_ID`),
  ADD KEY `USER_ID` (`USER_ID`);

--
-- Indexes for table `SUBJECT`
--
ALTER TABLE `SUBJECT`
  ADD PRIMARY KEY (`SUBJECT_ID`);

--
-- Indexes for table `SUBJECT_BRIDGE`
--
ALTER TABLE `SUBJECT_BRIDGE`
  ADD PRIMARY KEY (`SUBJECT_BRIDGE_ID`);

--
-- Indexes for table `TAG`
--
ALTER TABLE `TAG`
  ADD PRIMARY KEY (`TAG_ID`);

--
-- Indexes for table `TAG_BRIDGE`
--
ALTER TABLE `TAG_BRIDGE`
  ADD PRIMARY KEY (`TAG_BRIDGE_ID`);

--
-- Indexes for table `TUTOR`
--
ALTER TABLE `TUTOR`
  ADD PRIMARY KEY (`USER_ID`);

--
-- Indexes for table `USER`
--
ALTER TABLE `USER`
  ADD PRIMARY KEY (`USER_ID`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `APPOINTMENT`
--
ALTER TABLE `APPOINTMENT`
  ADD CONSTRAINT `student_FK1` FOREIGN KEY (`STUDENT_ID`) REFERENCES `STUDENT` (`USER_ID`);

--
-- Constraints for table `CLASS_BRIDGE`
--
ALTER TABLE `CLASS_BRIDGE`
  ADD CONSTRAINT `class_bridge_FK` FOREIGN KEY (`CLASS_ID`) REFERENCES `CLASS` (`CLASS_ID`),
  ADD CONSTRAINT `tutor_bridge_FK` FOREIGN KEY (`TUTOR_ID`) REFERENCES `TUTOR` (`USER_ID`);

--
-- Constraints for table `REVIEW`
--
ALTER TABLE `REVIEW`
  ADD CONSTRAINT `student_FK` FOREIGN KEY (`STUDENT_ID`) REFERENCES `student` (`USER_ID`),
  ADD CONSTRAINT `tutor_FK` FOREIGN KEY (`TUTOR_ID`) REFERENCES `TUTOR` (`USER_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
