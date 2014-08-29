-- phpMyAdmin SQL Dump
-- version 3.5.8.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 29, 2014 at 11:20 AM
-- Server version: 5.1.73
-- PHP Version: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `kickads_appserver_dev`
--

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `name`, `lastname`, `status`) VALUES
(1, 'hipercubus', '25aa0371a79f28acc97fd67e941e2295afe62e30', 'christian.motta@kickads.mobi', 'Christian', 'Motta', ''),
(2, 'pedroforwe', '265392dc2782778664cc9d56c8e3cd9956661bb0', 'pedro.forwe@kickads.mobi', 'Pedro', 'Forwe', ''),
(3, 'emiliomalia', '64ebb85741faaac9aea4f7de827b7290fa21d4d2', 'emilio.maila@kickads.mobi', 'Emilio', 'Malia', 'Active'),
(4, 'exequielarriola', '11f5ee0507c272be14a76e039ee3d48e779ab88d', 'exequiel@vanega.com', 'Exequiel', 'Arriola', 'Active'),
(5, 'fernandoragel', '258c724cba05fdb365a0209d11a1866fe2aed7d7', 'fernando@vanega.com', 'Fernando', 'Ragel', ''),
(6, 'matiascerrotta', 'd5c781d3c9ee8f957978e10293e38e49820bb5e1', 'matias.cerrotta@kickads.mobi', 'Matias', 'Cerrotta', 'Active'),
(7, 'michellescheiber', '906e9b22e2401bfe058ab6d340ab6dceb78ac7a3', 'michelle.schreiber@kickads.mobi', 'Michelle', 'Schreiber', 'Active');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
