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
-- Current Database: `bookcycle`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `bookcycle` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `bookcycle`;

--
-- Table structure for table `autores`
--

DROP TABLE IF EXISTS `autores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `autores` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(180) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_autores_nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `carreras`
--

DROP TABLE IF EXISTS `carreras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `carreras` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `institucion_id` int unsigned NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_carreras` (`institucion_id`,`nombre`),
  CONSTRAINT `fk_carreras_institucion` FOREIGN KEY (`institucion_id`) REFERENCES `instituciones` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `categorias`
--

DROP TABLE IF EXISTS `categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categorias` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `conversacion_participantes`
--

DROP TABLE IF EXISTS `conversacion_participantes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `conversacion_participantes` (
  `conversacion_id` int unsigned NOT NULL,
  `usuario_id` int unsigned NOT NULL,
  `fecha_union` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_ultima_lectura` datetime DEFAULT NULL,
  PRIMARY KEY (`conversacion_id`,`usuario_id`),
  KEY `fk_participantes_usuario` (`usuario_id`),
  CONSTRAINT `fk_participantes_conversacion` FOREIGN KEY (`conversacion_id`) REFERENCES `conversaciones` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_participantes_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `conversaciones`
--

DROP TABLE IF EXISTS `conversaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `conversaciones` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `solicitud_id` int unsigned DEFAULT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_ultimo_mensaje` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_conversaciones_solicitud` (`solicitud_id`),
  CONSTRAINT `fk_conversaciones_solicitud` FOREIGN KEY (`solicitud_id`) REFERENCES `solicitudes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cursos`
--

DROP TABLE IF EXISTS `cursos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cursos` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `carrera_id` int unsigned NOT NULL,
  `codigo` varchar(30) DEFAULT NULL,
  `nombre` varchar(150) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_cursos` (`carrera_id`,`nombre`),
  CONSTRAINT `fk_cursos_carrera` FOREIGN KEY (`carrera_id`) REFERENCES `carreras` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `deseos`
--

DROP TABLE IF EXISTS `deseos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `deseos` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `usuario_id` int unsigned NOT NULL,
  `obra_id` int unsigned NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `fecha_registro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_deseos` (`usuario_id`,`obra_id`),
  KEY `fk_deseos_obra` (`obra_id`),
  CONSTRAINT `fk_deseos_obra` FOREIGN KEY (`obra_id`) REFERENCES `obras` (`id`),
  CONSTRAINT `fk_deseos_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `estados_fisicos`
--

DROP TABLE IF EXISTS `estados_fisicos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estados_fisicos` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(40) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `estados_intercambio`
--

DROP TABLE IF EXISTS `estados_intercambio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estados_intercambio` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(40) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `estados_publicacion`
--

DROP TABLE IF EXISTS `estados_publicacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estados_publicacion` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(40) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `estados_reporte`
--

DROP TABLE IF EXISTS `estados_reporte`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estados_reporte` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(40) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `estados_solicitud`
--

DROP TABLE IF EXISTS `estados_solicitud`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estados_solicitud` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(40) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `estados_usuario`
--

DROP TABLE IF EXISTS `estados_usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estados_usuario` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `historial_estados_intercambio`
--

DROP TABLE IF EXISTS `historial_estados_intercambio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `historial_estados_intercambio` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `intercambio_id` int unsigned NOT NULL,
  `estado_intercambio_id` int unsigned NOT NULL,
  `cambiado_por_id` int unsigned NOT NULL,
  `comentario` varchar(500) DEFAULT NULL,
  `fecha_cambio` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_hist_intercambio` (`intercambio_id`),
  KEY `fk_hist_intercambio_estado` (`estado_intercambio_id`),
  KEY `fk_hist_intercambio_usuario` (`cambiado_por_id`),
  CONSTRAINT `fk_hist_intercambio` FOREIGN KEY (`intercambio_id`) REFERENCES `intercambios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_hist_intercambio_estado` FOREIGN KEY (`estado_intercambio_id`) REFERENCES `estados_intercambio` (`id`),
  CONSTRAINT `fk_hist_intercambio_usuario` FOREIGN KEY (`cambiado_por_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `historial_estados_solicitud`
--

DROP TABLE IF EXISTS `historial_estados_solicitud`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `historial_estados_solicitud` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `solicitud_id` int unsigned NOT NULL,
  `estado_solicitud_id` int unsigned NOT NULL,
  `cambiado_por_id` int unsigned NOT NULL,
  `comentario` varchar(500) DEFAULT NULL,
  `fecha_cambio` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_hist_solicitud` (`solicitud_id`),
  KEY `fk_hist_solicitud_estado` (`estado_solicitud_id`),
  KEY `fk_hist_solicitud_usuario` (`cambiado_por_id`),
  CONSTRAINT `fk_hist_solicitud` FOREIGN KEY (`solicitud_id`) REFERENCES `solicitudes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_hist_solicitud_estado` FOREIGN KEY (`estado_solicitud_id`) REFERENCES `estados_solicitud` (`id`),
  CONSTRAINT `fk_hist_solicitud_usuario` FOREIGN KEY (`cambiado_por_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `instituciones`
--

DROP TABLE IF EXISTS `instituciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `instituciones` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) NOT NULL,
  `dominio_correo` varchar(120) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_instituciones_nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `intercambios`
--

DROP TABLE IF EXISTS `intercambios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `intercambios` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `solicitud_id` int unsigned NOT NULL,
  `estado_intercambio_id` int unsigned NOT NULL,
  `punto_encuentro_id` int unsigned DEFAULT NULL,
  `codigo_entrega` varchar(30) NOT NULL,
  `fecha_acordada` datetime DEFAULT NULL,
  `fecha_finalizacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `solicitud_id` (`solicitud_id`),
  UNIQUE KEY `codigo_entrega` (`codigo_entrega`),
  KEY `fk_intercambios_estado` (`estado_intercambio_id`),
  KEY `fk_intercambios_punto` (`punto_encuentro_id`),
  CONSTRAINT `fk_intercambios_estado` FOREIGN KEY (`estado_intercambio_id`) REFERENCES `estados_intercambio` (`id`),
  CONSTRAINT `fk_intercambios_punto` FOREIGN KEY (`punto_encuentro_id`) REFERENCES `puntos_encuentro` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_intercambios_solicitud` FOREIGN KEY (`solicitud_id`) REFERENCES `solicitudes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mensajes`
--

DROP TABLE IF EXISTS `mensajes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mensajes` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `conversacion_id` int unsigned NOT NULL,
  `remitente_id` int unsigned NOT NULL,
  `contenido` varchar(2000) NOT NULL,
  `fecha_envio` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_edicion` datetime DEFAULT NULL,
  `fecha_eliminacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_mensajes_conversacion` (`conversacion_id`),
  KEY `fk_mensajes_remitente` (`remitente_id`),
  CONSTRAINT `fk_mensajes_conversacion` FOREIGN KEY (`conversacion_id`) REFERENCES `conversaciones` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_mensajes_remitente` FOREIGN KEY (`remitente_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `modalidades`
--

DROP TABLE IF EXISTS `modalidades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `modalidades` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `movimientos_credito`
--

DROP TABLE IF EXISTS `movimientos_credito`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `movimientos_credito` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `usuario_id` int unsigned NOT NULL,
  `intercambio_id` int unsigned DEFAULT NULL,
  `tipo_movimiento_id` int unsigned NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `fecha_movimiento` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_movimientos_usuario` (`usuario_id`),
  KEY `fk_movimientos_intercambio` (`intercambio_id`),
  KEY `fk_movimientos_tipo` (`tipo_movimiento_id`),
  CONSTRAINT `fk_movimientos_intercambio` FOREIGN KEY (`intercambio_id`) REFERENCES `intercambios` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_movimientos_tipo` FOREIGN KEY (`tipo_movimiento_id`) REFERENCES `tipos_movimiento` (`id`),
  CONSTRAINT `fk_movimientos_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `ck_movimientos_monto` CHECK ((`monto` <> 0))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `notificaciones`
--

DROP TABLE IF EXISTS `notificaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notificaciones` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `usuario_id` int unsigned NOT NULL,
  `tipo_notificacion_id` int unsigned NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `mensaje` varchar(500) NOT NULL,
  `url_destino` varchar(500) DEFAULT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_lectura` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_notificaciones_usuario` (`usuario_id`),
  KEY `fk_notificaciones_tipo` (`tipo_notificacion_id`),
  CONSTRAINT `fk_notificaciones_tipo` FOREIGN KEY (`tipo_notificacion_id`) REFERENCES `tipos_notificacion` (`id`),
  CONSTRAINT `fk_notificaciones_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `obra_autores`
--

DROP TABLE IF EXISTS `obra_autores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `obra_autores` (
  `obra_id` int unsigned NOT NULL,
  `autor_id` int unsigned NOT NULL,
  `orden_autoria` int unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`obra_id`,`autor_id`),
  KEY `fk_obra_autores_autor` (`autor_id`),
  CONSTRAINT `fk_obra_autores_autor` FOREIGN KEY (`autor_id`) REFERENCES `autores` (`id`),
  CONSTRAINT `fk_obra_autores_obra` FOREIGN KEY (`obra_id`) REFERENCES `obras` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `obra_categorias`
--

DROP TABLE IF EXISTS `obra_categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `obra_categorias` (
  `obra_id` int unsigned NOT NULL,
  `categoria_id` int unsigned NOT NULL,
  PRIMARY KEY (`obra_id`,`categoria_id`),
  KEY `fk_obra_categorias_categoria` (`categoria_id`),
  CONSTRAINT `fk_obra_categorias_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`),
  CONSTRAINT `fk_obra_categorias_obra` FOREIGN KEY (`obra_id`) REFERENCES `obras` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `obra_cursos`
--

DROP TABLE IF EXISTS `obra_cursos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `obra_cursos` (
  `obra_id` int unsigned NOT NULL,
  `curso_id` int unsigned NOT NULL,
  PRIMARY KEY (`obra_id`,`curso_id`),
  KEY `fk_obra_cursos_curso` (`curso_id`),
  CONSTRAINT `fk_obra_cursos_curso` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`),
  CONSTRAINT `fk_obra_cursos_obra` FOREIGN KEY (`obra_id`) REFERENCES `obras` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `obras`
--

DROP TABLE IF EXISTS `obras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `obras` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `tipo_material_id` int unsigned NOT NULL,
  `titulo` varchar(220) NOT NULL,
  `isbn` varchar(20) DEFAULT NULL,
  `edicion` varchar(60) DEFAULT NULL,
  `editorial` varchar(150) DEFAULT NULL,
  `anio_publicacion` year DEFAULT NULL,
  `descripcion` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_obras_isbn` (`isbn`),
  KEY `fk_obras_tipo` (`tipo_material_id`),
  CONSTRAINT `fk_obras_tipo` FOREIGN KEY (`tipo_material_id`) REFERENCES `tipos_material` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `preferencias_usuario`
--

DROP TABLE IF EXISTS `preferencias_usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `preferencias_usuario` (
  `usuario_id` int unsigned NOT NULL,
  `notificar_solicitudes` tinyint(1) NOT NULL DEFAULT '1',
  `notificar_mensajes` tinyint(1) NOT NULL DEFAULT '1',
  `notificar_coincidencias` tinyint(1) NOT NULL DEFAULT '1',
  `recibir_boletin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`usuario_id`),
  CONSTRAINT `fk_preferencias_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `publicacion_fotos`
--

DROP TABLE IF EXISTS `publicacion_fotos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `publicacion_fotos` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `publicacion_id` int unsigned NOT NULL,
  `url` varchar(500) NOT NULL,
  `orden` int unsigned NOT NULL DEFAULT '1',
  `es_portada` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_publicacion_fotos` (`publicacion_id`,`orden`),
  CONSTRAINT `fk_fotos_publicacion` FOREIGN KEY (`publicacion_id`) REFERENCES `publicaciones` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `publicaciones`
--

DROP TABLE IF EXISTS `publicaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `publicaciones` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `obra_id` int unsigned NOT NULL,
  `propietario_id` int unsigned NOT NULL,
  `estado_fisico_id` int unsigned NOT NULL,
  `estado_publicacion_id` int unsigned NOT NULL,
  `modalidad_id` int unsigned NOT NULL,
  `valor_creditos` decimal(10,2) DEFAULT NULL,
  `observaciones` text,
  `fecha_publicacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_publicaciones_obra` (`obra_id`),
  KEY `fk_publicaciones_propietario` (`propietario_id`),
  KEY `fk_publicaciones_estado_fisico` (`estado_fisico_id`),
  KEY `fk_publicaciones_estado` (`estado_publicacion_id`),
  KEY `fk_publicaciones_modalidad` (`modalidad_id`),
  CONSTRAINT `fk_publicaciones_estado` FOREIGN KEY (`estado_publicacion_id`) REFERENCES `estados_publicacion` (`id`),
  CONSTRAINT `fk_publicaciones_estado_fisico` FOREIGN KEY (`estado_fisico_id`) REFERENCES `estados_fisicos` (`id`),
  CONSTRAINT `fk_publicaciones_modalidad` FOREIGN KEY (`modalidad_id`) REFERENCES `modalidades` (`id`),
  CONSTRAINT `fk_publicaciones_obra` FOREIGN KEY (`obra_id`) REFERENCES `obras` (`id`),
  CONSTRAINT `fk_publicaciones_propietario` FOREIGN KEY (`propietario_id`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `ck_publicaciones_creditos` CHECK (((`valor_creditos` is null) or (`valor_creditos` >= 0)))
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `puntos_encuentro`
--

DROP TABLE IF EXISTS `puntos_encuentro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `puntos_encuentro` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `institucion_id` int unsigned NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `ubicacion` varchar(255) DEFAULT NULL,
  `instrucciones` varchar(500) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_puntos_encuentro` (`institucion_id`,`nombre`),
  CONSTRAINT `fk_puntos_institucion` FOREIGN KEY (`institucion_id`) REFERENCES `instituciones` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `reportes`
--

DROP TABLE IF EXISTS `reportes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reportes` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `reportante_id` int unsigned NOT NULL,
  `usuario_reportado_id` int unsigned DEFAULT NULL,
  `publicacion_reportada_id` int unsigned DEFAULT NULL,
  `solicitud_reportada_id` int unsigned DEFAULT NULL,
  `estado_reporte_id` int unsigned NOT NULL,
  `administrador_id` int unsigned DEFAULT NULL,
  `motivo` varchar(120) NOT NULL,
  `detalle` varchar(1000) DEFAULT NULL,
  `fecha_reporte` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_resolucion` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_reportes_reportante` (`reportante_id`),
  KEY `fk_reportes_usuario` (`usuario_reportado_id`),
  KEY `fk_reportes_publicacion` (`publicacion_reportada_id`),
  KEY `fk_reportes_solicitud` (`solicitud_reportada_id`),
  KEY `fk_reportes_estado` (`estado_reporte_id`),
  KEY `fk_reportes_admin` (`administrador_id`),
  CONSTRAINT `fk_reportes_admin` FOREIGN KEY (`administrador_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_reportes_estado` FOREIGN KEY (`estado_reporte_id`) REFERENCES `estados_reporte` (`id`),
  CONSTRAINT `fk_reportes_publicacion` FOREIGN KEY (`publicacion_reportada_id`) REFERENCES `publicaciones` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_reportes_reportante` FOREIGN KEY (`reportante_id`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `fk_reportes_solicitud` FOREIGN KEY (`solicitud_reportada_id`) REFERENCES `solicitudes` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_reportes_usuario` FOREIGN KEY (`usuario_reportado_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sesiones_usuario`
--

DROP TABLE IF EXISTS `sesiones_usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sesiones_usuario` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `usuario_id` int unsigned NOT NULL,
  `token_hash` varchar(255) NOT NULL,
  `direccion_ip` varchar(45) DEFAULT NULL,
  `agente_usuario` varchar(500) DEFAULT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_expiracion` datetime NOT NULL,
  `fecha_revocacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token_hash` (`token_hash`),
  KEY `fk_sesiones_usuario` (`usuario_id`),
  CONSTRAINT `fk_sesiones_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `solicitud_ofertas`
--

DROP TABLE IF EXISTS `solicitud_ofertas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `solicitud_ofertas` (
  `solicitud_id` int unsigned NOT NULL,
  `publicacion_ofrecida_id` int unsigned NOT NULL,
  PRIMARY KEY (`solicitud_id`,`publicacion_ofrecida_id`),
  KEY `fk_ofertas_publicacion` (`publicacion_ofrecida_id`),
  CONSTRAINT `fk_ofertas_publicacion` FOREIGN KEY (`publicacion_ofrecida_id`) REFERENCES `publicaciones` (`id`),
  CONSTRAINT `fk_ofertas_solicitud` FOREIGN KEY (`solicitud_id`) REFERENCES `solicitudes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `solicitudes`
--

DROP TABLE IF EXISTS `solicitudes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `solicitudes` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `publicacion_solicitada_id` int unsigned NOT NULL,
  `solicitante_id` int unsigned NOT NULL,
  `modalidad_id` int unsigned NOT NULL,
  `estado_solicitud_id` int unsigned NOT NULL,
  `creditos_ofrecidos` decimal(10,2) DEFAULT NULL,
  `mensaje` varchar(1000) DEFAULT NULL,
  `motivo_rechazo` varchar(500) DEFAULT NULL,
  `fecha_solicitud` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_respuesta` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_solicitudes_publicacion` (`publicacion_solicitada_id`),
  KEY `fk_solicitudes_solicitante` (`solicitante_id`),
  KEY `fk_solicitudes_modalidad` (`modalidad_id`),
  KEY `fk_solicitudes_estado` (`estado_solicitud_id`),
  CONSTRAINT `fk_solicitudes_estado` FOREIGN KEY (`estado_solicitud_id`) REFERENCES `estados_solicitud` (`id`),
  CONSTRAINT `fk_solicitudes_modalidad` FOREIGN KEY (`modalidad_id`) REFERENCES `modalidades` (`id`),
  CONSTRAINT `fk_solicitudes_publicacion` FOREIGN KEY (`publicacion_solicitada_id`) REFERENCES `publicaciones` (`id`),
  CONSTRAINT `fk_solicitudes_solicitante` FOREIGN KEY (`solicitante_id`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `ck_solicitudes_creditos` CHECK (((`creditos_ofrecidos` is null) or (`creditos_ofrecidos` >= 0)))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tipos_material`
--

DROP TABLE IF EXISTS `tipos_material`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipos_material` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(60) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tipos_movimiento`
--

DROP TABLE IF EXISTS `tipos_movimiento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipos_movimiento` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tipos_notificacion`
--

DROP TABLE IF EXISTS `tipos_notificacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipos_notificacion` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(60) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `usuario_roles`
--

DROP TABLE IF EXISTS `usuario_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario_roles` (
  `usuario_id` int unsigned NOT NULL,
  `rol_id` int unsigned NOT NULL,
  `fecha_asignacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`usuario_id`,`rol_id`),
  KEY `fk_usuario_roles_rol` (`rol_id`),
  CONSTRAINT `fk_usuario_roles_rol` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`),
  CONSTRAINT `fk_usuario_roles_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `institucion_id` int unsigned NOT NULL,
  `carrera_id` int unsigned DEFAULT NULL,
  `estado_usuario_id` int unsigned NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(150) NOT NULL,
  `correo` varchar(190) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `foto_url` varchar(500) DEFAULT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `correo` (`correo`),
  KEY `fk_usuarios_institucion` (`institucion_id`),
  KEY `fk_usuarios_carrera` (`carrera_id`),
  KEY `fk_usuarios_estado` (`estado_usuario_id`),
  CONSTRAINT `fk_usuarios_carrera` FOREIGN KEY (`carrera_id`) REFERENCES `carreras` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_usuarios_estado` FOREIGN KEY (`estado_usuario_id`) REFERENCES `estados_usuario` (`id`),
  CONSTRAINT `fk_usuarios_institucion` FOREIGN KEY (`institucion_id`) REFERENCES `instituciones` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `valoraciones`
--

DROP TABLE IF EXISTS `valoraciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `valoraciones` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `intercambio_id` int unsigned NOT NULL,
  `autor_id` int unsigned NOT NULL,
  `evaluado_id` int unsigned NOT NULL,
  `puntuacion` int unsigned NOT NULL,
  `comentario` varchar(600) DEFAULT NULL,
  `fecha_valoracion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_valoraciones` (`intercambio_id`,`autor_id`),
  KEY `fk_valoraciones_autor` (`autor_id`),
  KEY `fk_valoraciones_evaluado` (`evaluado_id`),
  CONSTRAINT `fk_valoraciones_autor` FOREIGN KEY (`autor_id`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `fk_valoraciones_evaluado` FOREIGN KEY (`evaluado_id`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `fk_valoraciones_intercambio` FOREIGN KEY (`intercambio_id`) REFERENCES `intercambios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ck_valoraciones_puntuacion` CHECK ((`puntuacion` between 1 and 5)),
  CONSTRAINT `ck_valoraciones_usuarios` CHECK ((`autor_id` <> `evaluado_id`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary view structure for view `vw_coincidencias`
--

DROP TABLE IF EXISTS `vw_coincidencias`;
/*!50001 DROP VIEW IF EXISTS `vw_coincidencias`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_coincidencias` AS SELECT 
 1 AS `usuario_id`,
 1 AS `obra_id`,
 1 AS `publicacion_id`,
 1 AS `propietario_id`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_saldos_credito`
--

DROP TABLE IF EXISTS `vw_saldos_credito`;
/*!50001 DROP VIEW IF EXISTS `vw_saldos_credito`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_saldos_credito` AS SELECT 
 1 AS `usuario_id`,
 1 AS `saldo`*/;
SET character_set_client = @saved_cs_client;

--
-- Current Database: `bookcycle`
--

USE `bookcycle`;

--
-- Final view structure for view `vw_coincidencias`
--

/*!50001 DROP VIEW IF EXISTS `vw_coincidencias`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_coincidencias` AS select `d`.`usuario_id` AS `usuario_id`,`d`.`obra_id` AS `obra_id`,`p`.`id` AS `publicacion_id`,`p`.`propietario_id` AS `propietario_id` from ((`deseos` `d` join `publicaciones` `p` on((`p`.`obra_id` = `d`.`obra_id`))) join `estados_publicacion` `ep` on((`ep`.`id` = `p`.`estado_publicacion_id`))) where ((`d`.`activo` = 1) and (`ep`.`nombre` = 'DISPONIBLE') and (`p`.`propietario_id` <> `d`.`usuario_id`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_saldos_credito`
--

/*!50001 DROP VIEW IF EXISTS `vw_saldos_credito`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_saldos_credito` AS select `u`.`id` AS `usuario_id`,coalesce(sum(`m`.`monto`),0.00) AS `saldo` from (`usuarios` `u` left join `movimientos_credito` `m` on((`m`.`usuario_id` = `u`.`id`))) group by `u`.`id` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-08-04 18:30:42
