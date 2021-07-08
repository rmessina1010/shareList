-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Generation Time: July 08, 2021 at 12:45 AM
-- Server version: 5.6.48-88.0
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ShareListDB`
--

-- --------------------------------------------------------

--
-- Table structure for table `GLCats`
--

CREATE TABLE `GLCats` (
  `GLCID` int(11) NOT NULL,
  `GLCName` tinytext NOT NULL,
  `GLCOrd` int(11) NOT NULL,
  `GLCParent` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `GLists`
--

CREATE TABLE `GLists` (
  `GLID` int(11) NOT NULL,
  `GLName` tinytext NOT NULL,
  `GLOwner` tinyint(128) UNSIGNED NOT NULL,
  `GLEditors` text NOT NULL,
  `GLSubs` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



--
-- Table structure for table `GLItems`
--

CREATE TABLE `GLItems` (
  `GLIID` int(11) NOT NULL,
  `inGList` tinyint(4) NOT NULL,
  `GLICat` text NOT NULL,
  `ItemName` tinytext NOT NULL,
  `Needed` tinyint(1) NOT NULL,
  `QTY` int(11) NOT NULL,
  `image` mediumtext NOT NULL,
  `notes` mediumtext NOT NULL,
  `GLIOrd` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


--
-- Table structure for table `ListOwners`
--

-- DEPRECATED TABLE!!
--CREATE TABLE `ListOwners` (
--  `LOID` smallint(11) UNSIGNED NOT NULL,
--  `LOName` varchar(50) DEFAULT NULL,
--  `LOLastName` tinytext NOT NULL,
--  `LOEmail` tinytext NOT NULL,
--  `LOPassword` varchar(50) DEFAULT NULL,
--  `LOPrefs` text NOT NULL
--) ENGINE=MyISAM DEFAULT CHARSET=latin1;
--

--
-- Table structure for table `ListOwnersNew`
--

CREATE TABLE `ListOwnersNew` (
  `LOID` smallint(11) UNSIGNED NOT NULL,
  `LOName` varchar(50) DEFAULT NULL,
  `LOLastName` tinytext NOT NULL,
  `LOEmail` tinytext NOT NULL,
  `LOPassword` varchar(255) DEFAULT NULL,
  `LOPrefs` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



