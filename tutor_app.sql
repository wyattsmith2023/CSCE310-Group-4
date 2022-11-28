-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Nov 28, 2022 at 11:04 PM
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
-- Database: `tutor_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `APPOINTMENT_ID` int(50) NOT NULL,
  `STUDENT_ID` int(50) NOT NULL,
  `TUTOR_ID` int(50) NOT NULL,
  `SUBJECT_ID` int(50) NOT NULL,
  `AVAILABILITY_ID` int(11) DEFAULT NULL,
  `LOCATION` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`APPOINTMENT_ID`, `STUDENT_ID`, `TUTOR_ID`, `SUBJECT_ID`, `AVAILABILITY_ID`, `LOCATION`) VALUES
(1, 1, 2, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `availability`
--

CREATE TABLE `availability` (
  `AVAILABILITY_ID` int(11) NOT NULL,
  `DAY` varchar(15) DEFAULT NULL,
  `TUTOR_ID` int(11) DEFAULT NULL,
  `START_TIME` time DEFAULT NULL,
  `END_TIME` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `availability`
--

INSERT INTO `availability` (`AVAILABILITY_ID`, `DAY`, `TUTOR_ID`, `START_TIME`, `END_TIME`) VALUES
(1, 'Tuesday', 2, '06:45:00', '07:45:00'),
(2, 'Wednesday', 100, '07:00:00', '08:00:00'),
(3, 'Saturday', 2, '05:00:00', '09:00:00'),
(4, 'Saturday', 100, '06:00:00', '07:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `CLASS_ID` int(50) NOT NULL,
  `CLASS_CODE` varchar(50) NOT NULL,
  `CLASS_NUMBER` int(50) NOT NULL,
  `NAME` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`CLASS_ID`, `CLASS_CODE`, `CLASS_NUMBER`, `NAME`) VALUES
(1, 'CSCE', 310, 'Database Systems');

-- --------------------------------------------------------

--
-- Table structure for table `class_bridge`
--

CREATE TABLE `class_bridge` (
  `CLASSES_BRIDGE_ID` int(50) NOT NULL,
  `TUTOR_ID` int(50) NOT NULL,
  `CLASS_ID` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `class_bridge`
--

INSERT INTO `class_bridge` (`CLASSES_BRIDGE_ID`, `TUTOR_ID`, `CLASS_ID`) VALUES
(1, 2, 1);

-- --------------------------------------------------------

--
-- Stand-in structure for view `five star tutors`
-- (See below for the actual view)
--
CREATE TABLE `five star tutors` (
`F_NAME` varchar(50)
,`L_NAME` varchar(50)
,`AVG_RATING` float
);

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `REVIEW_ID` int(11) NOT NULL,
  `COMMENT` varchar(100) NOT NULL,
  `STARS` int(1) NOT NULL,
  `TUTOR_ID` int(50) NOT NULL,
  `STUDENT_ID` int(50) NOT NULL,
  `DATE` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`REVIEW_ID`, `COMMENT`, `STARS`, `TUTOR_ID`, `STUDENT_ID`, `DATE`) VALUES
(1, 'Travis did the job. Mid af.', 5, 2, 1, '2022-11-01'),
(2, 'REVIEW bad', 1, 2, 1, '2022-11-09');

--
-- Triggers `review`
--
DELIMITER $$
CREATE TRIGGER `update_rating` AFTER INSERT ON `review` FOR EACH ROW UPDATE TUTOR
SET AVG_RATING = 
(SELECT AVG(STARS)
FROM review
WHERE TUTOR_ID = new.tutor_id)
WHERE USER_ID = new.tutor_id
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `USER_ID` int(50) NOT NULL,
  `GPA` float NOT NULL,
  `CLASS_YEAR` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`USER_ID`, `GPA`, `CLASS_YEAR`) VALUES
(1, 4, 1),
(100, 0, 1876);

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `SUBJECT_ID` int(50) NOT NULL,
  `NAME` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`SUBJECT_ID`, `NAME`) VALUES
(1, 'Statistics');

-- --------------------------------------------------------

--
-- Table structure for table `subject_bridge`
--

CREATE TABLE `subject_bridge` (
  `SUBJECT_BRIDGE_ID` int(50) NOT NULL,
  `USER_ID` int(50) NOT NULL,
  `SUBJECT_ID` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `subject_bridge`
--

INSERT INTO `subject_bridge` (`SUBJECT_BRIDGE_ID`, `USER_ID`, `SUBJECT_ID`) VALUES
(1, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tag`
--

