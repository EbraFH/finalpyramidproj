-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 23, 2022 at 05:08 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `phppyramid`
--
CREATE DATABASE IF NOT EXISTS `phppyramid` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `phppyramid`;

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE `games` (
  `gameId` int(20) NOT NULL,
  `gameDate` date DEFAULT NULL,
  `tournamentId` int(10) UNSIGNED NOT NULL,
  `playerAId` varchar(20) DEFAULT NULL,
  `playerBId` varchar(20) DEFAULT NULL,
  `gameScore` varchar(20) DEFAULT NULL,
  `gameWinner` varchar(20) DEFAULT NULL,
  `gameLoser` varchar(20) DEFAULT NULL,
  `gameStatus` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `invitations`
--

CREATE TABLE `invitations` (
  `invitationId` int(10) NOT NULL,
  `invitationDate` date DEFAULT NULL,
  `sender` varchar(10) DEFAULT NULL,
  `receiver` varchar(20) DEFAULT NULL,
  `invitationsStatus` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `participants`
--

CREATE TABLE `participants` (
  `userId` varchar(10) DEFAULT NULL,
  `tournamentId` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `participants`
--

INSERT INTO `participants` (`userId`, `tournamentId`) VALUES
('111111113', 1),
('111111112', 1),
('21183', 1),
('777', 1),
('111111113', 3),
('111111112', 3);

-- --------------------------------------------------------

--
-- Table structure for table `rankings`
--

CREATE TABLE `rankings` (
  `rankingNum` int(3) NOT NULL,
  `tournamentId` int(10) UNSIGNED NOT NULL,
  `playerId` varchar(20) NOT NULL,
  `playerName` varchar(20) DEFAULT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `rankings`
--

INSERT INTO `rankings` (`rankingNum`, `tournamentId`, `playerId`, `playerName`, `status`) VALUES
(16, 1, '111111113', 'Divine1', 'InActive'),
(15, 1, '111111112', 'Divine2', 'InActive'),
(13, 1, '21183', 'testModal', 'InActive'),
(14, 1, '777', '6767', 'InActive'),
(15, 3, '111111113', 'Divine1', 'InActive'),
(1, 3, '111111112', 'Divine2', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `tournamentmanagers`
--

CREATE TABLE `tournamentmanagers` (
  `tournamentId` int(10) UNSIGNED NOT NULL,
  `userId` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tournamentmanagers`
--

INSERT INTO `tournamentmanagers` (`tournamentId`, `userId`) VALUES
(1, '111111112'),
(2, '111111112'),
(3, '111111112');

-- --------------------------------------------------------

--
-- Table structure for table `tournaments`
--

CREATE TABLE `tournaments` (
  `tournamentId` int(10) UNSIGNED NOT NULL,
  `tournamentName` varchar(30) DEFAULT NULL,
  `tournamentParticipant` int(3) DEFAULT NULL,
  `tournamentRegistrationDate` date DEFAULT NULL,
  `tournamentStartDate` date DEFAULT NULL,
  `tournamentEndDate` date DEFAULT NULL,
  `tournamentPlace` varchar(20) DEFAULT NULL,
  `tournamentPrize` varchar(20) DEFAULT NULL,
  `tournamentWinner` varchar(20) DEFAULT NULL,
  `tournamentStatus` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tournaments`
--

INSERT INTO `tournaments` (`tournamentId`, `tournamentName`, `tournamentParticipant`, `tournamentRegistrationDate`, `tournamentStartDate`, `tournamentEndDate`, `tournamentPlace`, `tournamentPrize`, `tournamentWinner`, `tournamentStatus`) VALUES
(1, 'test', 15, '2022-07-23', '2022-07-23', '2022-07-29', 'Nazareth', '123123', 'None', 'Active'),
(2, 'test1', 15, '2022-07-23', '2022-07-24', '2022-07-26', 'Nazareth', '34534', 'None', 'Active'),
(3, 'test3', 15, '2022-07-23', '2022-07-29', '2022-08-04', 'Nazareth', '1333', 'None', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userId` varchar(10) NOT NULL,
  `userName` varchar(30) DEFAULT NULL,
  `userPassword` varchar(30) DEFAULT NULL,
  `userPhone` varchar(12) DEFAULT NULL,
  `userBirthDay` date DEFAULT NULL,
  `userAddress` varchar(100) DEFAULT NULL,
  `userEmail` varchar(100) DEFAULT NULL,
  `userRole` varchar(30) DEFAULT NULL,
  `userStatus` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userId`, `userName`, `userPassword`, `userPhone`, `userBirthDay`, `userAddress`, `userEmail`, `userRole`, `userStatus`) VALUES
('000', '012345', '$2y$10$nvaEd5dlk6zU0jKp1ikjLuA', '0123', '2022-06-03', '000', 'testModa0000@gmail.com', 'Player', 'Active'),
('101010', 'haj', '$2y$10$eArR2H4UrgB8hqAA5datdeZ', '069786567', '2022-05-24', 'Nazareth123', 'haj@gmail.com', 'Admin', 'Active'),
('111111112', 'Divine2', 'Divine', '6734889576', '2022-07-08', 'sghshrsth', 'sghshrsth132@gmail.com', 'Tournament Manager', 'Active'),
('111111113', 'Divine1', 'Divine', '3745674587', '2022-07-08', 'sghshrsth', 'sghshrsth132456@gmail.com', 'Admin', 'Active'),
('1593812', 'jojo12345691', '$2y$10$0EuMc0POM8GrzRYYcM4DFu5', '058674284', '2022-05-26', 'Nazareth', 'jojo@gmail.com', 'Player', 'Active'),
('21183', 'testModal', '$2y$10$RIc6YCoQ.gvJ5gEnoVFJ4e8', '0518239451', '2022-05-27', 'Nazareth', 'testModal@gmail.com', 'Admin', 'In-Active'),
('213', 'ibra1', '$2y$10$x4js5fNB8XGvEeiN.os6R.Z', '6056456879', '2022-05-27', 'Nazareth1', 'ibra23@gmail.com', 'Admin', 'In-Active'),
('2135', 'sgfg3', '$2y$10$ekofXq2iWhO/qrmAq4kEwuF', '5674567', '2022-05-13', 'Nazareth', 'testModal546456@gmail.com', 'Admin', 'In-Active'),
('45674567', '45674567', '$2y$10$O59gSk/7lZPhgjrlLgKPkOi', '45674567', '2022-05-13', 'Nazareth', 'testModal54y@gmail.com', 'Admin', '45y4y5'),
('546749586', '67398567493586', '$2y$10$L0o9fxSRz7LDUFhScdxeoO8', '3598673596', '2022-05-14', '4568745869', '45874587@gmail.com', 'Admin', 'Active'),
('777', '6767', '$2y$10$9lpHZSwdBbtLSQrVr1Rgw.N', '6767', '2022-05-14', '676', '676@gmail.com', 'Admin', 'In-Active'),
('789', '7897', '$2y$10$QozqevJy1bSLqmClwqAq4.t', '789789', '2022-05-18', '7898', 'testModal1789@gmail.com', 'Admin', 'In-Active'),
('789789789', '789789789', '$2y$10$6y7naw4wK1xYVsimLGKBYO2', '78978978', '2022-05-24', '6769', 'testModa1l789789@gmail.com', 'Admin', 'In-Active'),
('991', '991', '$2y$10$4H1Nycus0oVFnynR5CO/BOc', '991', '2022-05-27', '991', '991@gmail.com', 'Admin', 'In-Active'),
('999', '999', '$2y$10$hx9cpeplysLWabors6zP/OO', '999', '2022-05-14', 'Nazareth', 'testModal1199@gmail.com', 'Admin', 'In-Active'),
('abd', 'abd', '$2y$10$Om9njJf1lFu1yZ0NRULvNeD', '685685', '2022-05-12', 'Nazareth', 'abd@gmail.com', 'Admin', 'Active'),
('Divine', 'Divine', 'Divine', '123123123123', '2022-04-13', '123123', 'Divine', 'Admin', 'Active'),
('eyal', 'eyal', '$2y$10$26fMntKHUs/iyKQxWAtDGue', '49584985', '2022-05-18', 'Nazareth345', 'testModal123242343@gmail.com', 'Admin', 'Active'),
('ibrahandsa', 'ibrahandsaeem', '$2y$10$wC9fwwVZGMSX23Kt4cxW7.o', '467567', '2022-05-06', 'Nazareth', 'testModal1231231231@gmail.com', 'Admin', 'Active'),
('koko', 'koko', '$2y$10$cTIOZcYtVef8TQx5Luycqe9', '051823945113', '2022-05-07', 'Nazareth', 'testModal4525@gmail.com', 'Admin', 'Active'),
('lolo', 'lolo', '$2y$10$Em.UUDTpiATNRbW7h9NTz.5', '12323177', '2022-05-25', 'Nazareth345', 'testModal@gmail.com453', 'Admin', 'Active'),
('red', 'red', '$2y$10$8nWymfMxFrgqE4eBP3NmLe1', '769487', '2022-05-25', '0001312', 'red@gmail.com', 'Admin', 'In-Active'),
('red1', 'red1', '$2y$10$iL0a825DxSRdBNoATaeKeeT', '567567', '2022-05-19', 'red1', 'red1@gmail.com', 'Admin', 'In-Active'),
('red2', 'red2', '$2y$10$kfDIgwkAPRibXcXkR5NRTuQ', '678678', '2022-06-03', 'red234', 'red22@gmail.com', 'Admin', 'In-Active'),
('red3', 'red3', '$2y$10$fMDEMIIJoDXl977ZDqvepOM', '68678', '2022-06-01', 'red3434', 'ibra123123@gmail.com', 'Admin', 'In-Active'),
('ritshjorth', 'rjtyjtyj', '$2y$10$9DVt7LDcNO0qVQIUE5QHGec', '954068456', '2022-05-27', 'Nazareth423', 'testModal2342@gmail.com', 'Admin', 'Active'),
('rs', 'rs', '$2y$10$.nACngHp9uQy/TyuuUtlgO3', 'rs123', '2022-05-20', 'Nazareth', 'testModa123123l@gmail.com', 'Admin', 'Active'),
('test1', 'test1', '$2y$10$GZllKz.36G71TvdwdQwArOP', '123123123123', '2022-04-08', '123123', 'test1', 'Player', 'Active'),
('test123123', 'testModal123123', '$2y$10$DU1yj7TYuL/o9ro8FBW1Ge9', '051823945112', '2022-05-20', 'Nazareth', 'testModal123@gmail.com', 'Admin', 'Active'),
('testooooo', 'testoooo', '$2y$10$Wep2Ogvmoz2r9GoKKjieKuP', '435034', '2022-05-26', 'Nazareth123', 'testModal1223123@gmail.com', 'Admin', 'In-Active'),
('testred', 'testred', '$2y$10$XtUFDC/lNzeLqhi4aLSPie3', '76969', '2022-05-25', '000123123', 'ibrared@gmail.com', 'Admin', 'In-Active'),
('testRefres', '53748953', '$2y$10$SMg57g4HT2jDCo1tMwdwDO8', '051823945134', '2022-05-06', 'Nazareth', 'testModal53453@gmail.com', 'Admin', 'Active'),
('ufkfyu', 'fukfyu', '$2y$10$/ccEx551t74AXYEysD1IrO4', '051823945113', '2022-05-06', 'Nazareth', 'testModadfghl@gmail.com', 'Admin', 'Active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`tournamentId`);

--
-- Indexes for table `invitations`
--
ALTER TABLE `invitations`
  ADD PRIMARY KEY (`invitationId`),
  ADD KEY `sender` (`sender`),
  ADD KEY `receiver` (`receiver`);

--
-- Indexes for table `participants`
--
ALTER TABLE `participants`
  ADD KEY `userId` (`userId`),
  ADD KEY `tournamentId` (`tournamentId`);

--
-- Indexes for table `rankings`
--
ALTER TABLE `rankings`
  ADD KEY `playerId` (`playerId`);

--
-- Indexes for table `tournamentmanagers`
--
ALTER TABLE `tournamentmanagers`
  ADD PRIMARY KEY (`tournamentId`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `tournaments`
--
ALTER TABLE `tournaments`
  ADD PRIMARY KEY (`tournamentId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `tournamentId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invitations`
--
ALTER TABLE `invitations`
  MODIFY `invitationId` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `participants`
--
ALTER TABLE `participants`
  MODIFY `tournamentId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tournamentmanagers`
--
ALTER TABLE `tournamentmanagers`
  MODIFY `tournamentId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tournaments`
--
ALTER TABLE `tournaments`
  MODIFY `tournamentId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `games`
--
ALTER TABLE `games`
  ADD CONSTRAINT `games_ibfk_1` FOREIGN KEY (`tournamentId`) REFERENCES `tournaments` (`tournamentId`);

--
-- Constraints for table `invitations`
--
ALTER TABLE `invitations`
  ADD CONSTRAINT `invitations_ibfk_1` FOREIGN KEY (`sender`) REFERENCES `users` (`userId`);

--
-- Constraints for table `participants`
--
ALTER TABLE `participants`
  ADD CONSTRAINT `participants_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`),
  ADD CONSTRAINT `participants_ibfk_2` FOREIGN KEY (`tournamentId`) REFERENCES `tournaments` (`tournamentId`);

--
-- Constraints for table `tournamentmanagers`
--
ALTER TABLE `tournamentmanagers`
  ADD CONSTRAINT `tournamentmanagers_ibfk_2` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`),
  ADD CONSTRAINT `tournamentmanagers_ibfk_3` FOREIGN KEY (`tournamentId`) REFERENCES `tournaments` (`tournamentId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
