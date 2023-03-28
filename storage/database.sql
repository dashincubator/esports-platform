-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 30, 2021 at 05:43 PM
-- Server version: 10.5.12-MariaDB-1:10.5.12+maria~focal
-- PHP Version: 7.4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gamrs`
--

-- --------------------------------------------------------

--
-- Table structure for table `DeleteHistory`
--

CREATE TABLE `DeleteHistory` (
  `createdAt` int(11) NOT NULL,
  `data` text NOT NULL,
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `Game`
--

CREATE TABLE `Game` (
  `account` varchar(255) NOT NULL,
  `banner` text NOT NULL,
  `card` text NOT NULL,
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `platform` int(11) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `totalActiveLadders` int(11) NOT NULL,
  `totalActiveLeagues` int(11) NOT NULL,
  `totalActiveTournaments` int(11) NOT NULL,
  `totalMatchesPlayed` int(11) NOT NULL,
  `totalPrizesPaid` int(11) NOT NULL,
  `totalWagered` int(11) NOT NULL,
  `view` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `GameApiMatch`
--

CREATE TABLE `GameApiMatch` (
  `api` varchar(64) NOT NULL,
  `createdAt` int(11) NOT NULL,
  `data` text NOT NULL,
  `endedAt` int(11) NOT NULL,
  `hash` varchar(64) NOT NULL,
  `id` int(11) NOT NULL,
  `startedAt` int(11) NOT NULL,
  `username` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `GamePlatform`
--

CREATE TABLE `GamePlatform` (
  `account` varchar(255) NOT NULL,
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `view` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `Ladder`
--

CREATE TABLE `Ladder` (
  `endsAt` int(11) NOT NULL,
  `entryFee` decimal(10,2) NOT NULL COMMENT 'Per User',
  `entryFeePrizes` text NOT NULL,
  `firstToScore` int(11) NOT NULL,
  `format` varchar(60) NOT NULL,
  `game` int(11) NOT NULL,
  `gametypes` mediumtext NOT NULL,
  `id` int(11) NOT NULL,
  `info` mediumtext NOT NULL,
  `maxPlayersPerTeam` int(11) NOT NULL COMMENT 'Max Roster Size',
  `membershipRequired` tinyint(1) NOT NULL COMMENT '1 = Premium Required For Each User',
  `minPlayersPerTeam` int(11) NOT NULL COMMENT 'Min Roster Size',
  `name` varchar(255) NOT NULL,
  `organization` int(11) NOT NULL,
  `prizePool` decimal(10,2) NOT NULL,
  `prizes` mediumtext NOT NULL,
  `prizesAdjusted` tinyint(1) NOT NULL,
  `region` mediumtext NOT NULL,
  `rules` mediumtext NOT NULL,
  `sidebar` text NOT NULL,
  `slug` varchar(255) NOT NULL,
  `startsAt` int(11) NOT NULL,
  `status` int(11) NOT NULL COMMENT '0 = Registration Open, 1 = Registration Invite Only, 2 = Registration Closed',
  `stopLoss` int(11) NOT NULL,
  `totalLockedMembers` int(11) NOT NULL,
  `totalMatchesPlayed` int(11) NOT NULL,
  `totalRankedTeams` int(11) NOT NULL,
  `totalRegisteredTeams` int(11) NOT NULL,
  `totalWagered` int(11) NOT NULL,
  `type` mediumtext NOT NULL COMMENT 'Ladder, League, Survival, etc.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `LadderGametype`
--

CREATE TABLE `LadderGametype` (
  `bestOf` text NOT NULL,
  `game` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `mapsets` text NOT NULL,
  `modifiers` text NOT NULL,
  `name` text NOT NULL,
  `playersPerTeam` text NOT NULL,
  `teamsPerMatch` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `LadderMatch`
--

CREATE TABLE `LadderMatch` (
  `bestOf` int(11) NOT NULL,
  `createdAt` int(11) NOT NULL,
  `gametype` int(11) NOT NULL,
  `hosts` text NOT NULL,
  `id` int(11) NOT NULL,
  `ladder` int(11) NOT NULL,
  `mapset` text NOT NULL,
  `modifiers` text NOT NULL,
  `playersPerTeam` int(11) NOT NULL,
  `startedAt` int(11) NOT NULL,
  `status` int(11) NOT NULL COMMENT '0 = In Matchfinder, 1 = Upcoming, 2 = Playing, 3 = Complete, 4 = Disputed',
  `team` int(11) NOT NULL,
  `teamsPerMatch` int(11) NOT NULL COMMENT '# Of Teams In Match',
  `user` int(11) NOT NULL COMMENT 'Match Creator',
  `wager` decimal(10,2) NOT NULL COMMENT 'Per Player',
  `wagerComplete` int(11) NOT NULL,
  `winningTeam` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `LadderMatchReport`
--

CREATE TABLE `LadderMatchReport` (
  `createdAt` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `match` int(11) NOT NULL,
  `placement` int(11) NOT NULL,
  `reportedAt` int(11) NOT NULL,
  `roster` mediumtext NOT NULL,
  `team` int(11) NOT NULL,
  `user` int(11) NOT NULL COMMENT 'Reported By'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `LadderTeam`
--

CREATE TABLE `LadderTeam` (
  `avatar` text NOT NULL,
  `banner` text NOT NULL,
  `bio` varchar(255) CHARACTER SET utf8 NOT NULL,
  `createdAt` int(11) NOT NULL,
  `createdBy` int(11) NOT NULL,
  `earnings` decimal(10,2) NOT NULL,
  `glicko2Rating` decimal(10,2) NOT NULL,
  `glicko2RatingDeviation` decimal(10,2) NOT NULL,
  `glicko2Volatility` decimal(10,2) NOT NULL,
  `id` int(11) NOT NULL,
  `ladder` int(11) NOT NULL,
  `locked` tinyint(1) NOT NULL COMMENT '0 = Locked, 1 = Unlocked',
  `lockedAt` int(11) NOT NULL,
  `losses` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `rank` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `scoreModifiedAt` int(11) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `wins` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `LadderTeamMember`
--

CREATE TABLE `LadderTeamMember` (
  `account` varchar(60) NOT NULL,
  `createdAt` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `managesMatches` tinyint(1) NOT NULL,
  `managesTeam` tinyint(1) NOT NULL,
  `score` int(11) NOT NULL,
  `scoreModifiedAt` int(11) NOT NULL,
  `status` int(11) NOT NULL COMMENT '0 = Team Invite, 1 = On Team',
  `team` int(11) NOT NULL,
  `user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `Organization`
