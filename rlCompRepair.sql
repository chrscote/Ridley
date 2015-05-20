-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 04, 2015 at 03:05 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `rlcomprepair`
--

-- --------------------------------------------------------

--
-- Table structure for table `actionstaken`
--

CREATE TABLE IF NOT EXISTS `actionstaken` (
  `ActionID` int(11) NOT NULL AUTO_INCREMENT,
  `TechID_FK` int(11) NOT NULL,
  `IssueID_FK` int(11) NOT NULL,
  `Action` longtext NOT NULL,
  `ActionDate` date NOT NULL,
  PRIMARY KEY (`ActionID`),
  KEY `TechID_FK` (`TechID_FK`,`IssueID_FK`),
  KEY `IssueID_FK` (`IssueID_FK`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `AdminID` int(11) NOT NULL AUTO_INCREMENT,
  `UserName` text NOT NULL,
  `Password` text NOT NULL,
  PRIMARY KEY (`AdminID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`AdminID`, `UserName`, `Password`) VALUES
(1, 'rCusano', '5f4dcc3b5aa765d61d8327deb882cf99'),
(2, 'admin', '21232f297a57a5a743894a0e4a801fc3');

-- --------------------------------------------------------

--
-- Table structure for table `assignments`
--

CREATE TABLE IF NOT EXISTS `assignments` (
  `AssignmentID` int(11) NOT NULL AUTO_INCREMENT,
  `TechID_FK` int(11) NOT NULL,
  `IssueID_FK` int(11) NOT NULL,
  `DateStart` date NOT NULL,
  `DateEnd` date NOT NULL,
  PRIMARY KEY (`AssignmentID`),
  KEY `TechID_FK` (`TechID_FK`,`IssueID_FK`),
  KEY `IssueID_FK` (`IssueID_FK`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `computers`
--

CREATE TABLE IF NOT EXISTS `computers` (
  `ComputerID` int(11) NOT NULL AUTO_INCREMENT,
  `ComputerModel` varchar(50) NOT NULL,
  `ComputerSN` varchar(25) NOT NULL DEFAULT 'NA',
  `LogIn` varchar(50) NOT NULL,
  `Password` varchar(50) NOT NULL,
  `CustomerID_FK` int(11) NOT NULL,
  PRIMARY KEY (`ComputerID`),
  KEY `CustomerID_FK` (`CustomerID_FK`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE IF NOT EXISTS `customers` (
  `CustomerID` int(11) NOT NULL AUTO_INCREMENT,
  `LastName` varchar(25) CHARACTER SET utf8 NOT NULL,
  `FirstName` varchar(25) CHARACTER SET utf8 NOT NULL,
  `Street` varchar(200) CHARACTER SET utf8 NOT NULL,
  `City` varchar(50) CHARACTER SET utf8 NOT NULL,
  `State` varchar(2) CHARACTER SET utf8 NOT NULL,
  `Zip` varchar(10) CHARACTER SET utf8 NOT NULL,
  `Telephone` varchar(15) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`CustomerID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Table structure for table `issues`
--

CREATE TABLE IF NOT EXISTS `issues` (
  `IssueID` int(11) NOT NULL AUTO_INCREMENT,
  `DateRequested` date NOT NULL,
  `ComputerID_FK` int(11) NOT NULL,
  `Issue` longtext NOT NULL,
  `ItemsIncl` longtext NOT NULL,
  `ImageName` varchar(200) NOT NULL,
  `DateReceived` date NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`IssueID`),
  KEY `ComputerID_FK` (`ComputerID_FK`,`Status`),
  KEY `Status` (`Status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE IF NOT EXISTS `status` (
  `StatusID` int(11) NOT NULL AUTO_INCREMENT,
  `Status` varchar(50) NOT NULL,
  PRIMARY KEY (`StatusID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`StatusID`, `Status`) VALUES
(1, 'Unassigned'),
(2, 'Debugging'),
(3, 'Waiting for customer response'),
(4, 'Waiting for part'),
(5, 'Request completed/Customer called for pickup'),
(6, 'Customer signed off');

-- --------------------------------------------------------

--
-- Table structure for table `techs`
--

CREATE TABLE IF NOT EXISTS `techs` (
  `TechID` int(11) NOT NULL AUTO_INCREMENT,
  `FirstName` varchar(25) NOT NULL,
  `LastName` varchar(25) NOT NULL,
  `CurrentStudent` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`TechID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `actionstaken`
--
ALTER TABLE `actionstaken`
  ADD CONSTRAINT `actionstaken_ibfk_2` FOREIGN KEY (`IssueID_FK`) REFERENCES `issues` (`IssueID`),
  ADD CONSTRAINT `actionstaken_ibfk_1` FOREIGN KEY (`TechID_FK`) REFERENCES `techs` (`TechID`);

--
-- Constraints for table `assignments`
--
ALTER TABLE `assignments`
  ADD CONSTRAINT `assignments_ibfk_2` FOREIGN KEY (`IssueID_FK`) REFERENCES `issues` (`IssueID`),
  ADD CONSTRAINT `assignments_ibfk_1` FOREIGN KEY (`TechID_FK`) REFERENCES `techs` (`TechID`);

--
-- Constraints for table `computers`
--
ALTER TABLE `computers`
  ADD CONSTRAINT `computers_ibfk_1` FOREIGN KEY (`CustomerID_FK`) REFERENCES `customers` (`CustomerID`);

--
-- Constraints for table `issues`
--
ALTER TABLE `issues`
  ADD CONSTRAINT `issues_ibfk_2` FOREIGN KEY (`Status`) REFERENCES `status` (`StatusID`),
  ADD CONSTRAINT `issues_ibfk_1` FOREIGN KEY (`ComputerID_FK`) REFERENCES `computers` (`ComputerID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
