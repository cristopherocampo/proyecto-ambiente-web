-- MySQL dump 10.13  Distrib 8.0.46, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: bookcycle
-- ------------------------------------------------------
-- Server version	8.0.46

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Dumping data for table `instituciones`
--

LOCK TABLES `instituciones` WRITE;
/*!40000 ALTER TABLE `instituciones` DISABLE KEYS */;
INSERT INTO `instituciones` (`id`, `nombre`, `dominio_correo`, `activo`) VALUES (1,'Universidad Fidélitas','ufidelitas.ac.cr',1);
/*!40000 ALTER TABLE `instituciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `carreras`
--

LOCK TABLES `carreras` WRITE;
/*!40000 ALTER TABLE `carreras` DISABLE KEYS */;
INSERT INTO `carreras` (`id`, `institucion_id`, `nombre`, `activo`) VALUES (1,1,'Ingeniería en Sistemas',1);
/*!40000 ALTER TABLE `carreras` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `cursos`
--

LOCK TABLES `cursos` WRITE;
/*!40000 ALTER TABLE `cursos` DISABLE KEYS */;
INSERT INTO `cursos` (`id`, `carrera_id`, `codigo`, `nombre`, `activo`) VALUES (1,1,'SC-101','Programación I',1);
/*!40000 ALTER TABLE `cursos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `categorias`
--

LOCK TABLES `categorias` WRITE;
/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;
INSERT INTO `categorias` (`id`, `nombre`, `descripcion`, `activo`) VALUES (1,'Tecnología','Computación, programación y tecnología',1),(2,'Ciencias','Ciencias básicas y aplicadas',1),(3,'Humanidades','Letras, idiomas y ciencias sociales',1),(4,'Negocios','Administración, economía y contabilidad',1);
/*!40000 ALTER TABLE `categorias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tipos_material`
--

LOCK TABLES `tipos_material` WRITE;
/*!40000 ALTER TABLE `tipos_material` DISABLE KEYS */;
INSERT INTO `tipos_material` (`id`, `nombre`) VALUES (3,'APUNTES'),(2,'GUIA'),(1,'LIBRO'),(4,'OTRO');
/*!40000 ALTER TABLE `tipos_material` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `estados_fisicos`
--

LOCK TABLES `estados_fisicos` WRITE;
/*!40000 ALTER TABLE `estados_fisicos` DISABLE KEYS */;
INSERT INTO `estados_fisicos` (`id`, `nombre`) VALUES (4,'ACEPTABLE'),(3,'BUENO'),(2,'MUY BUENO'),(1,'NUEVO');
/*!40000 ALTER TABLE `estados_fisicos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `estados_publicacion`
--

LOCK TABLES `estados_publicacion` WRITE;
/*!40000 ALTER TABLE `estados_publicacion` DISABLE KEYS */;
INSERT INTO `estados_publicacion` (`id`, `nombre`) VALUES (1,'BORRADOR'),(2,'DISPONIBLE'),(5,'INACTIVA'),(4,'INTERCAMBIADA'),(3,'RESERVADA');
/*!40000 ALTER TABLE `estados_publicacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `modalidades`
--

LOCK TABLES `modalidades` WRITE;
/*!40000 ALTER TABLE `modalidades` DISABLE KEYS */;
INSERT INTO `modalidades` (`id`, `nombre`) VALUES (3,'AMBOS'),(2,'CREDITOS'),(1,'TRUEQUE');
/*!40000 ALTER TABLE `modalidades` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `estados_usuario`
--

LOCK TABLES `estados_usuario` WRITE;
/*!40000 ALTER TABLE `estados_usuario` DISABLE KEYS */;
INSERT INTO `estados_usuario` (`id`, `nombre`) VALUES (2,'ACTIVO'),(4,'ELIMINADO'),(1,'PENDIENTE'),(3,'SUSPENDIDO');
/*!40000 ALTER TABLE `estados_usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` (`id`, `nombre`) VALUES (2,'ADMINISTRADOR'),(1,'ESTUDIANTE');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tipos_movimiento`
--

LOCK TABLES `tipos_movimiento` WRITE;
/*!40000 ALTER TABLE `tipos_movimiento` DISABLE KEYS */;
INSERT INTO `tipos_movimiento` (`id`, `nombre`) VALUES (4,'AJUSTE'),(1,'BONIFICACION'),(2,'CREDITO POR INTERCAMBIO'),(3,'DEBITO POR INTERCAMBIO');
/*!40000 ALTER TABLE `tipos_movimiento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tipos_notificacion`
--

LOCK TABLES `tipos_notificacion` WRITE;
/*!40000 ALTER TABLE `tipos_notificacion` DISABLE KEYS */;
INSERT INTO `tipos_notificacion` (`id`, `nombre`) VALUES (3,'COINCIDENCIA'),(4,'CREDITO'),(5,'INTERCAMBIO'),(2,'MENSAJE'),(6,'SISTEMA'),(1,'SOLICITUD');
/*!40000 ALTER TABLE `tipos_notificacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `estados_intercambio`
--

LOCK TABLES `estados_intercambio` WRITE;
/*!40000 ALTER TABLE `estados_intercambio` DISABLE KEYS */;
INSERT INTO `estados_intercambio` (`id`, `nombre`) VALUES (1,'ACORDADO'),(5,'DISPUTADO'),(2,'EN CAMINO'),(4,'FINALIZADO'),(3,'RECIBIDO');
/*!40000 ALTER TABLE `estados_intercambio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `estados_reporte`
--

LOCK TABLES `estados_reporte` WRITE;
/*!40000 ALTER TABLE `estados_reporte` DISABLE KEYS */;
INSERT INTO `estados_reporte` (`id`, `nombre`) VALUES (1,'ABIERTO'),(4,'DESCARTADO'),(2,'EN REVISION'),(3,'RESUELTO');
/*!40000 ALTER TABLE `estados_reporte` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `estados_solicitud`
--

LOCK TABLES `estados_solicitud` WRITE;
/*!40000 ALTER TABLE `estados_solicitud` DISABLE KEYS */;
INSERT INTO `estados_solicitud` (`id`, `nombre`) VALUES (2,'ACEPTADA'),(4,'CANCELADA'),(6,'COMPLETADA'),(5,'EN PROCESO'),(1,'PENDIENTE'),(3,'RECHAZADA');
/*!40000 ALTER TABLE `estados_solicitud` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `puntos_encuentro`
--

LOCK TABLES `puntos_encuentro` WRITE;
/*!40000 ALTER TABLE `puntos_encuentro` DISABLE KEYS */;
/*!40000 ALTER TABLE `puntos_encuentro` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-08-04 18:30:43