--

CREATE TABLE `Organization` (
  `createdAt` int(11) NOT NULL,
  `domain` varchar(255) NOT NULL,
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `paypal` varchar(60) NOT NULL,
  `user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Organization`
--

INSERT INTO `Organization` (`createdAt`, `domain`, `id`, `name`, `paypal`, `user`) VALUES
(1630345317, 'gamrs.net', 1, 'GAMRS', 'payments@gamrs.net', 1);

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE `User` (
  `adminPosition` int(11) NOT NULL,
  `avatar` text NOT NULL,
  `banner` text NOT NULL,
  `bio` varchar(255) NOT NULL,
  `createdAt` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `id` int(11) NOT NULL,
  `locked` tinyint(1) NOT NULL COMMENT '0 = Unlocked, 1 = Locked',
  `lockedAt` int(11) NOT NULL,
  `membershipExpiresAt` int(11) NOT NULL,
  `organization` int(11) NOT NULL,
  `password` text NOT NULL,
  `signinToken` varchar(255) NOT NULL,
  `slug` varchar(32) NOT NULL,
  `timezone` varchar(255) NOT NULL,
  `username` varchar(32) NOT NULL,
  `wagers` tinyint(1) NOT NULL COMMENT '0 = Off, 1 = On'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `UserAccount`
--

CREATE TABLE `UserAccount` (
  `createdAt` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `user` int(11) NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `UserAdminPosition`
--

CREATE TABLE `UserAdminPosition` (
  `createdAt` int(11) NOT NULL,
  `games` text NOT NULL,
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `organization` int(11) NOT NULL,
  `permissions` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `UserBank`
--

CREATE TABLE `UserBank` (
  `balance` decimal(10,2) NOT NULL,
  `createdAt` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `organization` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `withdrawable` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `UserBankDeposit`
--

CREATE TABLE `UserBankDeposit` (
  `amount` decimal(10,2) NOT NULL,
  `chargeback` tinyint(1) NOT NULL COMMENT '0 = False, 1 = True',
  `createdAt` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `fee` decimal(10,2) NOT NULL,
  `id` int(11) NOT NULL,
  `organization` int(11) NOT NULL,
  `processedAt` int(11) NOT NULL,
  `processor` varchar(255) NOT NULL,
  `processorTransaction` text NOT NULL,
  `processorTransactionId` varchar(250) NOT NULL,
  `user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `UserBankTransaction`
--

CREATE TABLE `UserBankTransaction` (
  `amount` decimal(10,2) NOT NULL,
  `createdAt` int(11) NOT NULL,
  `fee` decimal(10,2) NOT NULL,
  `id` int(11) NOT NULL,
  `ladder` int(11) NOT NULL,
  `ladderMatch` int(11) NOT NULL,
  `memo` text NOT NULL,
  `refundedAt` int(11) NOT NULL,
  `team` int(11) NOT NULL,
  `tournament` int(11) NOT NULL,
  `type` int(11) NOT NULL COMMENT '0 = Credit (-), 1 = Debit (+)',
  `user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `UserBankWithdraw`
--

CREATE TABLE `UserBankWithdraw` (
  `amount` decimal(10,2) NOT NULL,
  `createdAt` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `fee` decimal(10,2) NOT NULL,
  `id` int(11) NOT NULL,
  `organization` int(11) NOT NULL,
  `processedAt` int(11) NOT NULL,
  `processor` varchar(255) NOT NULL,
  `processorTransactionId` text NOT NULL,
  `user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `UserForgotPassword`
--

CREATE TABLE `UserForgotPassword` (
  `code` text CHARACTER SET utf8 NOT NULL,
  `createdAt` int(11) NOT NULL,
  `emailedAt` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `UserLockMessage`
--

CREATE TABLE `UserLockMessage` (
  `content` mediumtext NOT NULL,
  `createdAt` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `UserLockMessageTemplate`
--

CREATE TABLE `UserLockMessageTemplate` (
  `content` mediumtext NOT NULL,
  `createdAt` int(11) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `UserRank`
--

CREATE TABLE `UserRank` (
  `earnings` decimal(10,2) NOT NULL,
  `game` int(11) NOT NULL,
  `glicko2Rating` decimal(10,2) NOT NULL,
  `glicko2RatingDeviation` decimal(10,2) NOT NULL,
  `glicko2Volatility` decimal(10,2) NOT NULL,
  `id` int(11) NOT NULL,
  `losses` int(11) NOT NULL,
  `rank` int(11) NOT NULL,
  `score` decimal(10,2) NOT NULL,
  `scoreModifiedAt` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `wins` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `DeleteHistory`
--
ALTER TABLE `DeleteHistory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Game`
--
ALTER TABLE `Game`
  ADD PRIMARY KEY (`id`),
  ADD KEY `platform` (`platform`),
  ADD KEY `slug` (`slug`(191)),
  ADD KEY `id` (`id`);

--
-- Indexes for table `GameApiMatch`
--
ALTER TABLE `GameApiMatch`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hash` (`hash`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `hash_2` (`hash`),
  ADD KEY `api` (`api`,`username`),
  ADD KEY `api_2` (`api`,`createdAt`,`startedAt`,`username`);

--
-- Indexes for table `GamePlatform`
--
ALTER TABLE `GamePlatform`
  ADD PRIMARY KEY (`id`),
  ADD KEY `slug` (`slug`(191)),
  ADD KEY `id` (`id`);

--
-- Indexes for table `Ladder`
--
ALTER TABLE `Ladder`
  ADD PRIMARY KEY (`id`),
  ADD KEY `game` (`game`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `LadderGametype`
--
ALTER TABLE `LadderGametype`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `LadderMatch`
--
ALTER TABLE `LadderMatch`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `ladder` (`ladder`),
  ADD KEY `winningTeam` (`winningTeam`),
  ADD KEY `team` (`team`),
  ADD KEY `user` (`user`);

--
-- Indexes for table `LadderMatchReport`
--
ALTER TABLE `LadderMatchReport`
  ADD PRIMARY KEY (`id`),
  ADD KEY `match` (`match`);

--
-- Indexes for table `LadderTeam`
--
ALTER TABLE `LadderTeam`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ladder` (`ladder`),
  ADD KEY `locked` (`locked`),
  ADD KEY `slug` (`slug`(191));

--
-- Indexes for table `LadderTeamMember`
--
ALTER TABLE `LadderTeamMember`
  ADD PRIMARY KEY (`id`),
  ADD KEY `team` (`team`),
  ADD KEY `user` (`user`);

--
-- Indexes for table `Organization`
--
ALTER TABLE `Organization`
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `domain` (`domain`),
  ADD KEY `id_2` (`id`),
  ADD KEY `domain_2` (`domain`);

--
-- Indexes for table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `id_2` (`id`),
  ADD KEY `slug_2` (`slug`);

--
-- Indexes for table `UserAccount`
--
ALTER TABLE `UserAccount`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `UserAdminPosition`
--
ALTER TABLE `UserAdminPosition`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `UserBank`
--
ALTER TABLE `UserBank`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `id_2` (`id`);

--
-- Indexes for table `UserBankDeposit`
--
ALTER TABLE `UserBankDeposit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user` (`user`) USING BTREE;

--
-- Indexes for table `UserBankTransaction`
--
ALTER TABLE `UserBankTransaction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user` (`user`);

--
-- Indexes for table `UserBankWithdraw`
--
ALTER TABLE `UserBankWithdraw`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id_2` (`user`);

--
-- Indexes for table `UserForgotPassword`
--
ALTER TABLE `UserForgotPassword`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `user` (`user`);

--
-- Indexes for table `UserLockMessage`
--
ALTER TABLE `UserLockMessage`
  ADD PRIMARY KEY (`id`),
  ADD KEY `createdAt` (`createdAt`);

--
-- Indexes for table `UserLockMessageTemplate`
--
ALTER TABLE `UserLockMessageTemplate`
  ADD PRIMARY KEY (`id`),
  ADD KEY `createdAt` (`createdAt`);

--
-- Indexes for table `UserRank`
--
ALTER TABLE `UserRank`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user` (`user`),
  ADD KEY `game` (`game`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `DeleteHistory`
--
ALTER TABLE `DeleteHistory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Game`
--
ALTER TABLE `Game`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `GameApiMatch`
--
ALTER TABLE `GameApiMatch`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `GamePlatform`
--
ALTER TABLE `GamePlatform`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Ladder`
--
ALTER TABLE `Ladder`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `LadderGametype`
--
ALTER TABLE `LadderGametype`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `LadderMatch`
--
ALTER TABLE `LadderMatch`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `LadderMatchReport`
--
ALTER TABLE `LadderMatchReport`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `LadderTeam`
--
ALTER TABLE `LadderTeam`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `LadderTeamMember`
--
ALTER TABLE `LadderTeamMember`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Organization`
--
ALTER TABLE `Organization`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `User`
--
ALTER TABLE `User`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `UserAccount`
--
ALTER TABLE `UserAccount`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `UserAdminPosition`
--
ALTER TABLE `UserAdminPosition`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `UserBank`
--
ALTER TABLE `UserBank`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `UserBankDeposit`
--
ALTER TABLE `UserBankDeposit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `UserBankTransaction`
--
ALTER TABLE `UserBankTransaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `UserBankWithdraw`
--
ALTER TABLE `UserBankWithdraw`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `UserForgotPassword`
--
ALTER TABLE `UserForgotPassword`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `UserLockMessage`
--
ALTER TABLE `UserLockMessage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `UserLockMessageTemplate`
--
ALTER TABLE `UserLockMessageTemplate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `UserRank`
--
ALTER TABLE `UserRank`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
