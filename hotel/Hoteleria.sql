CREATE DATABASE  IF NOT EXISTS `hoteleria` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `hoteleria`;
-- MySQL dump 10.13  Distrib 5.7.9, for Win64 (x86_64)
--
-- Host: localhost    Database: hoteleria
-- ------------------------------------------------------
-- Server version	5.6.26

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `articulo`
--

DROP TABLE IF EXISTS `articulo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `articulo` (
  `id_articulo` int(11) NOT NULL,
  `descripcion` varchar(45) DEFAULT NULL,
  `precio` decimal(18,0) DEFAULT NULL,
  `cant_disponible` int(11) DEFAULT NULL,
  `cant_minima` int(11) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  PRIMARY KEY (`id_articulo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articulo`
--

LOCK TABLES `articulo` WRITE;
/*!40000 ALTER TABLE `articulo` DISABLE KEYS */;
/*!40000 ALTER TABLE `articulo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cliente`
--

DROP TABLE IF EXISTS `cliente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cliente` (
  `id_cliente` int(9) NOT NULL,
  `id_tipo_doc` int(2) NOT NULL,
  `nombre` varchar(20) DEFAULT NULL,
  `apellido1` varchar(20) DEFAULT NULL,
  `apellido2` varchar(20) DEFAULT NULL,
  `telefono` int(8) DEFAULT NULL,
  `correo` varchar(45) DEFAULT NULL,
  `publicidad` bit(1) DEFAULT NULL COMMENT '0=no y 1=si',
  PRIMARY KEY (`id_cliente`,`id_tipo_doc`),
  KEY `fk_tipo_doc_cliente_idx` (`id_tipo_doc`),
  CONSTRAINT `fk_tipo_doc_cliente` FOREIGN KEY (`id_tipo_doc`) REFERENCES `tipo_documento` (`id_tipo_doc`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente`
--

