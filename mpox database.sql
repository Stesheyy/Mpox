-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 09, 2024 at 08:57 AM
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
-- Database: `mpox`
--

-- --------------------------------------------------------

--
-- Table structure for table `analysis`
--

CREATE TABLE `analysis` (
  `Name` varchar(100) NOT NULL,
  `dob` date NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone_number` int(20) NOT NULL,
  `todays_date` date NOT NULL DEFAULT current_timestamp(),
  `q1` int(11) NOT NULL,
  `q2` int(11) NOT NULL,
  `q3` int(11) NOT NULL,
  `q4` int(11) NOT NULL,
  `q5` int(11) NOT NULL,
  `q6` int(11) NOT NULL,
  `q7` int(11) NOT NULL,
  `q8` int(11) NOT NULL,
  `q9` int(11) NOT NULL,
  `additional_symptoms` text NOT NULL,
  `time_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `analysis`
--

INSERT INTO `analysis` (`Name`, `dob`, `email`, `phone_number`, `todays_date`, `q1`, `q2`, `q3`, `q4`, `q5`, `q6`, `q7`, `q8`, `q9`, `additional_symptoms`, `time_created`, `id`) VALUES
('', '0000-00-00', '', 0, '0000-00-00', 1, 2, 1, 1, 2, 2, 1, 2, 2, 'i have', '2024-11-21 16:03:33', 3),
('', '0000-00-00', '', 0, '0000-00-00', 0, 1, 1, 2, 1, 2, 1, 2, 2, 'nope', '2024-11-21 16:22:17', 4),
('', '0000-00-00', '', 0, '0000-00-00', 2, 1, 2, 2, 3, 1, 2, 2, 2, 'Skin rashes and a bit of fever', '2024-11-21 16:25:26', 5),
('Robert Greene', '2004-11-08', '', 0, '2024-11-25', 1, 2, 1, 1, 2, 1, 1, 2, 2, 'I have a very high fever', '2024-11-25 09:17:18', 6),
('Ivy Bwalei', '2010-06-16', '', 0, '2024-11-26', 1, 1, 2, 2, 1, 1, 2, 1, 1, 'abcd', '2024-11-26 09:08:26', 7),
('Billy Black', '2000-02-04', '', 0, '2024-11-26', 1, 1, 2, 1, 2, 1, 1, 2, 2, 'yesyesyes', '2024-11-26 10:00:23', 8),
('Billy Blue', '2005-10-16', 'bblue@gmail.com', 798765432, '2024-11-29', 1, 1, 2, 2, 2, 2, 1, 2, 2, 'nope', '2024-11-26 10:04:19', 9),
('Nancy Drew', '2013-02-05', 'ndrew@gmail.com', 798765432, '2024-11-28', 2, 2, 2, 1, 3, 2, 2, 2, 2, 'acbdbdlkdwjds', '2024-11-28 15:11:18', 10);

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `Name` varchar(50) NOT NULL,
  `Email` varchar(20) NOT NULL,
  `phone_number` int(30) NOT NULL DEFAULT 0,
  `Message` varchar(500) NOT NULL,
  `Id` int(11) NOT NULL,
  `time_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`Name`, `Email`, `phone_number`, `Message`, `Id`, `time_created`) VALUES
('Stellah Joy', 'stellah@gmail.com', 743216684, 'I really appreciate the work you are doing!', 4, '2024-11-21 16:17:18'),
('Joy Stellah', 'joysakaja@gmail.com', 743216684, 'Thank you!', 5, '2024-11-21 16:18:34'),
('Joy Stellah', 'joysakaja@gmail.com', 1115312557, 'thanks', 6, '2024-11-21 16:19:33'),
('Joy Stellah', 'joysakaja@gmail.com', 1115312557, 'thanks', 7, '2024-11-21 16:20:14'),
('Stacy Mercy', 'stacy@gmail.com', 756781233, 'Thank you!', 8, '2024-11-21 16:28:27'),
('Robert Greene', 'greene@gmail.com', 708124567, 'Thank you for the amazing work that you do to curb the mpox virus!', 9, '2024-11-26 04:23:11'),
('Ivy Bwalei', 'ivybwalei@gmail.com', 712745785, 'I really admire how fast your response is to any mpox case, Thank you for your amazing efforts !:)', 10, '2024-11-26 04:26:27'),
('Joy Stellah', 'joysakaja@gmail.com', 789764539, 'I\'m the admin', 11, '2024-11-26 04:29:21'),
('Nancy Drew', 'ndrew@gmail.com', 798765432, 'abcddhkj.,,', 12, '2024-11-28 15:12:20'),
('Fletcher Gordon', 'fgordon@gmail.com', 721310465, 'I love the efforts you guys are putting in place to curb the spread of mpox. This website has really educated me and i\'m not as scared as i was. Knowledge really is power. Thanks a lot:))', 13, '2024-12-02 13:52:46');

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `failed_attempts` int(11) DEFAULT 0,
  `last_attempt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login_attempts`
--

INSERT INTO `login_attempts` (`id`, `email`, `failed_attempts`, `last_attempt`) VALUES
(9, 'ivybwalei@gmail.com', 5, '2024-11-28 18:04:54');

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `Id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(20) NOT NULL,
  `location` varchar(50) NOT NULL,
  `symptoms_observed` varchar(200) NOT NULL,
  `date_of_exposure` date NOT NULL,
  `additional_info` varchar(300) NOT NULL,
  `time_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `report`
--

INSERT INTO `report` (`Id`, `name`, `email`, `location`, `symptoms_observed`, `date_of_exposure`, `additional_info`, `time_created`) VALUES
(5, 'Stacey Kirui', 'kirui@gmail.com', 'Eldoret', 'fever, muscle aches , skin rashes', '0000-00-00', 'n/a', '2024-11-21 16:07:05'),
(6, 'Stacey Kirui', 'kirui@gmail.com', 'Eldoret', 'fever, muscle aches , skin rashes', '0000-00-00', 'n/a', '2024-11-21 16:07:56'),
(7, 'William Delian', 'delian@gmail.com', 'Kakamega', 'skin rashes on children', '0000-00-00', 'n/a', '2024-11-21 16:10:31'),
(8, 'Stacy Mercy', 'stacy@gmail.com', 'Kiambu', 'Skin rashes on children between the age of 8 and 12', '2024-11-01', 'The symptoms were only visible in children.', '2024-11-21 16:27:19'),
(9, 'Nancy Drew', 'ndrew@gmail.com', 'Kitale', 'abcd', '2024-10-30', 'acdsxjsx', '2024-11-28 15:09:49');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `Id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Healthcare','Patient') NOT NULL,
  `time_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`Id`, `name`, `email`, `password`, `role`, `time_created`) VALUES
(7, 'Robert Greene', 'greene@gmail.com', '$2y$10$w3LsxXCC9yy34yl.NEYGo.tJBM9tfpKQPJeUJOMxxAs5oJsYXPjS.', 'Healthcare', '2024-11-24 18:37:33'),
(8, 'Sienna Drew', 'thalion@gmail.com', '$2y$10$pwQgqpHBHL4gyzM8w8iJ7uL2si4SsxmNiSY13MPxprdoDVnBhLuRy', 'Healthcare', '2024-11-25 11:29:26'),
(10, 'Joy Stellah', 'joysakaja@gmail.com', '$2y$10$m7q05yHlr27.CvBe/loN8.zku6QAcCThI5vppgbSmwDDXbSBflOjW', 'Admin', '2024-11-25 14:50:27'),
(12, 'Barasa Fredrick', 'barasa@gmail.com', '$2y$10$Nw9pM0TUyfCuxkq4bHVvMeTizbdHl0Nqu3yoO/Ypf7AybcZguekEK', 'Healthcare', '2024-11-25 15:13:16'),
(13, 'Ivy Bwalei', 'ivybwalei@gmail.com', '$2y$10$Zn0xYEm/.lYBPMJTRJMyueABCqd8iQ3lMHDvlqQGwiXDJkAmOVgd6', 'Patient', '2024-11-25 17:26:49'),
(15, 'Daniel Caesar', 'dcaesar@gmail.com', '$2y$10$vzuGSIfXE9U3XPsbdlzkWOcMf6xJQDgB/8Nw7PCCpfdozH0ZB.f1C', 'Patient', '2024-11-26 07:40:10'),
(23, 'James Christopher', 'christopher@gmail.com', '$2y$10$NPcqCvKaBW7za3XgNv11MuSOkK4ncNQKj8rDHJUE.vdGjDcHTWIz.', 'Patient', '2024-11-26 09:02:30'),
(25, 'Billie Eilish', 'beilish@gmail.com', '$2y$10$LrHev4WL7cUufRP.WLyaQuEDsbz8UzIrmmeIw/Szvy9eas7yF9pTO', 'Healthcare', '2024-11-26 12:09:25'),
(26, 'Sabrina Carpenter', 'scarpenter@gmail.com', '$2y$10$DPWlvQ405HoikXY5RgpUAuwmS7ym2c1.S6Hr.NCcHf4MIx0ACqhAK', 'Healthcare', '2024-11-26 12:13:25'),
(27, 'Fletcher Gordon', 'fgordon@gmail.com', '$2y$10$rtzPArvh8n7boouValsSaum7AQ03uhrpj0kCyvXmpx8E7mgL0qWne', 'Patient', '2024-12-02 13:54:47'),
(28, 'Gordon Davis', 'gdavis@gmail.com', '$2y$10$5Nb8u0sNk5D2PONIztBfl.WL9gto88lEAUTjWXj0h/vaIk4BNJuSS', 'Healthcare', '2024-12-02 13:56:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `analysis`
--
ALTER TABLE `analysis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `analysis`
--
ALTER TABLE `analysis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
