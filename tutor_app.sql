-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Dec 08, 2022 at 10:54 AM
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
-- Stand-in structure for view `admin_appointments`
-- (See below for the actual view)
--
CREATE TABLE `admin_appointments` (
`APPOINTMENT_ID` int(50)
,`SUBJECT` varchar(50)
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
,`APPOINTMENT_ID` int(50)
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
(31, 'Monday', 104, '12:00:00', '15:00:00'),
(32, 'Tuesday', 104, '12:00:00', '15:00:00'),
(34, 'Wednesday', 104, '10:00:00', '16:00:00'),
(36, 'Saturday', 135, '22:00:00', '23:00:00');

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
(8, 'CSCE', 310, 'Database'),
(9, 'STAT', 211, 'Intro to Stats'),
(10, 'PHYS', 206, 'Mechanics'),
(11, 'ENGR', 312, 'Tech Management'),
(13, 'STAT', 211, 'Intro to Statistics');

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
(10, 135, 13);

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
(3, 'GREAT', 5, 104, 166, '2022-12-08'),
(5, 'I LOVE LANDO', 5, 135, 165, '2022-12-08');

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
        VALUES(new.REVIEW_ID, 112);
    END IF;
    IF new.STARS < 4 THEN
        INSERT INTO `tag_bridge`(
            `REVIEW_ID`,
            `TAG_ID`
        )
        VALUES(new.REVIEW_ID, 111);
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
(164, 0, 1876),
(165, 0, 1876),
(166, 0, 1876),
(167, 0, 1876);

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
(36, 'Statistics');

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
(15, 135, 36);

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
(115, 'Caring'),
(116, 'Understanding');

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
(3, 3, 112),
(6, 5, 112),
(7, 5, 100);

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
(104, 5),
(105, 0),
(106, 0),
(107, 0),
(126, 0),
(135, 5),
(164, 0);

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
(1, 'GOD', 'password', 'Jesus', 'Christ', '1', 'theson@heaven.com', 1, 1, 1),
(104, 'lukeskywalker', 'luke', 'Luke', 'Skywalker', '456', 'luke@rebellion.com', 0, 1, 1),
(105, 'leiaorgana', 'leia', 'Leia', 'Organa', '456', 'leia@rebellion.com', 0, 1, 1),
(106, 'hansolo', 'han', 'Han', 'Solo', '456', 'han@rebellion.com', 0, 1, 1),
(107, 'darthvader', 'darth', 'Darth', 'Vader', '456', 'vader@empire.com', 0, 1, 0),
(126, 'darthsidious', 'darth', 'Sheev', 'Palpatine', '123456', 'emperor@empire.com', 1, 1, 1),
(135, 'landocalrissian', 'lando', 'Lando', 'Calrissian', '56', 'lando@rebellion.com', 0, 1, 0),
(164, 'benkenobi', 'ben', 'Ben', 'Kenobi', '123456', 'ben@jedi.com', 1, 1, 1),
(165, 'macewindu', 'mace', 'Mace', 'Windu', '123', 'mace@jedi.com', 1, 0, 0),
(166, 'anakinskywalker', 'anakin', 'Anakin', 'Skywalker', '123', 'anakin@jedi.com', 1, 0, 0),
(167, 'padmeamidal', 'padme', 'Padme', 'Amidal', '123', 'padme@senate.com', 1, 0, 0);

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

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `admin_appointments`  AS SELECT `appointment`.`APPOINTMENT_ID` AS `APPOINTMENT_ID`, `subject`.`NAME` AS `SUBJECT`, `appointment`.`STUDENT_ID` AS `STUDENT_ID`, `appointment`.`TUTOR_ID` AS `TUTOR_ID`, `appointment`.`SUBJECT_ID` AS `SUBJECT_ID`, `appointment`.`AVAILABILITY_ID` AS `AVAILABILITY_ID`, `appointment`.`LOCATION` AS `LOCATION`, `availability`.`DAY` AS `DAY`, `availability`.`START_TIME` AS `START_TIME`, `availability`.`END_TIME` AS `END_TIME`, concat(`user`.`F_NAME`,' ',`user`.`L_NAME`) AS `TUTOR`, `student_query`.`STUDENT` AS `STUDENT` FROM ((((`appointment` join `user` on((`appointment`.`TUTOR_ID` = `user`.`USER_ID`))) join `subject` on((`appointment`.`SUBJECT_ID` = `subject`.`SUBJECT_ID`))) join `availability` on((`appointment`.`AVAILABILITY_ID` = `availability`.`AVAILABILITY_ID`))) join (select concat(`user`.`F_NAME`,' ',`user`.`L_NAME`) AS `STUDENT`,`user`.`USER_ID` AS `USER_ID` from `user`) `student_query` on((`appointment`.`STUDENT_ID` = `student_query`.`USER_ID`))) ;

-- --------------------------------------------------------

--
-- Structure for view `all_appointments`
--
DROP TABLE IF EXISTS `all_appointments`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `all_appointments`  AS SELECT `appointment`.`STUDENT_ID` AS `STUDENT_ID`, `appointment`.`APPOINTMENT_ID` AS `APPOINTMENT_ID`, `appointment`.`LOCATION` AS `LOCATION`, `availability`.`DAY` AS `DAY`, `availability`.`START_TIME` AS `START_TIME`, `availability`.`END_TIME` AS `END_TIME` FROM (`appointment` join `availability` on((`appointment`.`AVAILABILITY_ID` = `availability`.`AVAILABILITY_ID`))) ;

-- --------------------------------------------------------

--
-- Structure for view `all_reviews`
--
DROP TABLE IF EXISTS `all_reviews`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `all_reviews`  AS SELECT `review`.`REVIEW_ID` AS `REVIEW_ID`, `review`.`STUDENT_ID` AS `STUDENT_ID`, `review`.`TUTOR_ID` AS `TUTOR_ID`, `user`.`USERNAME` AS `TUTOR`, concat(`user`.`F_NAME`,' ',`user`.`L_NAME`) AS `NAME`, `review`.`COMMENT` AS `COMMENT`, `review`.`STARS` AS `STARS`, group_concat(distinct `tag`.`NAME` separator ', ') AS `TAGS`, `review`.`DATE` AS `DATE` FROM (((`review` join `tag_bridge` on((`review`.`REVIEW_ID` = `tag_bridge`.`REVIEW_ID`))) join `tag` on((`tag_bridge`.`TAG_ID` = `tag`.`TAG_ID`))) join `user` on((`review`.`TUTOR_ID` = `user`.`USER_ID`))) GROUP BY `review`.`REVIEW_ID` ORDER BY `review`.`DATE` ASC ;

-- --------------------------------------------------------

--
-- Structure for view `five_star_tutors`
--
DROP TABLE IF EXISTS `five_star_tutors`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `five_star_tutors`  AS SELECT `user`.`F_NAME` AS `F_NAME`, `user`.`L_NAME` AS `L_NAME`, `tutor`.`AVG_RATING` AS `AVG_RATING` FROM (`tutor` join `user` on((`tutor`.`USER_ID` = `user`.`USER_ID`))) WHERE (`tutor`.`AVG_RATING` > 4.5) ;

-- --------------------------------------------------------

--
-- Structure for view `tutor_appointments`
--
DROP TABLE IF EXISTS `tutor_appointments`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `tutor_appointments`  AS SELECT `appointment`.`APPOINTMENT_ID` AS `APPOINTMENT_ID`, `appointment`.`TUTOR_ID` AS `TUTOR_ID`, `appointment`.`LOCATION` AS `LOCATION`, `availability`.`AVAILABILITY_ID` AS `AVAILABILITY_ID`, `availability`.`DAY` AS `DAY`, `availability`.`START_TIME` AS `START_TIME`, `availability`.`END_TIME` AS `END_TIME` FROM (`appointment` join `availability` on((`appointment`.`AVAILABILITY_ID` = `availability`.`AVAILABILITY_ID`))) ;

-- --------------------------------------------------------

--
-- Structure for view `tutor_availability`
--
DROP TABLE IF EXISTS `tutor_availability`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `tutor_availability`  AS SELECT `user`.`USERNAME` AS `USERNAME`, `availability`.`DAY` AS `DAY`, `availability`.`START_TIME` AS `START_TIME`, `availability`.`END_TIME` AS `END_TIME` FROM (`user` join `availability`) WHERE ((`availability`.`DAY` = 'Saturday') AND (`availability`.`TUTOR_ID` = `user`.`USER_ID`) AND `user`.`IS_TUTOR`) ;

-- --------------------------------------------------------

--
-- Structure for view `tutor_classes`
--
DROP TABLE IF EXISTS `tutor_classes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `tutor_classes`  AS SELECT `class`.`CLASS_ID` AS `CLASS_ID`, `class`.`CLASS_CODE` AS `CLASS_CODE`, `class`.`CLASS_NUMBER` AS `CLASS_NUMBER`, `class`.`NAME` AS `NAME`, `class_bridge`.`TUTOR_ID` AS `TUTOR_ID` FROM (`class_bridge` join `class` on((`class`.`CLASS_ID` = `class_bridge`.`CLASS_ID`))) ;

-- --------------------------------------------------------

--
-- Structure for view `tutor_subjects`
--
DROP TABLE IF EXISTS `tutor_subjects`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `tutor_subjects`  AS SELECT `subject_bridge`.`TUTOR_ID` AS `TUTOR_ID`, `subject_bridge`.`SUBJECT_ID` AS `SUBJECT_ID`, `subject`.`NAME` AS `NAME` FROM (`subject_bridge` join `subject` on((`subject_bridge`.`SUBJECT_ID` = `subject`.`SUBJECT_ID`))) ;

-- --------------------------------------------------------

--
-- Structure for view `tutor_subjects_search`
--
DROP TABLE IF EXISTS `tutor_subjects_search`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `tutor_subjects_search`  AS SELECT `user`.`F_NAME` AS `F_NAME`, `user`.`L_NAME` AS `L_NAME`, `user`.`EMAIL` AS `EMAIL`, `subject`.`NAME` AS `NAME`, `subject_bridge`.`TUTOR_ID` AS `TUTOR_ID`, `subject_bridge`.`SUBJECT_ID` AS `SUBJECT_ID`, `tutor`.`AVG_RATING` AS `AVG_RATING` FROM (((`subject_bridge` join `user` on((`user`.`USER_ID` = `subject_bridge`.`TUTOR_ID`))) join `tutor` on((`tutor`.`USER_ID` = `subject_bridge`.`TUTOR_ID`))) join `subject` on((`subject`.`SUBJECT_ID` = `subject_bridge`.`SUBJECT_ID`))) ;

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
  MODIFY `APPOINTMENT_ID` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1009;

--
-- AUTO_INCREMENT for table `availability`
--
ALTER TABLE `availability`
  MODIFY `AVAILABILITY_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `class`
--
ALTER TABLE `class`
  MODIFY `CLASS_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `class_bridge`
--
ALTER TABLE `class_bridge`
  MODIFY `CLASSES_BRIDGE_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `REVIEW_ID` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `subject`
--
ALTER TABLE `subject`
  MODIFY `SUBJECT_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `subject_bridge`
--
ALTER TABLE `subject_bridge`
  MODIFY `SUBJECT_BRIDGE_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tag`
--
ALTER TABLE `tag`
  MODIFY `TAG_ID` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT for table `tag_bridge`
--
ALTER TABLE `tag_bridge`
  MODIFY `TAG_BRIDGE_ID` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `USER_ID` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=168;

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
