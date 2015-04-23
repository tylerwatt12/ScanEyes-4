-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 23, 2015 at 09:20 PM
-- Server version: 5.6.21
-- PHP Version: 5.6.3
--
-- Database: `scaneyes315`
--
CREATE DATABASE IF NOT EXISTS `scaneyes315` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `scaneyes315`;

-- --------------------------------------------------------

--
-- Table structure for table `calls`
--

DROP TABLE IF EXISTS `calls`;
CREATE TABLE IF NOT EXISTS `calls` (
  `TIME` varchar(20) NOT NULL,
  `SRCID` varchar(20) NOT NULL,
  `TGTID` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ids`
--

DROP TABLE IF EXISTS `ids`;
CREATE TABLE IF NOT EXISTS `ids` (
  `ID` int(8) NOT NULL COMMENT 'ID',
  `TAG` varchar(16) DEFAULT NULL COMMENT 'Short Alpha Tag',
  `LABEL` varchar(64) DEFAULT NULL COMMENT 'Extended Alpha Tag',
  `TYPE` varchar(2) DEFAULT NULL COMMENT 'TG/RD Talkgroup/Radio',
  `LOCKOUT` varchar(1) DEFAULT NULL COMMENT 'Hidden ID 0/1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `calls`
--
ALTER TABLE `calls`
 ADD UNIQUE KEY `TIME` (`TIME`);

--
-- Indexes for table `ids`
--
ALTER TABLE `ids`
 ADD PRIMARY KEY (`ID`), ADD UNIQUE KEY `ID` (`ID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
