-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 08, 2022 at 01:05 AM
-- Server version: 5.7.24
-- PHP Version: 8.0.1

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
-- Stand-in structure for view `admin_appointments`
-- (See below for the actual view)
--
CREATE TABLE `admin_appointments` (
`APPOINTMENT_ID` int(50)
,`STUDENT_ID` int(50)
,`TUTOR_ID` int(50)
,`SUBJECT_ID` int(50)
,`AVAILABILITY_ID` int(11)
,`LOCATION` varchar(50)
,`DAY` varchar(15)
,`START_TIME` time
,`END_TIME` time
,`TUTOR` varchar(101)
,`STUDENT` varchar(101)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `all_appointments`
-- (See below for the actual view)
--
CREATE TABLE `all_appointments` (
`STUDENT_ID` int(50)
,`LOCATION` varchar(50)
,`DAY` varchar(15)
,`START_TIME` time
,`END_TIME` time
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `all_reviews`
-- (See below for the actual view)
--
CREATE TABLE `all_reviews` (
`REVIEW_ID` int(50)
,`STUDENT_ID` int(50)
,`TUTOR_ID` int(50)
,`TUTOR` varchar(50)
,`NAME` varchar(101)
,`COMMENT` varchar(100)
,`STARS` int(1)
,`TAGS` text
,`DATE` date
);

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
(1, 1, 2, 1, 2, 'Evans Library');

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
(1, 'Friday', 2, '12:37:00', '01:44:00'),
(2, 'Wednesday', 100, '11:00:00', '13:00:00'),
(3, 'Saturday', 2, '05:00:00', '09:00:00'),
(4, 'Friday', 100, '06:00:00', '07:00:00'),
(5, 'Friday', 100, '06:00:00', '08:00:00'),
(7, 'Tuesday', 100, '08:00:00', '10:00:00'),
(19, 'Monday', 100, '23:00:00', '23:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `CLASS_ID` int(11) NOT NULL,
  `CLASS_CODE` varchar(50) NOT NULL,
  `CLASS_NUMBER` int(50) NOT NULL,
  `NAME` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`CLASS_ID`, `CLASS_CODE`, `CLASS_NUMBER`, `NAME`) VALUES
(1, 'CSCE', 310, 'Database Systems'),
(2, 'CSCE', 436, 'HCI'),
(3, 'CSCE', 436, 'HCI'),
(4, 'CSCE', 436, 'HCI'),
(5, 'CSCE', 436, 'HCI');

-- --------------------------------------------------------

--
-- Table structure for table `class_bridge`
--

CREATE TABLE `class_bridge` (
  `CLASSES_BRIDGE_ID` int(11) NOT NULL,
  `TUTOR_ID` int(50) NOT NULL,
  `CLASS_ID` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `class_bridge`
--

INSERT INTO `class_bridge` (`CLASSES_BRIDGE_ID`, `TUTOR_ID`, `CLASS_ID`) VALUES
(1, 2, 1),
(7, 100, 2);

-- --------------------------------------------------------

--
-- Stand-in structure for view `five_star_tutors`
-- (See below for the actual view)
--
CREATE TABLE `five_star_tutors` (
`F_NAME` varchar(50)
,`L_NAME` varchar(50)
,`AVG_RATING` float
);

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `REVIEW_ID` int(50) NOT NULL,
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
(1, 'Tom did the job. Mid af.', 5, 2, 1, '2022-11-01'),
(2, 'REVIEW bad', 1, 2, 1, '2022-11-09'),
(22, 'Test', 3, 100, 1, '2022-12-07'),
(23, 'Test', 3, 100, 1, '2022-12-07'),
(24, 'Test Review', 3, 100, 1, '2022-12-07'),
(28, 'Test', 2, 100, 1, '2022-12-07'),
(31, 'ii', 3, 100, 1, '2022-12-07'),
(32, 'ii', 3, 100, 1, '2022-12-07'),
(34, 'Test', 3, 100, 1, '2022-12-07'),
(35, 'Test', 3, 100, 1, '2022-12-07'),
(36, 'Test', 3, 100, 1, '2022-12-07'),
(41, 'Billy was shitty', 3, 100, 1, '2022-12-07');

--
-- Triggers `review`
--
DELIMITER $$
CREATE TRIGGER `add_basic_tag` AFTER INSERT ON `review` FOR EACH ROW BEGIN
	IF new.STARS > 3 THEN
        INSERT INTO `tag_bridge`(
            `REVIEW_ID`,
            `TAG_ID`
        )
        VALUES(new.REVIEW_ID, 111);
    END IF;
    IF new.STARS < 4 THEN
        INSERT INTO `tag_bridge`(
            `REVIEW_ID`,
            `TAG_ID`
        )
        VALUES(new.REVIEW_ID, 112);
    END IF;
END
$$
DELIMITER ;
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
  `SUBJECT_ID` int(11) NOT NULL,
  `NAME` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`SUBJECT_ID`, `NAME`) VALUES
(1, 'Statistics'),
(2, 'Math'),
(3, 'Math'),
(4, 'Math'),
(5, 'Math'),
(6, 'Math'),
(7, 'Math'),
(8, 'Math'),
(9, 'Math'),
(10, 'Math'),
(11, 'Science'),
(12, 'Science'),
(13, 'Science'),
(14, 'Science'),
(15, 'Science'),
(16, 'Science'),
(17, 'Science'),
(18, 'Science'),
(25, 'Astronomy'),
(26, 'Astronomy'),
(27, 'CSCE');

-- --------------------------------------------------------

--
-- Table structure for table `subject_bridge`
--

CREATE TABLE `subject_bridge` (
  `SUBJECT_BRIDGE_ID` int(11) NOT NULL,
  `TUTOR_ID` int(50) NOT NULL,
  `SUBJECT_ID` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `subject_bridge`
--

INSERT INTO `subject_bridge` (`SUBJECT_BRIDGE_ID`, `TUTOR_ID`, `SUBJECT_ID`) VALUES
(1, 100, 1),
(11, 100, 27);

-- --------------------------------------------------------

--
-- Table structure for table `tag`
--

CREATE TABLE `tag` (
  `TAG_ID` int(50) NOT NULL,
  `NAME` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tag`
--

INSERT INTO `tag` (`TAG_ID`, `NAME`) VALUES
(100, 'Would Recommend'),
(101, 'Would Not Recommend'),
(102, 'Auditory Teacher'),
(103, 'Visual Teacher'),
(104, 'Great'),
(105, 'Poor Listener'),
(106, 'Great Listener'),
(107, 'Patient'),
(108, 'Poor Communicator'),
(109, 'Stubborn'),
(110, 'Rude'),
(111, 'Not Very Helpful'),
(112, 'Very Helpful'),
(113, 'Terrible'),
(114, 'Amazing'),
(115, 'Caring');

-- --------------------------------------------------------

--
-- Table structure for table `tag_bridge`
--

CREATE TABLE `tag_bridge` (
  `TAG_BRIDGE_ID` int(50) NOT NULL,
  `REVIEW_ID` int(50) NOT NULL,
  `TAG_ID` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tag_bridge`
--

INSERT INTO `tag_bridge` (`TAG_BRIDGE_ID`, `REVIEW_ID`, `TAG_ID`) VALUES
(3, 2, 109),
(11, 28, 112),
(26, 41, 100),
(27, 41, 101),
(28, 41, 102);

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
(2, 2.8),
(100, 2.91667);

-- --------------------------------------------------------

--
-- Stand-in structure for view `tutor_appointments`
-- (See below for the actual view)
--
CREATE TABLE `tutor_appointments` (
`APPOINTMENT_ID` int(50)
,`TUTOR_ID` int(50)
,`LOCATION` varchar(50)
,`AVAILABILITY_ID` int(11)
,`DAY` varchar(15)
,`START_TIME` time
,`END_TIME` time
);

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
-- Stand-in structure for view `tutor_classes`
-- (See below for the actual view)
--
CREATE TABLE `tutor_classes` (
`CLASS_ID` int(11)
,`CLASS_CODE` varchar(50)
,`CLASS_NUMBER` int(50)
,`NAME` varchar(50)
,`TUTOR_ID` int(50)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `tutor_subjects`
-- (See below for the actual view)
--
CREATE TABLE `tutor_subjects` (
`TUTOR_ID` int(50)
,`SUBJECT_ID` int(50)
,`NAME` varchar(50)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `tutor_subjects_search`
-- (See below for the actual view)
--
CREATE TABLE `tutor_subjects_search` (
`F_NAME` varchar(50)
,`L_NAME` varchar(50)
,`EMAIL` varchar(50)
,`NAME` varchar(50)
,`TUTOR_ID` int(50)
,`SUBJECT_ID` int(50)
,`AVG_RATING` float
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
(100, 'wsmith', 'new', 'Wyatt', 'Smith', '123', 'wyattsmith@tamu.edu', 1, 1, 1);

--
-- Triggers `user`
--
DELIMITER $$
CREATE TRIGGER `user_type` AFTER INSERT ON `user` FOR EACH ROW BEGIN
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
-- Structure for view `admin_appointments`
--
DROP TABLE IF EXISTS `admin_appointments`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `admin_appointments`  AS SELECT `appointment`.`APPOINTMENT_ID` AS `APPOINTMENT_ID`, `appointment`.`STUDENT_ID` AS `STUDENT_ID`, `appointment`.`TUTOR_ID` AS `TUTOR_ID`, `appointment`.`SUBJECT_ID` AS `SUBJECT_ID`, `appointment`.`AVAILABILITY_ID` AS `AVAILABILITY_ID`, `appointment`.`LOCATION` AS `LOCATION`, `availability`.`DAY` AS `DAY`, `availability`.`START_TIME` AS `START_TIME`, `availability`.`END_TIME` AS `END_TIME`, concat(`user`.`F_NAME`,' ',`user`.`L_NAME`) AS `TUTOR`, `student_query`.`STUDENT` AS `STUDENT` FROM (((`appointment` join `user` on((`appointment`.`TUTOR_ID` = `user`.`USER_ID`))) join `availability` on((`appointment`.`AVAILABILITY_ID` = `availability`.`AVAILABILITY_ID`))) join (select concat(`user`.`F_NAME`,' ',`user`.`L_NAME`) AS `STUDENT`,`user`.`USER_ID` AS `USER_ID` from `user`) `student_query` on((`appointment`.`STUDENT_ID` = `student_query`.`USER_ID`)))  ;

-- --------------------------------------------------------

--
-- Structure for view `all_appointments`
--
DROP TABLE IF EXISTS `all_appointments`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `all_appointments`  AS SELECT `appointment`.`STUDENT_ID` AS `STUDENT_ID`, `appointment`.`LOCATION` AS `LOCATION`, `availability`.`DAY` AS `DAY`, `availability`.`START_TIME` AS `START_TIME`, `availability`.`END_TIME` AS `END_TIME` FROM (`appointment` join `availability` on((`appointment`.`AVAILABILITY_ID` = `availability`.`AVAILABILITY_ID`)))  ;

-- --------------------------------------------------------

--
-- Structure for view `all_reviews`
--
DROP TABLE IF EXISTS `all_reviews`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `all_reviews`  AS SELECT `review`.`REVIEW_ID` AS `REVIEW_ID`, `review`.`STUDENT_ID` AS `STUDENT_ID`, `review`.`TUTOR_ID` AS `TUTOR_ID`, `user`.`USERNAME` AS `TUTOR`, concat(`user`.`F_NAME`,' ',`user`.`L_NAME`) AS `NAME`, `review`.`COMMENT` AS `COMMENT`, `review`.`STARS` AS `STARS`, group_concat(distinct `tag`.`NAME` separator ', ') AS `TAGS`, `review`.`DATE` AS `DATE` FROM (((`review` join `tag_bridge` on((`review`.`REVIEW_ID` = `tag_bridge`.`REVIEW_ID`))) join `tag` on((`tag_bridge`.`TAG_ID` = `tag`.`TAG_ID`))) join `user` on((`review`.`TUTOR_ID` = `user`.`USER_ID`))) GROUP BY `review`.`REVIEW_ID` ORDER BY `review`.`DATE` ASC  ;

-- --------------------------------------------------------

--
-- Structure for view `five_star_tutors`
--
DROP TABLE IF EXISTS `five_star_tutors`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `five_star_tutors`  AS SELECT `user`.`F_NAME` AS `F_NAME`, `user`.`L_NAME` AS `L_NAME`, `tutor`.`AVG_RATING` AS `AVG_RATING` FROM (`tutor` join `user` on((`tutor`.`USER_ID` = `user`.`USER_ID`))) WHERE (`tutor`.`AVG_RATING` > 4.5)  ;

-- --------------------------------------------------------

--
-- Structure for view `tutor_appointments`
--
DROP TABLE IF EXISTS `tutor_appointments`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `tutor_appointments`  AS SELECT `appointment`.`APPOINTMENT_ID` AS `APPOINTMENT_ID`, `appointment`.`TUTOR_ID` AS `TUTOR_ID`, `appointment`.`LOCATION` AS `LOCATION`, `availability`.`AVAILABILITY_ID` AS `AVAILABILITY_ID`, `availability`.`DAY` AS `DAY`, `availability`.`START_TIME` AS `START_TIME`, `availability`.`END_TIME` AS `END_TIME` FROM (`appointment` join `availability` on((`appointment`.`AVAILABILITY_ID` = `availability`.`AVAILABILITY_ID`)))  ;

-- --------------------------------------------------------

--
-- Structure for view `tutor_availability`
--
DROP TABLE IF EXISTS `tutor_availability`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `tutor_availability`  AS SELECT `user`.`USERNAME` AS `USERNAME`, `availability`.`DAY` AS `DAY`, `availability`.`START_TIME` AS `START_TIME`, `availability`.`END_TIME` AS `END_TIME` FROM (`user` join `availability`) WHERE ((`availability`.`DAY` = 'Saturday') AND (`availability`.`TUTOR_ID` = `user`.`USER_ID`) AND `user`.`IS_TUTOR`)  ;

-- --------------------------------------------------------

--
-- Structure for view `tutor_classes`
--
DROP TABLE IF EXISTS `tutor_classes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `tutor_classes`  AS SELECT `class`.`CLASS_ID` AS `CLASS_ID`, `class`.`CLASS_CODE` AS `CLASS_CODE`, `class`.`CLASS_NUMBER` AS `CLASS_NUMBER`, `class`.`NAME` AS `NAME`, `class_bridge`.`TUTOR_ID` AS `TUTOR_ID` FROM (`class_bridge` join `class` on((`class`.`CLASS_ID` = `class_bridge`.`CLASS_ID`)))  ;

-- --------------------------------------------------------

--
-- Structure for view `tutor_subjects`
--
DROP TABLE IF EXISTS `tutor_subjects`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `tutor_subjects`  AS SELECT `subject_bridge`.`TUTOR_ID` AS `TUTOR_ID`, `subject_bridge`.`SUBJECT_ID` AS `SUBJECT_ID`, `subject`.`NAME` AS `NAME` FROM (`subject_bridge` join `subject` on((`subject_bridge`.`SUBJECT_ID` = `subject`.`SUBJECT_ID`)))  ;

-- --------------------------------------------------------

--
-- Structure for view `tutor_subjects_search`
--
DROP TABLE IF EXISTS `tutor_subjects_search`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `tutor_subjects_search`  AS SELECT `user`.`F_NAME` AS `F_NAME`, `user`.`L_NAME` AS `L_NAME`, `user`.`EMAIL` AS `EMAIL`, `subject`.`NAME` AS `NAME`, `subject_bridge`.`TUTOR_ID` AS `TUTOR_ID`, `subject_bridge`.`SUBJECT_ID` AS `SUBJECT_ID`, `tutor`.`AVG_RATING` AS `AVG_RATING` FROM (((`subject_bridge` join `user` on((`user`.`USER_ID` = `subject_bridge`.`TUTOR_ID`))) join `tutor` on((`tutor`.`USER_ID` = `subject_bridge`.`TUTOR_ID`))) join `subject` on((`subject`.`SUBJECT_ID` = `subject_bridge`.`SUBJECT_ID`)))  ;

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
  ADD KEY `fk_tutor_id2` (`TUTOR_ID`);

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
  ADD KEY `fk_tutor_id3` (`TUTOR_ID`),
  ADD KEY `fk_class_id1` (`CLASS_ID`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`REVIEW_ID`),
  ADD KEY `fk_tutor_id4` (`TUTOR_ID`),
  ADD KEY `fk_student_id2` (`STUDENT_ID`),
  ADD KEY `review_rating` (`TUTOR_ID`,`STARS`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`USER_ID`),
  ADD KEY `CLASS_YEAR` (`CLASS_YEAR`);

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
  ADD KEY `fk_tutor_id` (`TUTOR_ID`),
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
  ADD KEY `fk_review_id1` (`REVIEW_ID`),
  ADD KEY `fk_tag_id1` (`TAG_ID`);

--
-- Indexes for table `tutor`
--
ALTER TABLE `tutor`
  ADD PRIMARY KEY (`USER_ID`),
  ADD KEY `tutors_by_rating` (`USER_ID`,`AVG_RATING`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`USER_ID`),
  ADD KEY `user_phonenumbers` (`USERNAME`,`PHONE`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `APPOINTMENT_ID` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000;

--
-- AUTO_INCREMENT for table `availability`
--
ALTER TABLE `availability`
  MODIFY `AVAILABILITY_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `class`
--
ALTER TABLE `class`
  MODIFY `CLASS_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `class_bridge`
--
ALTER TABLE `class_bridge`
  MODIFY `CLASSES_BRIDGE_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `REVIEW_ID` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `subject`
--
ALTER TABLE `subject`
  MODIFY `SUBJECT_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `subject_bridge`
--
ALTER TABLE `subject_bridge`
  MODIFY `SUBJECT_BRIDGE_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tag`
--
ALTER TABLE `tag`
  MODIFY `TAG_ID` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT for table `tag_bridge`
--
ALTER TABLE `tag_bridge`
  MODIFY `TAG_BRIDGE_ID` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `USER_ID` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `fk_availability_id` FOREIGN KEY (`AVAILABILITY_ID`) REFERENCES `availability` (`AVAILABILITY_ID`),
  ADD CONSTRAINT `fk_student_id1` FOREIGN KEY (`STUDENT_ID`) REFERENCES `student` (`USER_ID`),
  ADD CONSTRAINT `fk_subject_id1` FOREIGN KEY (`SUBJECT_ID`) REFERENCES `subject` (`SUBJECT_ID`),
  ADD CONSTRAINT `fk_tutor_id1` FOREIGN KEY (`TUTOR_ID`) REFERENCES `tutor` (`USER_ID`);

--
-- Constraints for table `availability`
--
ALTER TABLE `availability`
  ADD CONSTRAINT `fk_tutor_id2` FOREIGN KEY (`TUTOR_ID`) REFERENCES `tutor` (`USER_ID`);

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `fk_student_id2` FOREIGN KEY (`STUDENT_ID`) REFERENCES `student` (`USER_ID`),
  ADD CONSTRAINT `fk_tutor_id4` FOREIGN KEY (`TUTOR_ID`) REFERENCES `tutor` (`USER_ID`);

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `fk_user_id3` FOREIGN KEY (`USER_ID`) REFERENCES `user` (`USER_ID`);

--
-- Constraints for table `subject_bridge`
--
ALTER TABLE `subject_bridge`
  ADD CONSTRAINT `fk_subject_id2` FOREIGN KEY (`SUBJECT_ID`) REFERENCES `subject` (`SUBJECT_ID`),
  ADD CONSTRAINT `fk_user_id1` FOREIGN KEY (`TUTOR_ID`) REFERENCES `tutor` (`USER_ID`);

--
-- Constraints for table `tag_bridge`
--
ALTER TABLE `tag_bridge`
  ADD CONSTRAINT `fk_review_id1` FOREIGN KEY (`REVIEW_ID`) REFERENCES `review` (`REVIEW_ID`),
  ADD CONSTRAINT `fk_tag_id1` FOREIGN KEY (`TAG_ID`) REFERENCES `tag` (`TAG_ID`);

--
-- Constraints for table `tutor`
--
ALTER TABLE `tutor`
  ADD CONSTRAINT `fk_user_id2` FOREIGN KEY (`USER_ID`) REFERENCES `user` (`USER_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
