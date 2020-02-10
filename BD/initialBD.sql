-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 10-02-2020 a las 18:59:31
-- Versión del servidor: 5.7.23
-- Versión de PHP: 7.0.33-0+deb9u6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `wscvuy_proyecto_mineria_datos_estructura`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `area_principal`
--

CREATE TABLE `area_principal` (
  `id` int(11) NOT NULL,
  `documento` varchar(20) NOT NULL,
  `area` varchar(200) NOT NULL,
  `subarea` varchar(200) NOT NULL,
  `disciplina` varchar(300) NOT NULL,
  `especialidad` varchar(300) DEFAULT NULL,
  `fecha_importacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulos_dimensions`
--

CREATE TABLE `articulos_dimensions` (
  `id` varchar(50) NOT NULL,
  `title` varchar(300) NOT NULL,
  `type` varchar(50) NOT NULL,
  `year` varchar(4) NOT NULL,
  `doi` varchar(150) NOT NULL,
  `linkout` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulos_dimensions_authors`
--

CREATE TABLE `articulos_dimensions_authors` (
  `id_articulo` varchar(50) NOT NULL,
  `first_name` varchar(70) NOT NULL,
  `last_name` varchar(70) NOT NULL,
  `org_id` varchar(50) DEFAULT NULL,
  `org_name` varchar(150) DEFAULT NULL,
  `org_city` varchar(100) DEFAULT NULL,
  `org_city_id` varchar(50) DEFAULT NULL,
  `org_country` varchar(100) DEFAULT NULL,
  `org_country_code` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulos_dimensions_categories`
--

CREATE TABLE `articulos_dimensions_categories` (
  `id_articulo` varchar(50) NOT NULL,
  `id` varchar(50) NOT NULL,
  `category` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulos_dimensions_concepts`
--

CREATE TABLE `articulos_dimensions_concepts` (
  `id_articulo` varchar(50) NOT NULL,
  `word` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulos_dimensions_funders`
--

CREATE TABLE `articulos_dimensions_funders` (
  `id_articulo` varchar(50) NOT NULL,
  `id` varchar(50) NOT NULL,
  `acronym` varchar(150) NOT NULL,
  `name` varchar(150) NOT NULL,
  `country_name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulos_dimensions_journals`
--

CREATE TABLE `articulos_dimensions_journals` (
  `id_articulo` varchar(50) NOT NULL,
  `id` varchar(50) NOT NULL,
  `title` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulos_dimensions_mesh_terms`
--

CREATE TABLE `articulos_dimensions_mesh_terms` (
  `id_articulo` varchar(50) NOT NULL,
  `word` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulos_dimensions_terms`
--

CREATE TABLE `articulos_dimensions_terms` (
  `id_articulo` varchar(50) NOT NULL,
  `word` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulo_revista_arbitrada`
--

CREATE TABLE `articulo_revista_arbitrada` (
  `id` varchar(30) NOT NULL,
  `documento` varchar(20) NOT NULL,
  `lugarPublicacion` varchar(200) NOT NULL,
  `escritoPorInvitacion` varchar(11) NOT NULL,
  `volumen` varchar(100) NOT NULL,
  `fasciculo` varchar(100) NOT NULL,
  `serie` varchar(100) NOT NULL,
  `paginaInicial` varchar(11) NOT NULL,
  `paginaFinal` varchar(11) NOT NULL,
  `arbitrado` varchar(11) NOT NULL,
  `scopus` varchar(11) NOT NULL,
  `thompson` varchar(11) NOT NULL,
  `latindex` varchar(11) NOT NULL,
  `scielo` varchar(11) NOT NULL,
  `tipoArticulo` varchar(150) NOT NULL,
  `infoAdicional` text NOT NULL,
  `anio` varchar(11) NOT NULL,
  `web` varchar(200) NOT NULL,
  `relevante` varchar(11) NOT NULL,
  `medioDivulgacion` varchar(200) NOT NULL,
  `revista_nombre` varchar(250) NOT NULL,
  `revista_issn` varchar(30) NOT NULL,
  `titulo` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulo_revista_arbitrada_area`
--

CREATE TABLE `articulo_revista_arbitrada_area` (
  `id` varchar(30) NOT NULL,
  `idarticulo` varchar(30) NOT NULL,
  `area` varchar(200) NOT NULL,
  `subarea` varchar(200) NOT NULL,
  `disciplina` varchar(300) NOT NULL,
  `especialidad` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulo_revista_arbitrada_coautor`
--

CREATE TABLE `articulo_revista_arbitrada_coautor` (
  `id` varchar(30) NOT NULL,
  `idarticulo` varchar(30) NOT NULL,
  `nombres` varchar(150) NOT NULL,
  `apellidos` varchar(150) NOT NULL,
  `citacion` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulo_revista_arbitrada_palabra_clave`
--

CREATE TABLE `articulo_revista_arbitrada_palabra_clave` (
  `id` varchar(30) NOT NULL,
  `idarticulo` varchar(30) NOT NULL,
  `palabra` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulo_revista_noarbitrada`
--

CREATE TABLE `articulo_revista_noarbitrada` (
  `id` varchar(30) NOT NULL,
  `documento` varchar(20) NOT NULL,
  `lugarPublicacion` varchar(200) NOT NULL,
  `escritoPorInvitacion` varchar(11) NOT NULL,
  `volumen` varchar(100) NOT NULL,
  `fasciculo` varchar(100) NOT NULL,
  `serie` varchar(100) NOT NULL,
  `paginaInicial` varchar(11) NOT NULL,
  `paginaFinal` varchar(11) NOT NULL,
  `arbitrado` varchar(11) NOT NULL,
  `scopus` varchar(11) NOT NULL,
  `thompson` varchar(11) NOT NULL,
  `latindex` varchar(11) NOT NULL,
  `scielo` varchar(11) NOT NULL,
  `tipoArticulo` varchar(150) NOT NULL,
  `infoAdicional` text NOT NULL,
  `anio` varchar(11) NOT NULL,
  `web` varchar(200) NOT NULL,
  `relevante` varchar(11) NOT NULL,
  `medioDivulgacion` varchar(200) NOT NULL,
  `revista_nombre` varchar(250) NOT NULL,
  `revista_issn` varchar(30) NOT NULL,
  `titulo` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulo_revista_noarbitrada_area`
--

CREATE TABLE `articulo_revista_noarbitrada_area` (
  `id` varchar(30) NOT NULL,
  `idarticulo` varchar(30) NOT NULL,
  `area` varchar(200) NOT NULL,
  `subarea` varchar(200) NOT NULL,
  `disciplina` varchar(300) NOT NULL,
  `especialidad` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulo_revista_noarbitrada_coautor`
--

CREATE TABLE `articulo_revista_noarbitrada_coautor` (
  `id` varchar(30) NOT NULL,
  `idarticulo` varchar(30) NOT NULL,
  `nombres` varchar(150) NOT NULL,
  `apellidos` varchar(150) NOT NULL,
  `citacion` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulo_revista_noarbitrada_palabra_clave`
--

CREATE TABLE `articulo_revista_noarbitrada_palabra_clave` (
  `id` varchar(30) NOT NULL,
  `idarticulo` varchar(30) NOT NULL,
  `palabra` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comite_evaluador_proyectos`
--

CREATE TABLE `comite_evaluador_proyectos` (
  `documento` varchar(20) NOT NULL,
  `id_comite` varchar(20) NOT NULL,
  `institucion` varchar(200) DEFAULT NULL,
  `subInstitucion` varchar(200) DEFAULT NULL,
  `nombre` varchar(500) DEFAULT NULL,
  `anio` varchar(20) DEFAULT NULL,
  `periodoFin` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contacto`
--

CREATE TABLE `contacto` (
  `documento` varchar(20) NOT NULL,
  `email` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cvs_a_importar`
--

CREATE TABLE `cvs_a_importar` (
  `documento` varchar(30) NOT NULL,
  `nombres` varchar(150) NOT NULL,
  `apellidos` varchar(150) NOT NULL,
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `datos_identificacion`
--

CREATE TABLE `datos_identificacion` (
  `documento` varchar(20) NOT NULL,
  `citacion` varchar(150) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `genero` varchar(50) DEFAULT NULL,
  `pais` varchar(200) DEFAULT NULL,
  `nacionalidad` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `direccion_administracion`
--

CREATE TABLE `direccion_administracion` (
  `documento` varchar(20) NOT NULL,
  `id_direccion` varchar(20) NOT NULL,
  `dependencia` varchar(200) DEFAULT NULL,
  `unidad` varchar(200) DEFAULT NULL,
  `fechaInicio` varchar(20) DEFAULT NULL,
  `fechaFin` varchar(20) DEFAULT NULL,
  `descripcion` varchar(500) DEFAULT NULL,
  `cargaHorariaSemanal` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docencias`
--

CREATE TABLE `docencias` (
  `documento` varchar(20) NOT NULL,
  `id_docencia` varchar(20) NOT NULL,
  `tipoDocencia` varchar(150) DEFAULT NULL,
  `fechaInicio` varchar(20) DEFAULT NULL,
  `fechaFin` varchar(20) DEFAULT NULL,
  `tipoCurso` varchar(150) DEFAULT NULL,
  `descripcion` varchar(500) DEFAULT NULL,
  `cargaHoraria` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docentes_con_dt`
--

CREATE TABLE `docentes_con_dt` (
  `id_docente` int(8) NOT NULL,
  `cedula` varchar(8) DEFAULT NULL COMMENT 'sin puntos ni guion'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docentes_grado_2_dt`
--

CREATE TABLE `docentes_grado_2_dt` (
  `id_docente` int(8) NOT NULL,
  `cedula` varchar(8) DEFAULT NULL COMMENT 'sin puntos ni guion'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `formacion`
--

CREATE TABLE `formacion` (
  `id` varchar(30) NOT NULL,
  `documento` varchar(30) NOT NULL,
  `titulo` varchar(250) NOT NULL,
  `tutor` varchar(200) NOT NULL,
  `web` varchar(250) NOT NULL,
  `inicio` varchar(10) NOT NULL,
  `fin` varchar(10) NOT NULL,
  `obtencion` varchar(10) NOT NULL,
  `nivelAcademico` varchar(150) NOT NULL,
  `institucion` varchar(250) NOT NULL,
  `subInstitucion` varchar(250) NOT NULL,
  `subInstitucionDesc` varchar(250) NOT NULL,
  `programa` varchar(250) NOT NULL,
  `estatus` varchar(150) NOT NULL,
  `pais` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `formacion_area`
--

CREATE TABLE `formacion_area` (
  `id` varchar(30) NOT NULL,
  `idformacion` varchar(30) NOT NULL,
  `area` varchar(200) NOT NULL,
  `subarea` varchar(200) NOT NULL,
  `disciplina` varchar(300) NOT NULL,
  `especialidad` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `formacion_palabra_clave`
--

CREATE TABLE `formacion_palabra_clave` (
  `id` varchar(30) NOT NULL,
  `idformacion` varchar(30) NOT NULL,
  `palabra` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gestion_academica`
--

CREATE TABLE `gestion_academica` (
  `documento` varchar(20) NOT NULL,
  `id_gestion` varchar(20) NOT NULL,
  `funcionDesempeniada` varchar(200) DEFAULT NULL,
  `tipoGestion` varchar(200) DEFAULT NULL,
  `unidad` varchar(300) DEFAULT NULL,
  `descripcion` varchar(500) DEFAULT NULL,
  `fechaInicio` varchar(20) DEFAULT NULL,
  `fechaFin` varchar(20) DEFAULT NULL,
  `cargaHorariaSemanal` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hijos`
--

CREATE TABLE `hijos` (
  `id_hijo` varchar(20) NOT NULL,
  `documento` varchar(20) NOT NULL,
  `fecha_nacimiento` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `institucion_principal`
--

CREATE TABLE `institucion_principal` (
  `documento` varchar(20) NOT NULL,
  `institucion` varchar(250) NOT NULL,
  `subinstitucion` varchar(250) NOT NULL,
  `tipo_institucion` varchar(10) DEFAULT NULL,
  `subinstitucion_nueva` varchar(400) DEFAULT NULL,
  `dependencia` varchar(250) NOT NULL,
  `pais` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `investigadores`
--

CREATE TABLE `investigadores` (
  `documento` varchar(20) NOT NULL,
  `nombres` varchar(200) NOT NULL,
  `apellidos` varchar(200) NOT NULL,
  `cv_xml` longblob,
  `fecha_importacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sni` varchar(50) DEFAULT NULL,
  `ultima_actualizacion_cv` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `investigadores_no_cvuy`
--

CREATE TABLE `investigadores_no_cvuy` (
  `id` int(11) NOT NULL,
  `nombre_completo` varchar(200) NOT NULL,
  `citacion` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libros`
--

CREATE TABLE `libros` (
  `documento` varchar(20) NOT NULL,
  `id_libro` varchar(20) NOT NULL,
  `referado` varchar(20) DEFAULT NULL,
  `categoria` varchar(50) DEFAULT NULL,
  `anio` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `material_didactico`
--

CREATE TABLE `material_didactico` (
  `documento` varchar(20) NOT NULL,
  `id_material` varchar(20) NOT NULL,
  `titulo` varchar(500) DEFAULT NULL,
  `anio` varchar(20) DEFAULT NULL,
  `descripcion` varchar(500) DEFAULT NULL,
  `web` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `organizacion_eventos`
--

CREATE TABLE `organizacion_eventos` (
  `documento` varchar(20) NOT NULL,
  `id_evento_org` varchar(20) NOT NULL,
  `tipoOtraProduccion` varchar(200) DEFAULT NULL,
  `subTipoOtraProduccion` varchar(200) DEFAULT NULL,
  `anio` varchar(20) DEFAULT NULL,
  `institucionPromotora` varchar(500) DEFAULT NULL,
  `pais` varchar(150) DEFAULT NULL,
  `titulo` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `premios`
--

CREATE TABLE `premios` (
  `documento` varchar(20) NOT NULL,
  `id_premio` varchar(20) NOT NULL,
  `descripcion` varchar(500) DEFAULT NULL,
  `anio` varchar(20) DEFAULT NULL,
  `nombre` varchar(500) DEFAULT NULL,
  `entidadPromotora` varchar(200) DEFAULT NULL,
  `tipoCaracterEvento` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `produccion_tecnica_procesos`
--

CREATE TABLE `produccion_tecnica_procesos` (
  `id` varchar(30) NOT NULL,
  `documento` varchar(30) NOT NULL,
  `aplicacionProductivaSocial` varchar(11) NOT NULL,
  `descripcionProductivaSocial` text NOT NULL,
  `descripcion` text NOT NULL,
  `institucionFinanciadora` varchar(250) NOT NULL,
  `disponibilidad` varchar(200) NOT NULL,
  `tipoTecnica` varchar(200) NOT NULL,
  `pais` varchar(200) NOT NULL,
  `infoAdicional` text NOT NULL,
  `titulo` varchar(250) NOT NULL,
  `anio` varchar(11) NOT NULL,
  `web` varchar(250) NOT NULL,
  `relevante` varchar(11) NOT NULL,
  `medioDivulgacion` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `produccion_tecnica_procesos_area`
--

CREATE TABLE `produccion_tecnica_procesos_area` (
  `id` varchar(30) NOT NULL,
  `idproducto` varchar(30) NOT NULL,
  `area` varchar(200) NOT NULL,
  `subarea` varchar(200) NOT NULL,
  `disciplina` varchar(300) NOT NULL,
  `especialidad` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `produccion_tecnica_procesos_coautor`
--

CREATE TABLE `produccion_tecnica_procesos_coautor` (
  `id` varchar(30) NOT NULL,
  `idproducto` varchar(30) NOT NULL,
  `nombres` varchar(150) NOT NULL,
  `apellidos` varchar(150) NOT NULL,
  `citacion` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `produccion_tecnica_procesos_palabra_clave`
--

CREATE TABLE `produccion_tecnica_procesos_palabra_clave` (
  `id` varchar(30) NOT NULL,
  `idproducto` varchar(30) NOT NULL,
  `palabra` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `produccion_tecnica_procesos_patente`
--

CREATE TABLE `produccion_tecnica_procesos_patente` (
  `id` varchar(30) NOT NULL,
  `idproducto` varchar(30) NOT NULL,
  `codigo` varchar(30) NOT NULL,
  `titulo` varchar(250) NOT NULL,
  `tipoRegistro` varchar(200) NOT NULL,
  `patenteNacional` varchar(200) NOT NULL,
  `deposito` varchar(100) NOT NULL,
  `examen` varchar(100) NOT NULL,
  `concesion` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `produccion_tecnica_productos`
--

CREATE TABLE `produccion_tecnica_productos` (
  `id` varchar(30) NOT NULL,
  `documento` varchar(30) NOT NULL,
  `aplicacionProductivaSocial` varchar(11) NOT NULL,
  `descripcionProductivaSocial` text NOT NULL,
  `categoriaTecnica` varchar(200) NOT NULL,
  `descripcion` text NOT NULL,
  `institucionFinanciadora` varchar(250) NOT NULL,
  `institucion_financiadora_1` varchar(250) DEFAULT NULL,
  `institucion_financiadora_cod_1` varchar(250) DEFAULT NULL,
  `institucion_financiadora_2` varchar(250) DEFAULT NULL,
  `institucion_financiadora_cod_2` varchar(250) DEFAULT NULL,
  `institucion_financiadora_3` varchar(250) DEFAULT NULL,
  `institucion_financiadora_cod_3` varchar(250) DEFAULT NULL,
  `institucion_financiadora_4` varchar(250) DEFAULT NULL,
  `institucion_financiadora_cod_4` varchar(250) DEFAULT NULL,
  `institucion_financiadora_5` varchar(250) DEFAULT NULL,
  `institucion_financiadora_cod_5` varchar(250) DEFAULT NULL,
  `disponibilidad` varchar(200) NOT NULL,
  `tipoTecnica` varchar(200) NOT NULL,
  `pais` varchar(200) NOT NULL,
  `infoAdicional` text NOT NULL,
  `titulo` varchar(250) NOT NULL,
  `anio` varchar(11) NOT NULL,
  `web` varchar(250) NOT NULL,
  `relevante` varchar(11) NOT NULL,
  `medioDivulgacion` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `produccion_tecnica_productos_area`
--

CREATE TABLE `produccion_tecnica_productos_area` (
  `id` varchar(30) NOT NULL,
  `idproducto` varchar(30) NOT NULL,
  `area` varchar(200) NOT NULL,
  `subarea` varchar(200) NOT NULL,
  `disciplina` varchar(300) NOT NULL,
  `especialidad` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `produccion_tecnica_productos_coautor`
--

CREATE TABLE `produccion_tecnica_productos_coautor` (
  `id` varchar(30) NOT NULL,
  `idproducto` varchar(30) NOT NULL,
  `nombres` varchar(150) NOT NULL,
  `apellidos` varchar(150) NOT NULL,
  `citacion` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `produccion_tecnica_productos_coautor_coincidencias`
--

CREATE TABLE `produccion_tecnica_productos_coautor_coincidencias` (
  `id` varchar(30) NOT NULL,
  `documento_encontrado` varchar(30) DEFAULT NULL,
  `tipo_coincidencia` varchar(30) DEFAULT NULL,
  `id_investigador_no_cv_uy` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `produccion_tecnica_productos_palabra_clave`
--

CREATE TABLE `produccion_tecnica_productos_palabra_clave` (
  `id` varchar(30) NOT NULL,
  `idproducto` varchar(30) NOT NULL,
  `palabra` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `produccion_tecnica_productos_patente`
--

CREATE TABLE `produccion_tecnica_productos_patente` (
  `id` varchar(30) NOT NULL,
  `idproducto` varchar(30) NOT NULL,
  `codigo` varchar(30) NOT NULL,
  `titulo` varchar(250) NOT NULL,
  `tipoRegistro` varchar(200) NOT NULL,
  `patenteNacional` varchar(200) NOT NULL,
  `deposito` varchar(100) NOT NULL,
  `examen` varchar(100) NOT NULL,
  `concesion` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `produccion_tecnica_trabajos`
--

CREATE TABLE `produccion_tecnica_trabajos` (
  `id` varchar(30) NOT NULL,
  `documento` varchar(30) NOT NULL,
  `duracion` varchar(11) DEFAULT NULL,
  `numeroPaginas` varchar(11) DEFAULT NULL,
  `ciudad` varchar(150) DEFAULT NULL,
  `finalidad` varchar(150) DEFAULT NULL,
  `idioma` varchar(150) DEFAULT NULL,
  `descripcion` text,
  `institucionFinanciadora` varchar(250) DEFAULT NULL,
  `disponibilidad` varchar(150) DEFAULT NULL,
  `tipoTecnica` varchar(150) DEFAULT NULL,
  `pais` varchar(200) DEFAULT NULL,
  `infoAdicional` text,
  `titulo` varchar(250) DEFAULT NULL,
  `anio` varchar(11) DEFAULT NULL,
  `web` varchar(250) DEFAULT NULL,
  `relevante` varchar(11) DEFAULT NULL,
  `medioDivulgacion` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `produccion_tecnica_trabajos_area`
--

CREATE TABLE `produccion_tecnica_trabajos_area` (
  `id` varchar(30) NOT NULL,
  `idproducto` varchar(30) NOT NULL,
  `area` varchar(200) NOT NULL,
  `subarea` varchar(200) NOT NULL,
  `disciplina` varchar(300) NOT NULL,
  `especialidad` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `produccion_tecnica_trabajos_coautor`
--

CREATE TABLE `produccion_tecnica_trabajos_coautor` (
  `id` varchar(30) NOT NULL,
  `idproducto` varchar(30) NOT NULL,
  `nombres` varchar(150) NOT NULL,
  `apellidos` varchar(150) NOT NULL,
  `citacion` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `produccion_tecnica_trabajos_palabra_clave`
--

CREATE TABLE `produccion_tecnica_trabajos_palabra_clave` (
  `id` varchar(30) NOT NULL,
  `idproducto` varchar(30) NOT NULL,
  `palabra` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyecto_extension`
--

CREATE TABLE `proyecto_extension` (
  `id` varchar(25) NOT NULL,
  `documento` varchar(20) NOT NULL,
  `institucion` varchar(250) NOT NULL,
  `descripcion` text NOT NULL,
  `dependencia` varchar(250) NOT NULL,
  `unidad` varchar(250) NOT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `cargaHorariaSemanal` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyecto_extension_area`
--

CREATE TABLE `proyecto_extension_area` (
  `id` varchar(30) NOT NULL,
  `idproyecto` varchar(30) NOT NULL,
  `area` varchar(200) NOT NULL,
  `subarea` varchar(200) NOT NULL,
  `disciplina` varchar(300) NOT NULL,
  `especialidad` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyecto_investigacion`
--

CREATE TABLE `proyecto_investigacion` (
  `id` varchar(25) NOT NULL,
  `documento` varchar(20) NOT NULL,
  `institucion` varchar(250) NOT NULL,
  `titulo` varchar(500) NOT NULL,
  `descripcion` text NOT NULL,
  `otra_descripcion` text NOT NULL,
  `alumno_pregrado` varchar(11) NOT NULL,
  `alumno_especializacion` varchar(11) NOT NULL,
  `alumno_maestria` varchar(11) NOT NULL,
  `alumno_maestria_prof` varchar(11) NOT NULL,
  `alumno_doctorado` varchar(11) NOT NULL,
  `tipo_participacion_vinculo` varchar(200) NOT NULL,
  `situacion_vinculo` varchar(200) NOT NULL,
  `tipo_clase_vinculo` varchar(200) NOT NULL,
  `dependencia` varchar(250) NOT NULL,
  `unidad` varchar(250) NOT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `cargaHorariaSemanal` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyecto_investigacion_area`
--

CREATE TABLE `proyecto_investigacion_area` (
  `id` varchar(30) NOT NULL,
  `idproyecto` varchar(30) NOT NULL,
  `area` varchar(200) NOT NULL,
  `subarea` varchar(200) NOT NULL,
  `disciplina` varchar(300) NOT NULL,
  `especialidad` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyecto_investigacion_equipo`
--

CREATE TABLE `proyecto_investigacion_equipo` (
  `id` varchar(35) NOT NULL,
  `idproyecto` varchar(30) NOT NULL,
  `nombres` varchar(200) DEFAULT NULL,
  `apellidos` varchar(200) DEFAULT NULL,
  `citacion` varchar(150) DEFAULT NULL,
  `responsable` varchar(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyecto_investigacion_equipo_coincidencias`
--

CREATE TABLE `proyecto_investigacion_equipo_coincidencias` (
  `id` varchar(30) NOT NULL,
  `documento_encontrado` varchar(30) DEFAULT NULL,
  `tipo_coincidencia` varchar(30) DEFAULT NULL,
  `id_investigador_no_cv_uy` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyecto_investigacion_institucion_financiadora`
--

CREATE TABLE `proyecto_investigacion_institucion_financiadora` (
  `id` varchar(30) NOT NULL,
  `idproyecto` varchar(30) NOT NULL,
  `institucion` varchar(250) NOT NULL,
  `subinstitucion` varchar(250) NOT NULL,
  `pais` varchar(250) NOT NULL,
  `tipofinanciacion` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyecto_investigacion_palabra_clave`
--

CREATE TABLE `proyecto_investigacion_palabra_clave` (
  `id` varchar(30) NOT NULL,
  `idproyecto` varchar(30) NOT NULL,
  `palabra` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recorte_articulo_revista_arbitrada`
--

CREATE TABLE `recorte_articulo_revista_arbitrada` (
  `id` varchar(30) NOT NULL,
  `documento` varchar(20) NOT NULL,
  `lugarPublicacion` varchar(200) NOT NULL,
  `escritoPorInvitacion` varchar(11) NOT NULL,
  `volumen` varchar(100) NOT NULL,
  `fasciculo` varchar(100) NOT NULL,
  `serie` varchar(100) NOT NULL,
  `paginaInicial` varchar(11) NOT NULL,
  `paginaFinal` varchar(11) NOT NULL,
  `arbitrado` varchar(11) NOT NULL,
  `scopus` varchar(11) NOT NULL,
  `thompson` varchar(11) NOT NULL,
  `latindex` varchar(11) NOT NULL,
  `scielo` varchar(11) NOT NULL,
  `tipoArticulo` varchar(150) NOT NULL,
  `infoAdicional` text NOT NULL,
  `anio` varchar(11) NOT NULL,
  `web` varchar(200) NOT NULL,
  `relevante` varchar(11) NOT NULL,
  `medioDivulgacion` varchar(200) NOT NULL,
  `revista_nombre` varchar(250) NOT NULL,
  `revista_issn` varchar(30) NOT NULL,
  `titulo` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recorte_articulo_revista_arbitrada_coautor`
--

CREATE TABLE `recorte_articulo_revista_arbitrada_coautor` (
  `id` varchar(30) NOT NULL,
  `idarticulo` varchar(30) NOT NULL,
  `nombres` varchar(150) NOT NULL,
  `apellidos` varchar(150) NOT NULL,
  `citacion` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recorte_produccion_tecnica_productos`
--

CREATE TABLE `recorte_produccion_tecnica_productos` (
  `id` varchar(30) NOT NULL,
  `documento` varchar(30) NOT NULL,
  `aplicacionProductivaSocial` varchar(11) NOT NULL,
  `descripcionProductivaSocial` text NOT NULL,
  `categoriaTecnica` varchar(200) NOT NULL,
  `descripcion` text NOT NULL,
  `institucionFinanciadora` varchar(250) NOT NULL,
  `disponibilidad` varchar(200) NOT NULL,
  `tipoTecnica` varchar(200) NOT NULL,
  `pais` varchar(200) NOT NULL,
  `infoAdicional` text NOT NULL,
  `titulo` varchar(250) NOT NULL,
  `anio` varchar(11) NOT NULL,
  `web` varchar(250) NOT NULL,
  `relevante` varchar(11) NOT NULL,
  `medioDivulgacion` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recorte_produccion_tecnica_productos_area`
--

CREATE TABLE `recorte_produccion_tecnica_productos_area` (
  `id` varchar(30) NOT NULL,
  `idproducto` varchar(30) NOT NULL,
  `area` varchar(200) NOT NULL,
  `subarea` varchar(200) NOT NULL,
  `disciplina` varchar(300) NOT NULL,
  `especialidad` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recorte_produccion_tecnica_productos_coautor`
--

CREATE TABLE `recorte_produccion_tecnica_productos_coautor` (
  `id` varchar(30) NOT NULL,
  `idproducto` varchar(30) NOT NULL,
  `nombres` varchar(150) NOT NULL,
  `apellidos` varchar(150) NOT NULL,
  `citacion` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recorte_proyecto_investigacion`
--

CREATE TABLE `recorte_proyecto_investigacion` (
  `id` varchar(25) NOT NULL,
  `idioma` varchar(20) DEFAULT NULL,
  `documento` varchar(20) NOT NULL,
  `institucion` varchar(250) NOT NULL,
  `tipo_institucion` varchar(10) DEFAULT NULL,
  `subinstitucion_nueva` varchar(400) DEFAULT NULL,
  `titulo` varchar(500) NOT NULL,
  `descripcion` text NOT NULL,
  `tituloTraducido` varchar(500) DEFAULT NULL,
  `descripcionTraducida` text,
  `otra_descripcion` text NOT NULL,
  `alumno_pregrado` varchar(11) NOT NULL,
  `alumno_especializacion` varchar(11) NOT NULL,
  `alumno_maestria` varchar(11) NOT NULL,
  `alumno_maestria_prof` varchar(11) NOT NULL,
  `alumno_doctorado` varchar(11) NOT NULL,
  `tipo_participacion_vinculo` varchar(200) NOT NULL,
  `situacion_vinculo` varchar(200) NOT NULL,
  `tipo_clase_vinculo` varchar(200) NOT NULL,
  `dependencia` varchar(250) NOT NULL,
  `unidad` varchar(250) NOT NULL,
  `fecha_inicio` varchar(50) DEFAULT NULL,
  `fecha_fin` varchar(50) DEFAULT NULL,
  `cargaHorariaSemanal` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recorte_proyecto_investigacion_backup`
--

CREATE TABLE `recorte_proyecto_investigacion_backup` (
  `id` varchar(25) NOT NULL,
  `idioma` varchar(20) DEFAULT NULL,
  `documento` varchar(20) NOT NULL,
  `institucion` varchar(250) NOT NULL,
  `tipo_institucion` varchar(10) DEFAULT NULL,
  `subinstitucion_nueva` varchar(400) DEFAULT NULL,
  `titulo` varchar(500) NOT NULL,
  `descripcion` text NOT NULL,
  `otra_descripcion` text NOT NULL,
  `alumno_pregrado` varchar(11) NOT NULL,
  `alumno_especializacion` varchar(11) NOT NULL,
  `alumno_maestria` varchar(11) NOT NULL,
  `alumno_maestria_prof` varchar(11) NOT NULL,
  `alumno_doctorado` varchar(11) NOT NULL,
  `tipo_participacion_vinculo` varchar(200) NOT NULL,
  `situacion_vinculo` varchar(200) NOT NULL,
  `tipo_clase_vinculo` varchar(200) NOT NULL,
  `dependencia` varchar(250) NOT NULL,
  `unidad` varchar(250) NOT NULL,
  `fecha_inicio` varchar(50) DEFAULT NULL,
  `fecha_fin` varchar(50) DEFAULT NULL,
  `cargaHorariaSemanal` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recorte_proyecto_investigacion_equipo`
--

CREATE TABLE `recorte_proyecto_investigacion_equipo` (
  `id` varchar(35) NOT NULL,
  `idproyecto` varchar(30) NOT NULL,
  `nombres` varchar(200) DEFAULT NULL,
  `apellidos` varchar(200) DEFAULT NULL,
  `citacion` varchar(150) DEFAULT NULL,
  `responsable` varchar(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recorte_proyecto_investigacion_equipo_coincidencias`
--

CREATE TABLE `recorte_proyecto_investigacion_equipo_coincidencias` (
  `id` varchar(30) NOT NULL,
  `documento_encontrado` varchar(30) DEFAULT NULL,
  `tipo_coincidencia` varchar(30) DEFAULT NULL,
  `id_investigador_no_cv_uy` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recorte_proyecto_investigacion_institucion_financiadora`
--

CREATE TABLE `recorte_proyecto_investigacion_institucion_financiadora` (
  `id` varchar(30) NOT NULL,
  `idproyecto` varchar(30) NOT NULL,
  `institucion` varchar(250) NOT NULL,
  `subinstitucion` varchar(250) NOT NULL,
  `tipo_institucion` varchar(10) DEFAULT NULL,
  `subinstitucion_nueva` varchar(400) DEFAULT NULL,
  `pais` varchar(250) NOT NULL,
  `tipofinanciacion` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sni`
--

CREATE TABLE `sni` (
  `id` int(11) NOT NULL,
  `documento` varchar(20) NOT NULL,
  `nivel` varchar(100) DEFAULT NULL,
  `categoria` varchar(200) DEFAULT NULL,
  `area` varchar(200) DEFAULT NULL,
  `subarea` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `textos_revistas`
--

CREATE TABLE `textos_revistas` (
  `documento` varchar(20) NOT NULL,
  `id_texto` varchar(20) NOT NULL,
  `tituloLugarPublicado` varchar(500) DEFAULT NULL,
  `anio` varchar(20) DEFAULT NULL,
  `claseTexto` varchar(200) DEFAULT NULL,
  `titulo` varchar(500) DEFAULT NULL,
  `medioDivulgacion` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trabajos_eventos`
--

CREATE TABLE `trabajos_eventos` (
  `documento` varchar(20) NOT NULL,
  `id_trabajo` varchar(20) NOT NULL,
  `referado` varchar(20) DEFAULT NULL,
  `clase` varchar(20) DEFAULT NULL,
  `anio` varchar(20) DEFAULT NULL,
  `evento_nombre` varchar(500) DEFAULT NULL,
  `evento_clasificacion` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tutorias_concluidas`
--

CREATE TABLE `tutorias_concluidas` (
  `documento` varchar(20) NOT NULL,
  `id_tutoria` varchar(20) NOT NULL,
  `titulo` varchar(200) DEFAULT NULL,
  `anio` varchar(20) DEFAULT NULL,
  `tipoTutoria` varchar(150) DEFAULT NULL,
  `concluida` varchar(20) DEFAULT NULL,
  `infoAdicional` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vinculo_institucional`
--

CREATE TABLE `vinculo_institucional` (
  `id` varchar(30) NOT NULL,
  `documento` varchar(30) NOT NULL,
  `institucion` varchar(200) NOT NULL,
  `subinstitucion` varchar(200) NOT NULL,
  `tipo_institucion` varchar(10) DEFAULT NULL,
  `subinstitucion_nueva` varchar(400) DEFAULT NULL,
  `descripcion` text NOT NULL,
  `cargahoraria` varchar(10) NOT NULL,
  `relevante` varchar(10) NOT NULL,
  `inicio` varchar(15) NOT NULL,
  `fin` varchar(15) NOT NULL,
  `dedicaciontotal` varchar(10) NOT NULL,
  `tipovinculo` varchar(100) NOT NULL,
  `tipovinculodocente` varchar(100) NOT NULL,
  `tipovinculodocentecargo` varchar(100) NOT NULL,
  `tipovinculodocentegrado` varchar(100) NOT NULL,
  `info` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `area_principal`
--
ALTER TABLE `area_principal`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `articulo_revista_arbitrada`
--
ALTER TABLE `articulo_revista_arbitrada`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `articulo_revista_arbitrada_area`
--
ALTER TABLE `articulo_revista_arbitrada_area`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `articulo_revista_arbitrada_coautor`
--
ALTER TABLE `articulo_revista_arbitrada_coautor`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `articulo_revista_arbitrada_palabra_clave`
--
ALTER TABLE `articulo_revista_arbitrada_palabra_clave`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `articulo_revista_noarbitrada`
--
ALTER TABLE `articulo_revista_noarbitrada`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `articulo_revista_noarbitrada_area`
--
ALTER TABLE `articulo_revista_noarbitrada_area`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `articulo_revista_noarbitrada_coautor`
--
ALTER TABLE `articulo_revista_noarbitrada_coautor`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `articulo_revista_noarbitrada_palabra_clave`
--
ALTER TABLE `articulo_revista_noarbitrada_palabra_clave`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `contacto`
--
ALTER TABLE `contacto`
  ADD PRIMARY KEY (`documento`);

--
-- Indices de la tabla `cvs_a_importar`
--
ALTER TABLE `cvs_a_importar`
  ADD PRIMARY KEY (`documento`);

--
-- Indices de la tabla `datos_identificacion`
--
ALTER TABLE `datos_identificacion`
  ADD PRIMARY KEY (`documento`);

--
-- Indices de la tabla `docentes_con_dt`
--
ALTER TABLE `docentes_con_dt`
  ADD PRIMARY KEY (`id_docente`);

--
-- Indices de la tabla `docentes_grado_2_dt`
--
ALTER TABLE `docentes_grado_2_dt`
  ADD PRIMARY KEY (`id_docente`);

--
-- Indices de la tabla `formacion`
--
ALTER TABLE `formacion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `formacion_area`
--
ALTER TABLE `formacion_area`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `formacion_palabra_clave`
--
ALTER TABLE `formacion_palabra_clave`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `institucion_principal`
--
ALTER TABLE `institucion_principal`
  ADD PRIMARY KEY (`documento`);

--
-- Indices de la tabla `investigadores`
--
ALTER TABLE `investigadores`
  ADD PRIMARY KEY (`documento`);

--
-- Indices de la tabla `investigadores_no_cvuy`
--
ALTER TABLE `investigadores_no_cvuy`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `produccion_tecnica_procesos`
--
ALTER TABLE `produccion_tecnica_procesos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `produccion_tecnica_procesos_area`
--
ALTER TABLE `produccion_tecnica_procesos_area`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `produccion_tecnica_procesos_coautor`
--
ALTER TABLE `produccion_tecnica_procesos_coautor`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `produccion_tecnica_procesos_palabra_clave`
--
ALTER TABLE `produccion_tecnica_procesos_palabra_clave`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `produccion_tecnica_procesos_patente`
--
ALTER TABLE `produccion_tecnica_procesos_patente`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `produccion_tecnica_productos`
--
ALTER TABLE `produccion_tecnica_productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `produccion_tecnica_productos_area`
--
ALTER TABLE `produccion_tecnica_productos_area`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `produccion_tecnica_productos_coautor`
--
ALTER TABLE `produccion_tecnica_productos_coautor`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `produccion_tecnica_productos_palabra_clave`
--
ALTER TABLE `produccion_tecnica_productos_palabra_clave`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `produccion_tecnica_productos_patente`
--
ALTER TABLE `produccion_tecnica_productos_patente`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `produccion_tecnica_trabajos`
--
ALTER TABLE `produccion_tecnica_trabajos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `produccion_tecnica_trabajos_area`
--
ALTER TABLE `produccion_tecnica_trabajos_area`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `produccion_tecnica_trabajos_coautor`
--
ALTER TABLE `produccion_tecnica_trabajos_coautor`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `produccion_tecnica_trabajos_palabra_clave`
--
ALTER TABLE `produccion_tecnica_trabajos_palabra_clave`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `proyecto_extension`
--
ALTER TABLE `proyecto_extension`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `proyecto_extension_area`
--
ALTER TABLE `proyecto_extension_area`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `proyecto_investigacion`
--
ALTER TABLE `proyecto_investigacion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `proyecto_investigacion_area`
--
ALTER TABLE `proyecto_investigacion_area`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `proyecto_investigacion_institucion_financiadora`
--
ALTER TABLE `proyecto_investigacion_institucion_financiadora`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `proyecto_investigacion_palabra_clave`
--
ALTER TABLE `proyecto_investigacion_palabra_clave`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `recorte_articulo_revista_arbitrada`
--
ALTER TABLE `recorte_articulo_revista_arbitrada`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `recorte_articulo_revista_arbitrada_coautor`
--
ALTER TABLE `recorte_articulo_revista_arbitrada_coautor`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `recorte_produccion_tecnica_productos`
--
ALTER TABLE `recorte_produccion_tecnica_productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `recorte_produccion_tecnica_productos_area`
--
ALTER TABLE `recorte_produccion_tecnica_productos_area`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `recorte_produccion_tecnica_productos_coautor`
--
ALTER TABLE `recorte_produccion_tecnica_productos_coautor`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `recorte_proyecto_investigacion`
--
ALTER TABLE `recorte_proyecto_investigacion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `recorte_proyecto_investigacion_backup`
--
ALTER TABLE `recorte_proyecto_investigacion_backup`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `recorte_proyecto_investigacion_institucion_financiadora`
--
ALTER TABLE `recorte_proyecto_investigacion_institucion_financiadora`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sni`
--
ALTER TABLE `sni`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `area_principal`
--
ALTER TABLE `area_principal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21420;
--
-- AUTO_INCREMENT de la tabla `docentes_con_dt`
--
ALTER TABLE `docentes_con_dt`
  MODIFY `id_docente` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2566;
--
-- AUTO_INCREMENT de la tabla `docentes_grado_2_dt`
--
ALTER TABLE `docentes_grado_2_dt`
  MODIFY `id_docente` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1668;
--
-- AUTO_INCREMENT de la tabla `investigadores_no_cvuy`
--
ALTER TABLE `investigadores_no_cvuy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115074;
--
-- AUTO_INCREMENT de la tabla `sni`
--
ALTER TABLE `sni`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1827;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
