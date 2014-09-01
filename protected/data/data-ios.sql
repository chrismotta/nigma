-- phpMyAdmin SQL Dump
-- version 3.5.8.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 29, 2014 at 11:19 AM
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
-- Dumping data for table `ios`
--

INSERT INTO `ios` (`id`, `name`, `commercial_name`, `status`, `address`, `country_id`, `state`, `zip_code`, `phone`, `contact_com`, `email_com`, `contact_adm`, `email_adm`, `currency`, `ret`, `tax_id`, `commercial_id`, `entity`, `net_payment`, `advertisers_id`, `pdf_name`) VALUES
(1, 'Adsmonster', 'Adsmonster Ltd', 10, '16/5 West Pilton Rise', 2826, 'Edinburgh', '', '', 'Isla', 'isla@tweemedia.com', '', '', 'USD', '', '', 4, 'LLC', '30', 1, ''),
(2, 'Airmovil', 'Airmovil Sa De Cv', 10, 'San Francisco 761, Col Del Valle- Delegacion Benito Juarez', 2484, 'Df', '', '', 'Antonio Randolph', 'antonio.randolph@airmovil.com', 'Luis Piek', 'luis.piek@airmovil.com', 'USD', '', 'RFC: AIR030516ID1', 4, 'LLC', '30', 2, ''),
(3, 'Aspire Global', 'Neopoint Technologies Ltd', 10, '50 Town Range Road', 2826, 'Gibraltar', '', '', 'Dana F', 'danaf@aspireglobal.com', 'Dana F', 'danaf@aspireglobal.com', 'USD', '', 'Registration number: 9011', 4, 'LLC', '30', 3, ''),
(4, 'Barons Media', 'Barons Media', 10, 'Address 4111 W. Alameda Ave Suite 501', 2840, 'Burbank', '', '', 'Ivan', 'ivan@baronsmedia.com', 'Ivan', 'ivan@baronsmedia.com', 'USD', '', '', 4, 'LLC', '30', 4, ''),
(5, 'Beleader', 'Beleader Internet Marketing Sl', 10, 'Avenida Victoria 25 2° Planta Of 14', 2724, 'Madrid', '', '', 'Patricia', 'patricia@beleader.com', 'Patricia', 'patricia@beleader.com', 'USD', '', 'CIF B85218154', 4, 'LLC', '30', 5, ''),
(6, 'Blinker', 'Blinker Media Consulting S.A', 10, 'Gral Lemos 260 Piso 7 Dto 31', 2032, 'Buenos Aires', '', '', 'M Alvarez', 'malvarez@blinkerad.com', 'Blinker', 'blinker.ordendecompra@gmail.com', 'ARS', '', 'CUIT: 30-71012887-8', 4, 'SRL', '30', 6, ''),
(7, 'Bucksense', 'Bucksense_Me!', 10, '', 2840, 'Nevada', '', '', 'Luigi Diodato', 'luigi.diodato@bucksense.com', 'Billing', 'billing@bucksense.com!', 'USD', '', 'ID:!4538681537', 4, 'LLC', '30', 7, ''),
(8, 'Buongiorno Ar', 'Axis Mundi Sa', 10, 'Humboldt 2495, Piso 6 B', 2032, 'Buenos Aires', '', '', 'Carolina Falcone', 'carolina.falcone@buongiorno.com', 'Mariapaz Oldani', 'mariapaz.oldani@buongiorno.com', 'ARS', '', '30-70836473-4', 4, 'SRL', '30', 8, ''),
(9, 'Chocolate', 'Chocolate Movil Spa', 10, 'Av. Cristóbal Colon 6120, Las Condes', 2152, 'Santiago', '', '', 'Anders', 'anders@chocolatemobile.co.uk', 'Consuelo', 'consuelo@chocolatemobile.co.uk', 'USD', '', '76.261.879-6', 4, 'LLC', '30', 9, ''),
(10, 'Concepto Movil', 'Concepto Movil Sa De Cv', 10, 'Shakespeare 39 301, Col. Anzures', 2484, 'Df', '', '', 'Compras', 'compras@conceptomovil.com', 'Emmanuel Dominguez', 'emmanuel.dominguez@conceptomovil.com', 'USD', '', 'RFC: CMO-080718-M21', 4, 'LLC', '30', 10, ''),
(11, 'Froggie', 'Froggie Sl', 10, 'C/Arquitectura N°2 , Torre 11, Planta 7', 2724, 'Sevilla', '', '', 'Belinda Munoz', 'belindamunoz@froggie-mm.com', 'Maribel Godoy', 'maribelgodoy@froggie-mm.com', 'USD', '', 'CIF:91109454', 4, 'LLC', '30', 13, ''),
(12, 'Glispa', 'Glispa Gmbh', 10, 'Sophienstrasse 21', 2276, 'Berlin', '', '', 'Rafael Carvalho', 'rafael.carvalho@glispamedia.com', 'Rafael Carvalho', 'rafael.carvalho@glispamedia.com', 'USD', '', 'DE814998388', 4, 'LLC', '30', 14, ''),
(13, 'Inovacel', 'Consultores En Servicios Fiscales Y Contable Del Valle Sa De Cv', 10, 'Epigmenio Garcia N° 103 Col. Valle De Vasconcelos', 2484, 'Df', '', '', 'Miguel Mares', 'miguel.mares@inovacel.com', 'Alejandra', 'alejandra@inovacel.com', 'USD', '', 'CSF 120806 4I4', 4, 'LLC', '30', 15, ''),
(14, 'Jet It', 'Digital Virgo Entertainment', 10, '350 Rue Denis Papin 13100 Aix En Provence', 2250, 'Paris', '', '902010201', 'Nicolas Bulle', 'nbulle@digitalvirgo.com', '', '', 'USD', '', 'VAT NUMBER: FR 80 430 325 811', 4, 'LLC', '30', 16, ''),
(15, 'Jet Ar', 'Digital Virgo Argentina Sa', 10, 'Av. Juan B. Justo 637, Piso 7', 2032, 'Buenos Aires', '', '', 'S Ramirez', 'sramirez@jetmultimedia.com', '', '', 'ARS', '', '30-70862740-9', 4, 'SRL', '30', 16, ''),
(16, 'Jet Br', 'Jet Multimedia España, S.A.', 10, 'Parque Empresarial Cristalia Edificio 5, Planta 4ª, Via De Los Poblados, 3', 2724, 'Madrid', '', '', 'S Rolli', 'srolli@digitalvirgo.com', 'F Sortega', 'fsortega@jetmultimedia.es', 'USD', '', 'ESA80060924', 4, 'LLC', '30', 16, ''),
(17, 'Joker', 'Joker Mobile S.A', 10, 'Vuelta De Obligado 1947 Piso 8 – Cap Fed', 2032, 'Buenos Aires', '', '', 'T Cohen', 'tcohen@unlimiteddistribution.biz', 'M Torres', 'mtorres@unlimiteddistribution.biz', 'ARS', '', '33-71209976-9', 4, 'SRL', '45', 17, ''),
(18, 'Mad4mobile', 'Mad4mobile Limited', 10, 'Advantage Business Center 132-134 Great Ancoats Street', 2826, 'Manchester', '', '', 'James', 'james@mad4mobile.com', '', '', 'USD', '', 'VAT NUMBER: 116732133', 4, 'LLC', '30', 18, ''),
(19, 'Mcr-M', 'Cellon Ltd', 10, 'Hayetzira 29, Ramat Gan', 2376, 'Ramat Gan', '', '', 'Ariana', 'ariana@mcr-m.com', 'Nir', 'nir@mcr-m.com', 'USD', '', 'VAT: 514235852', 4, 'LLC', '30', 19, ''),
(20, 'Mobile Streams Ar', 'Mobile Streams De Argentina Srl', 10, 'Talcahuano 833 Piso 9 “E”- Capital Federal', 2032, 'Buenos Aires', '', '', 'Emiliano D', 'emilianod@mobilestreams.com', 'Emiliano D', 'emilianod@mobilestreams.com', 'ARS', '', '33-70881800-9', 4, 'SRL', '30', 21, ''),
(21, 'Mobile Streams Row', 'Mobile Streams Of México S De Rl De Cv', 10, 'Lateral Autopista Mexico Toluca #1235 Oficina 404', 2484, 'Df', '', '', 'Emiliano D', 'emilianod@mobilestreams.com', 'Emiliano D', 'emilianod@mobilestreams.com', 'USD', '', 'RFC: MSM040617 RT0', 4, 'LLC', '', 21, ''),
(22, 'Movile', 'Cyclelogic De Mexico S De Rl De Cv', 10, 'Av. Del Prado Sur 140, Piso 3, Lomas De Chapulquetec Iv Seccion, Del Miguel Hidalgo', 2484, 'Df', '', '', 'Vanessa Rocha', 'vanessa.rocha@movile.com', 'Rosaura Perez', 'rosaura.perez@movile.com', 'USD', '', 'RFC: CME30325CPA', 4, 'LLC', '30', 22, ''),
(23, 'Neomobile Co', 'Neomobile Colombia S.A.S.', 10, 'Calle 93 #18-12, Oficina 206', 2170, 'Bogota', '', '', 'Xamir Castelblanco', 'xamir.castelblanco@neomobile.com', 'Andrea Grijalba', 'andrea.grijalba@neomobile.com', 'USD', '', 'NIT 9006041543', 4, 'LLC', '30', 23, ''),
(24, 'Neomobile Ec', 'Neomobilecuador S.A.', 10, 'Av. Republica Del Salvador 1082 Y Naciones Unidas, Edificio Mansion Blanca Torre Paris', 2218, 'Quito', '', '', 'Xamir Castelblanco', 'xamir.castelblanco@neomobile.com', 'Andrea Grijalba', 'andrea.grijalba@neomobile.com', 'USD', '', 'RUC:1792394910001', 4, 'LLC', '30', 23, ''),
(25, 'Neomobile Pe', 'Neomobile Peru S.A.C', 10, 'Av. Víctor Andrés Belaúnde 147, Vía Principal 110 Edificio Real Cinco, Oficina 901', 2604, 'Lima', '', '', 'Xamir Castelblanco', 'xamir.castelblanco@neomobile.com', 'Ruth Navarro', 'ruth.navarro@neomobile.com', 'USD', '', 'RUC: 20550996180', 4, 'LLC', '30', 23, ''),
(26, 'Olx', 'Olx Inc', 10, '', 2840, 'Ny', '', '', 'Isabelli Thille', 'isabelli.thille@olx.com', 'Federico Vazquez', 'federico.vazquez@olx.com', 'USD', '', '20-452-1118', 4, 'LLC', '30', 24, ''),
(27, 'Pedidosya', 'Pedidosya Sa', 10, 'Coronel Diaz 1811 Piso 1', 2032, 'Buenos Aires', '', '', 'Valeria G', 'valeriag@pedidosya.com', 'Marinan', 'marinan@pedidosya.com', 'ARS', '', 'CUIT 30711985766', 4, 'SRL', '30', 25, ''),
(28, 'Playphone', 'Playphone Inc', 10, '345 S B St. San Mateo', 2840, 'California', '', '', 'Ray', 'ray@playphone.com', 'Ap', 'ap@playphone.com', 'USD', '', 'TAX ID: 56-2116077', 4, 'LLC', '30', 26, ''),
(29, 'Playtown', 'Playtown Sa', 10, 'Lavalle 636 Piso 1 Oficina 7', 2032, 'Buenos Aires', '', '', 'Florencia Escandon', 'florencia.escandon@playtown.com.ar', 'Luis Maggi', 'luis.maggi@playtown.com.ar', 'ARS', '', '30711518857', 4, 'SRL', '60', 27, ''),
(30, 'Plus Mobile', 'Plus Mobile Communication Sa', 10, 'Talcahuano 718', 2032, 'Buenos Aires', '', '', 'Laura Contarino', 'laura.contarino@plusmobile.com', 'Maria Mendizabal', 'maria.mendizabal@plusmobile.com', 'ARS', '', '30-70721407-0', 4, 'SRL', '30', 28, ''),
(31, 'Pmovil Ar', 'Pmovil Argentina Sa', 10, 'Aguirre 1501 – Pb', 2032, 'Buenos Aires', '', '', 'Micaela', 'micaela@pmovil.com.ar', 'Alejandro', 'alejandro@pmovil.com.ar', 'ARS', '', '30-70761582-2', 4, 'SRL', '30', 30, ''),
(32, 'Pmovil Row', 'Instinex Sa', 10, 'Coronel Brandsen 1961 Of Ep103', 2858, 'Montevideo', '', '', 'Micaela', 'micaela@pmovil.com.ar', 'Alejandro', 'alejandro@pmovil.com.ar', 'USD', '', '214711790011', 4, 'LLC', '30', 30, ''),
(33, 'Renxo Ar', 'Renxo Sa', 10, 'Av. Caseros 3405, Piso 4', 2032, 'Buenos Aires', '', '', 'Mauro', 'mauro@vanega.com', 'Finanzas', 'finanzas@renxo.com', 'ARS', '', '30-69915299-0', 4, 'SRL', '30', 31, ''),
(34, 'Renxo Row', 'Renxo Sa', 10, 'Av. Caseros 3405, Piso 4', 2032, 'Buenos Aires', '', '', 'Mauro', 'mauro@vanega.com', 'Finanzas', 'finanzas@renxo.com', 'USD', '', '30-69915299-0', 4, 'LLC', '30', 31, ''),
(35, 'Restorando', 'Restorando Sa', 10, 'Vuelta De Obligado 1947 5to', 2032, 'Buenos Aires', '', '', 'Guillermo Pendola', 'guillermo.pendola@restorando.com', 'Billing', 'billing@restorando.com', 'ARS', '', 'CUIT: 30-71169630-6', 4, 'SRL', '30', 32, ''),
(36, 'Sony Pictures', 'Sony Pictures Releasing Argentina S.R.L', 10, 'José A. Cabrera 6027 - C1414bhm', 2032, 'Buenos Aires', '', '', 'Lucia Ceballos', 'lucia_ceballos@spe.sony.com', 'Maria Valletta', 'maria_valletta@spe.sony.com', 'ARS', '', 'CUIT: 30-71067240-3', 4, 'SRL', '30', 33, ''),
(37, 'Taplink', 'Dada Ententermeint Inc', 10, '205 Lexington Ave. 12th Floor', 2840, 'Ny', '', '', '', '', '', '', 'USD', '', 'TAX ID: 13-4090548', 4, 'LLC', '', 35, ''),
(38, 'Tekka', 'Tekka Srl', 10, 'Lungodora Colletta 67', 2380, 'Torino', '', '', 'Simonetta Capaldi', '', 'S Capaldi', 's.capaldi@tekka.it', '', '', '', 4, '', '', 36, ''),
(39, 'Telinfor', 'Telinfor Sa', 10, 'Laprida 1024 - Pb', 2032, 'Buenos Aires', '', '', 'E Gallardo', 'egallardo@telinfor.com', 'N Gomez', 'ngomez@telinfor.com', 'ARS', '', '30-67773938-6', 4, 'SRL', '30', 37, ''),
(40, 'Terra Co', 'Marktech.Biz Sa De Cv', 10, 'Cto. Guillermo González Camarena 1450 Piso 2 -', 2484, 'Santa Fe', '', '', 'Roberto Sauceda', 'roberto.sauceda@corp.terra.com.mx', 'Finanzas', 'finanzas@mobile360.biz', 'USD', '', 'MAR1110194V0', 4, 'LLC', '30', 38, ''),
(41, 'Terra Mx', 'Comercializadora Especializada Retía, Sa. De Cv', 10, '', 2484, 'Toluca', '', '', 'Roberto Sauceda', 'roberto.sauceda@corp.terra.com.mx', '', '', 'USD', '', 'CER090729 DK0', 4, 'LLC', '30', 38, ''),
(42, 'Timwe Ar, Cl', 'Total Tim Argentina Sa', 10, 'Olga Cossenttini 1545, Piso 3', 2032, 'Buenos Aires', '', '', 'Ivo Antunes', 'ivo.antunes@timwe.com', '', '', 'ARS', '', '30-70911973-3', 4, 'SRL', '30', 39, ''),
(43, 'Timwe Br', 'Total Spin Brasil Serviços De Telecomunicações Ltda', 10, '', 2076, 'San Pablo', '', '', 'Cristina Melo', 'cristina.melo@timwe.com', '', '', 'USD', '', 'C.N.P.J.: 08.462.355/0001-04', 4, 'LLC', '30', 39, ''),
(44, 'Timwe Row', 'Timwe', 10, 'Olga Cossettini 1545 3° Piso Norte', 2032, 'Buenos Aires', '', '', 'Ivo Antunes', 'ivo.antunes@timwe.com', 'Carolina Ribeiro', 'carolina.ribeiro@timwe.com.', 'ARS', '', '30-70911973-3', 4, 'SRL', '30', 39, ''),
(45, 'Way Media', 'Be Good Srl', 10, 'Larrea 1106, Piso 5 "B"', 2032, 'Buenos Aires', '', '', 'Agustin', 'agustin@waymedia.mobi', '', '', 'ARS', '', '30-70173592-3', 4, 'SRL', '30', 40, ''),
(56, 'Digital 6', '', 0, '', NULL, '', '', '', '', '', '', '', '', '', '', 4, '', '', 11, ''),
(57, 'Engage', '', 0, '', NULL, '', '', '', '', '', '', '', '', '', '', 4, '', '', 12, ''),
(58, 'Mobile Fuse', '', 0, '', NULL, '', '', '', '', '', '', '', '', '', '', 4, '', '', 20, ''),
(59, 'Ply Media', '', 0, '', NULL, '', '', '', '', '', '', '', '', '', '', 4, '', '', 29, ''),
(60, 'Spike Media', '', 0, '', NULL, '', '', '', '', '', '', '', '', '', '', 4, '', '', 34, '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