CREATE TABLE `tag` (
  `TAG_ID` int(50) NOT NULL,
  `NAME` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tag_bridge`
--

CREATE TABLE `tag_bridge` (
  `TAG_BRIDGE_ID` int(50) NOT NULL,
  `REVIEW_ID` int(50) NOT NULL,
  `TAG_ID` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tutor`
--

CREATE TABLE `tutor` (
  `USER_ID` int(50) NOT NULL,
  `AVG_RATING` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tutor`
--

INSERT INTO `tutor` (`USER_ID`, `AVG_RATING`) VALUES
(2, 3),
(100, 0);

-- --------------------------------------------------------

--
-- Stand-in structure for view `tutor_availability`
-- (See below for the actual view)
--
CREATE TABLE `tutor_availability` (
`USERNAME` varchar(50)
,`DAY` varchar(15)
,`START_TIME` time
,`END_TIME` time
);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
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
-- Dumping data for table `user`
--

INSERT INTO `user` (`USER_ID`, `USERNAME`, `PASSWORD`, `F_NAME`, `L_NAME`, `PHONE`, `EMAIL`, `IS_STUDENT`, `IS_TUTOR`, `IS_ADMIN`) VALUES
(1, 'landonjpalmer', 'password', 'Landon', 'Palmer', '911-555-5555', 'landonjpalmer@tamu.edu', 1, 0, 0),
(2, 'tomhanks', 'password', 'Tom', 'Hanks', '555-555-5555', 'tomhanks@hanks.com', 0, 1, 0),
(100, 'usernamebitch', 'passwordhoney', 'Wyatt', 'Smith', '12345', 'email@amam', 1, 1, 1);

--
-- Triggers `user`
--
DELIMITER $$
CREATE TRIGGER `user_type` BEFORE INSERT ON `user` FOR EACH ROW BEGIN
        IF new.is_student = 1 THEN
    		INSERT INTO student (USER_ID, GPA, CLASS_YEAR)
            VALUES (new.user_id, 0, 1876);
    	END IF; 
        IF new.is_tutor = 1 THEN
			INSERT INTO tutor(USER_ID, AVG_RATING)
			VALUES(new.user_id, 0);
		END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure for view `five star tutors`
--
DROP TABLE IF EXISTS `five star tutors`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `five star tutors`  AS SELECT `user`.`F_NAME` AS `F_NAME`, `user`.`L_NAME` AS `L_NAME`, `tutor`.`AVG_RATING` AS `AVG_RATING` FROM (`tutor` join `user` on((`tutor`.`USER_ID` = `user`.`USER_ID`))) WHERE (`tutor`.`AVG_RATING` > 4.5) ;

-- --------------------------------------------------------

--
-- Structure for view `tutor_availability`
--
DROP TABLE IF EXISTS `tutor_availability`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `tutor_availability`  AS SELECT `user`.`USERNAME` AS `USERNAME`, `availability`.`DAY` AS `DAY`, `availability`.`START_TIME` AS `START_TIME`, `availability`.`END_TIME` AS `END_TIME` FROM (`user` join `availability`) WHERE ((`availability`.`DAY` = 'Saturday') AND (`availability`.`TUTOR_ID` = `user`.`USER_ID`) AND `user`.`IS_TUTOR`) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`APPOINTMENT_ID`),
  ADD KEY `fk_student_id1` (`STUDENT_ID`) USING BTREE,
  ADD KEY `fk_subject_id1` (`SUBJECT_ID`) USING BTREE,
  ADD KEY `fk_tutor_id1` (`TUTOR_ID`) USING BTREE,
  ADD KEY `fk_availability_id1` (`AVAILABILITY_ID`);

--
-- Indexes for table `availability`
--
ALTER TABLE `availability`
  ADD PRIMARY KEY (`AVAILABILITY_ID`),
  ADD KEY `fk_tutor_id5` (`TUTOR_ID`);

--
-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`CLASS_ID`);

--
-- Indexes for table `class_bridge`
--
ALTER TABLE `class_bridge`
  ADD PRIMARY KEY (`CLASSES_BRIDGE_ID`),
  ADD KEY `fk_tutor_id2` (`TUTOR_ID`),
  ADD KEY `fk_class_id1` (`CLASS_ID`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`REVIEW_ID`),
  ADD KEY `fk_tutor_id3` (`TUTOR_ID`),
  ADD KEY `fk_student_id3` (`STUDENT_ID`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`USER_ID`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`SUBJECT_ID`);

--
-- Indexes for table `subject_bridge`
--
ALTER TABLE `subject_bridge`
  ADD PRIMARY KEY (`SUBJECT_BRIDGE_ID`),
  ADD KEY `fk_user_id1` (`USER_ID`),
  ADD KEY `fk_subject_id2` (`SUBJECT_ID`);

--
-- Indexes for table `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`TAG_ID`);

--
-- Indexes for table `tag_bridge`
--
ALTER TABLE `tag_bridge`
  ADD PRIMARY KEY (`TAG_BRIDGE_ID`),
  ADD KEY `fk_review_id2` (`REVIEW_ID`),
  ADD KEY `fk_tag_id1` (`TAG_ID`);

--
-- Indexes for table `tutor`
--
ALTER TABLE `tutor`
  ADD PRIMARY KEY (`USER_ID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`USER_ID`),
  ADD KEY `user_phonenumbers` (`USERNAME`,`PHONE`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `fk_availability_id1` FOREIGN KEY (`AVAILABILITY_ID`) REFERENCES `availability` (`AVAILABILITY_ID`),
  ADD CONSTRAINT `fk_student_id` FOREIGN KEY (`STUDENT_ID`) REFERENCES `student` (`USER_ID`),
  ADD CONSTRAINT `fk_subject_id` FOREIGN KEY (`SUBJECT_ID`) REFERENCES `subject` (`SUBJECT_ID`),
  ADD CONSTRAINT `fk_tutor_id` FOREIGN KEY (`TUTOR_ID`) REFERENCES `tutor` (`USER_ID`);

--
-- Constraints for table `availability`
--
ALTER TABLE `availability`
  ADD CONSTRAINT `fk_tutor_id5` FOREIGN KEY (`TUTOR_ID`) REFERENCES `tutor` (`USER_ID`);

--
-- Constraints for table `class_bridge`
--
ALTER TABLE `class_bridge`
  ADD CONSTRAINT `fk_class_id1` FOREIGN KEY (`CLASS_ID`) REFERENCES `class` (`CLASS_ID`),
  ADD CONSTRAINT `fk_tutor_id2` FOREIGN KEY (`TUTOR_ID`) REFERENCES `tutor` (`USER_ID`);

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `fk_student_id3` FOREIGN KEY (`STUDENT_ID`) REFERENCES `student` (`USER_ID`),
  ADD CONSTRAINT `fk_tutor_id3` FOREIGN KEY (`TUTOR_ID`) REFERENCES `tutor` (`USER_ID`);

--
-- Constraints for table `subject_bridge`
--
ALTER TABLE `subject_bridge`
  ADD CONSTRAINT `fk_subject_id2` FOREIGN KEY (`SUBJECT_ID`) REFERENCES `subject` (`SUBJECT_ID`),
  ADD CONSTRAINT `fk_user_id1` FOREIGN KEY (`USER_ID`) REFERENCES `user` (`USER_ID`);

--
-- Constraints for table `tag_bridge`
--
ALTER TABLE `tag_bridge`
  ADD CONSTRAINT `fk_review_id2` FOREIGN KEY (`REVIEW_ID`) REFERENCES `review` (`REVIEW_ID`),
  ADD CONSTRAINT `fk_tag_id1` FOREIGN KEY (`TAG_ID`) REFERENCES `tag` (`TAG_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