LOCK TABLES `cliente` WRITE;
/*!40000 ALTER TABLE `cliente` DISABLE KEYS */;
/*!40000 ALTER TABLE `cliente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_fac_articulo`
--

DROP TABLE IF EXISTS `detalle_fac_articulo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detalle_fac_articulo` (
  `id_detalle_factura` int(11) NOT NULL,
  `id_factura` int(11) NOT NULL,
  `id_articulo` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio` decimal(18,0) DEFAULT NULL,
  PRIMARY KEY (`id_detalle_factura`,`id_factura`,`id_articulo`),
  KEY `fk_fac_articulo_idx` (`id_articulo`),
  CONSTRAINT `fk_fac_articulo` FOREIGN KEY (`id_articulo`) REFERENCES `articulo` (`id_articulo`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_fac_articulo`
--

LOCK TABLES `detalle_fac_articulo` WRITE;
/*!40000 ALTER TABLE `detalle_fac_articulo` DISABLE KEYS */;
/*!40000 ALTER TABLE `detalle_fac_articulo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_factura`
--

DROP TABLE IF EXISTS `detalle_factura`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detalle_factura` (
  `id_detalle_factura` int(11) NOT NULL,
  `id_factura` int(11) NOT NULL,
  `id_habitacion` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `id_adicional` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio` decimal(8,2) DEFAULT NULL,
  PRIMARY KEY (`id_detalle_factura`,`id_factura`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_factura`
--

LOCK TABLES `detalle_factura` WRITE;
/*!40000 ALTER TABLE `detalle_factura` DISABLE KEYS */;
/*!40000 ALTER TABLE `detalle_factura` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `equipo`
--

DROP TABLE IF EXISTS `equipo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `equipo` (
  `id_equipo` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(45) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_equipo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `equipo`
--

LOCK TABLES `equipo` WRITE;
/*!40000 ALTER TABLE `equipo` DISABLE KEYS */;
/*!40000 ALTER TABLE `equipo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `equipo_habitacion`
--

DROP TABLE IF EXISTS `equipo_habitacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `equipo_habitacion` (
  `id_equipo_hab` int(11) NOT NULL,
  `id_habitacion` int(11) NOT NULL,
  PRIMARY KEY (`id_equipo_hab`,`id_habitacion`),
  KEY `fk_hab_idx` (`id_habitacion`),
  CONSTRAINT `fk_hab` FOREIGN KEY (`id_habitacion`) REFERENCES `habitacion` (`id_habitacion`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_hab_equipo` FOREIGN KEY (`id_equipo_hab`) REFERENCES `equipo` (`id_equipo`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `equipo_habitacion`
--

LOCK TABLES `equipo_habitacion` WRITE;
/*!40000 ALTER TABLE `equipo_habitacion` DISABLE KEYS */;
/*!40000 ALTER TABLE `equipo_habitacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `extra`
--

DROP TABLE IF EXISTS `extra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `extra` (
  `id_extra` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(45) DEFAULT NULL,
  `precio` decimal(18,2) DEFAULT NULL,
  PRIMARY KEY (`id_extra`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `extra`
--

LOCK TABLES `extra` WRITE;
/*!40000 ALTER TABLE `extra` DISABLE KEYS */;
/*!40000 ALTER TABLE `extra` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `extra_habitacion`
--

DROP TABLE IF EXISTS `extra_habitacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `extra_habitacion` (
  `id_extra_hab` int(11) NOT NULL,
  `id_habitacion` int(11) NOT NULL,
  PRIMARY KEY (`id_extra_hab`,`id_habitacion`),
  KEY `fk_hab_idx` (`id_habitacion`),
  KEY `fk_hab_ide` (`id_habitacion`),
  CONSTRAINT `fk_extra` FOREIGN KEY (`id_extra_hab`) REFERENCES `extra` (`id_extra`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_hab_extra` FOREIGN KEY (`id_habitacion`) REFERENCES `habitacion` (`id_habitacion`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `extra_habitacion`
--

LOCK TABLES `extra_habitacion` WRITE;
/*!40000 ALTER TABLE `extra_habitacion` DISABLE KEYS */;
/*!40000 ALTER TABLE `extra_habitacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `factura_articulo`
--

DROP TABLE IF EXISTS `factura_articulo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `factura_articulo` (
  `id_factura_articulo` int(11) NOT NULL,
  `fecha_emision` date DEFAULT NULL,
  `fecha_cancelacion` date DEFAULT NULL,
  `subtotal` decimal(18,0) DEFAULT NULL,
  `impuesto` decimal(18,0) DEFAULT NULL,
  `total` decimal(18,0) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_factura_articulo`),
  KEY `fk_fact_art_usu_idx` (`id_usuario`),
  CONSTRAINT `fk_fact_art_usu` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `factura_articulo`
--

LOCK TABLES `factura_articulo` WRITE;
/*!40000 ALTER TABLE `factura_articulo` DISABLE KEYS */;
/*!40000 ALTER TABLE `factura_articulo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `factura_reservacion`
--

DROP TABLE IF EXISTS `factura_reservacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `factura_reservacion` (
  `id_factura` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `fecha_reservacion` date DEFAULT NULL,
  `fecha_retiro` date DEFAULT NULL,
  `subtotal` decimal(18,0) DEFAULT NULL,
  `descuento` decimal(18,0) DEFAULT NULL,
  `impuesto` decimal(18,0) DEFAULT NULL,
  `total` decimal(18,0) DEFAULT NULL,
  `id_tipo_pago` int(11) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `fecha_emision` date DEFAULT NULL,
  `fecha_cancelacion` date DEFAULT NULL,
  `tipo_moneda` varchar(5) DEFAULT NULL,
  `cant_noches` int(11) DEFAULT NULL,
  `observaciones` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_factura`),
  KEY `fk_fac_cliente_idx` (`id_cliente`),
  KEY `fk_fac_tipo_pago_idx` (`id_tipo_pago`),
  CONSTRAINT `fk_fac_cliente` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_fac_tipo_pago` FOREIGN KEY (`id_tipo_pago`) REFERENCES `tipo_pago` (`id_tipo_pago`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `factura_reservacion`
--

LOCK TABLES `factura_reservacion` WRITE;
/*!40000 ALTER TABLE `factura_reservacion` DISABLE KEYS */;
/*!40000 ALTER TABLE `factura_reservacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `habitacion`
--

DROP TABLE IF EXISTS `habitacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `habitacion` (
  `id_habitacion` int(11) NOT NULL AUTO_INCREMENT,
  `id_tipo_hab` int(11) DEFAULT NULL,
  `estado` bit(1) DEFAULT NULL COMMENT '0=desabilitado, 1=habilidado',
  PRIMARY KEY (`id_habitacion`),
  KEY `fk_tipo_hab_idx` (`id_tipo_hab`),
  CONSTRAINT `fk_tipo_hab` FOREIGN KEY (`id_tipo_hab`) REFERENCES `tipo_habitacion` (`id_tipo_hab`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `habitacion`
--

LOCK TABLES `habitacion` WRITE;
/*!40000 ALTER TABLE `habitacion` DISABLE KEYS */;
/*!40000 ALTER TABLE `habitacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modulo`
--

DROP TABLE IF EXISTS `modulo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `modulo` (
  `id_modulo` int(11) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  PRIMARY KEY (`id_modulo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modulo`
--

LOCK TABLES `modulo` WRITE;
/*!40000 ALTER TABLE `modulo` DISABLE KEYS */;
/*!40000 ALTER TABLE `modulo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `perfil`
--

DROP TABLE IF EXISTS `perfil`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perfil` (
  `id_perfil` int(11) NOT NULL,
  `id_permiso` int(11) NOT NULL,
  `descripcion` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_perfil`,`id_permiso`),
  KEY `fk_per_perf_idx` (`id_permiso`),
  CONSTRAINT `fk_per_perf` FOREIGN KEY (`id_permiso`) REFERENCES `permiso` (`id_permiso`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perfil`
--

LOCK TABLES `perfil` WRITE;
/*!40000 ALTER TABLE `perfil` DISABLE KEYS */;
/*!40000 ALTER TABLE `perfil` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permiso`
--

DROP TABLE IF EXISTS `permiso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permiso` (
  `id_permiso` int(11) NOT NULL,
  `id_modulo` int(11) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  PRIMARY KEY (`id_permiso`),
  KEY `fk_per_mod_idx` (`id_modulo`),
  CONSTRAINT `fk_per_mod` FOREIGN KEY (`id_modulo`) REFERENCES `modulo` (`id_modulo`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permiso`
--

LOCK TABLES `permiso` WRITE;
/*!40000 ALTER TABLE `permiso` DISABLE KEYS */;
/*!40000 ALTER TABLE `permiso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_documento`
--

DROP TABLE IF EXISTS `tipo_documento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipo_documento` (
  `id_tipo_doc` int(2) NOT NULL,
  `descripcion` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_tipo_doc`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_documento`
--

LOCK TABLES `tipo_documento` WRITE;
/*!40000 ALTER TABLE `tipo_documento` DISABLE KEYS */;
/*!40000 ALTER TABLE `tipo_documento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_habitacion`
--

DROP TABLE IF EXISTS `tipo_habitacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipo_habitacion` (
  `id_tipo_hab` int(2) NOT NULL,
  `descripcion` varchar(45) DEFAULT NULL,
  `precio_noche` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_tipo_hab`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_habitacion`
--

LOCK TABLES `tipo_habitacion` WRITE;
/*!40000 ALTER TABLE `tipo_habitacion` DISABLE KEYS */;
/*!40000 ALTER TABLE `tipo_habitacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_pago`
--

DROP TABLE IF EXISTS `tipo_pago`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipo_pago` (
  `id_tipo_pago` int(11) NOT NULL,
  `descripcion` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_tipo_pago`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_pago`
--

LOCK TABLES `tipo_pago` WRITE;
/*!40000 ALTER TABLE `tipo_pago` DISABLE KEYS */;
/*!40000 ALTER TABLE `tipo_pago` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `id_perfil` int(11) NOT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  `clave` varchar(45) DEFAULT NULL,
  `estado` varchar(45) DEFAULT NULL,
  `fecha_creacion` date DEFAULT NULL,
  PRIMARY KEY (`id_usuario`,`id_perfil`),
  KEY `fk_usu_per_idx` (`id_perfil`),
  CONSTRAINT `fk_usu_per` FOREIGN KEY (`id_perfil`) REFERENCES `perfil` (`id_perfil`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-03-21  0:39:37
