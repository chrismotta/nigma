-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 21-07-2014 a las 12:39:00
-- Versión del servidor: 5.5.38
-- Versión de PHP: 5.3.10-1ubuntu3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `kickads_appserver`
--


--
-- Volcado de datos para la tabla `finance_entities`
--

INSERT INTO `finance_entities` (`id`, `rec`, `commercial`, `address`, `tax_id`, `tax_percent`, `zip_code`, `country`, `state`, `web`) VALUES
(1, 0, 'test', '', '', '', '', '', '', '');


--
-- Volcado de datos para la tabla `advertisers`
--

INSERT INTO `advertisers` (`id`, `rec`, `name`, `status`, `finance_entities_id`) VALUES
(1, 0, 'test', 0, 1);

--
-- Volcado de datos para la tabla `ios`
--

INSERT INTO `ios` (`id`, `rec`, `advertisers_id`, `user_id`, `name`, `offer_type`, `currency`, `budget_type`, `budget`, `model`, `bid`, `invoice_type`, `net`, `comment`, `status`, `date_start`, `date_end`) VALUES
(1, 0, 1, 1, 'test', 0, 0, 0, 0.00, 0, 0.00, 0, 0.00, '', 0, '0000-00-00', '0000-00-00');

--
-- Volcado de datos para la tabla `opportunities`
--

INSERT INTO `opportunities` (`id`, `rec`, `ios_id`, `model`, `budget`, `rate`, `carrier`, `product`, `manager_id`) VALUES
(1, 0, 1, 0, 0.00, 0.00, 'test', 'test', 2);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
