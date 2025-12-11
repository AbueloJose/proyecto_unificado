-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-12-2025 a las 21:47:48
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sistema_unificado`
--
CREATE DATABASE IF NOT EXISTS `sistema_unificado` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `sistema_unificado`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `vacancy_id` int(11) NOT NULL,
  `docente_id` int(11) DEFAULT NULL,
  `estado` enum('pendiente','aprobada','rechazada','en_curso') NOT NULL DEFAULT 'pendiente',
  `fecha_aplicacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `chat_history`
--

CREATE TABLE `chat_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `mensaje_usuario` text NOT NULL,
  `mensaje_bot` text NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `companies`
--

CREATE TABLE `companies` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `ruc` varchar(20) DEFAULT NULL,
  `rubro` varchar(100) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `telefono_contacto` varchar(20) DEFAULT NULL,
  `nombre_contacto` varchar(150) DEFAULT NULL,
  `email_contacto` varchar(100) DEFAULT NULL,
  `convenio_vigente` tinyint(1) DEFAULT 1,
  `fecha_fin_convenio` date DEFAULT NULL,
  `logo_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `companies`
--

INSERT INTO `companies` (`id`, `nombre`, `ruc`, `rubro`, `direccion`, `telefono_contacto`, `nombre_contacto`, `email_contacto`, `convenio_vigente`, `fecha_fin_convenio`, `logo_path`) VALUES
(1000, 'Informes', '10171027192', 'Ingenieria', '4X5FX4P, Av. Túpac Amaru, Carabayllo 1733', NULL, '', '', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evaluations`
--

CREATE TABLE `evaluations` (
  `id` int(11) NOT NULL,
  `internship_id` int(11) NOT NULL,
  `semana` int(11) DEFAULT 0,
  `conocimiento_tecnico` int(11) DEFAULT 0,
  `comunicacion` int(11) DEFAULT 0,
  `trabajo_equipo` int(11) DEFAULT 0,
  `resolucion_problemas` int(11) DEFAULT 0,
  `puntualidad` int(11) DEFAULT 0,
  `fecha_evaluacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `internships`
--

CREATE TABLE `internships` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `application_id` int(11) DEFAULT NULL,
  `puesto` varchar(100) NOT NULL,
  `estado` enum('en_curso','finalizada','cancelada') DEFAULT 'en_curso',
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `student_profiles`
--

CREATE TABLE `student_profiles` (
  `user_id` int(11) NOT NULL,
  `cv_path` varchar(255) DEFAULT NULL,
  `ficha_tecnica_path` varchar(255) DEFAULT NULL,
  `habilidades` text DEFAULT NULL,
  `descripcion_perfil` text DEFAULT NULL,
  `semestre` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `student_profiles`
--

INSERT INTO `student_profiles` (`user_id`, `cv_path`, `ficha_tecnica_path`, `habilidades`, `descripcion_perfil`, `semestre`) VALUES
(1002, NULL, NULL, NULL, NULL, NULL),
(1003, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tracking_documents`
--

CREATE TABLE `tracking_documents` (
  `id` int(11) NOT NULL,
  `internship_id` int(11) NOT NULL,
  `tipo_documento` enum('Plan_Trabajo','Informe_Final','Constancia_Empresa') NOT NULL,
  `ruta_archivo` varchar(512) NOT NULL,
  `estado` enum('Pendiente','Observado','Aprobado') NOT NULL DEFAULT 'Pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `codigo` varchar(20) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `rol` enum('estudiante','docente','admin') NOT NULL DEFAULT 'estudiante',
  `foto_perfil` varchar(255) DEFAULT NULL,
  `foto_biometria` varchar(255) DEFAULT NULL,
  `face_descriptor` text DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `nombres`, `apellidos`, `email`, `password`, `codigo`, `telefono`, `rol`, `foto_perfil`, `foto_biometria`, `face_descriptor`, `activo`, `created_at`) VALUES
(1002, 'asas', 'asasa', 'barca.flash29@gmail.com', '$2y$10$wUIA2H3VEA3GQXrMtgyHEOf8BTgB.lcB2zMD3o92o5zzYvq16n4Ii', '122212', NULL, 'admin', NULL, 'uploads/biometria/biometria_1765484153_8175.png', '[-0.17863903939723969,0.027637990191578865,0.026969928294420242,-0.04568712040781975,-0.15477080643177032,0.006097750272601843,0.019310975447297096,-0.09075547754764557,0.12341427803039551,-0.09268439561128616,0.171744704246521,-0.022652670741081238,-0.21604304015636444,-0.009755824692547321,-0.05519584193825722,0.16496463119983673,-0.15266020596027374,-0.16250649094581604,-0.07171958684921265,-0.015692872926592827,0.03964538127183914,-0.012117227539420128,-0.015109251253306866,0.12855497002601624,-0.11966272443532944,-0.28840401768684387,-0.07966512441635132,-0.056231480091810226,-0.06157892942428589,-0.05989375710487366,0.0372653566300869,0.0020293411798775196,-0.1849919557571411,-0.031433407217264175,0.014117259532213211,0.09885638952255249,-0.04841523990035057,-0.06339091062545776,0.13181540369987488,0.026072565466165543,-0.24189803004264832,0.07424698024988174,0.06947127729654312,0.24630163609981537,0.15172258019447327,0.10900840163230896,0.011366876773536205,-0.08958783745765686,0.1599733680486679,-0.23038144409656525,-0.006903000175952911,0.10765445232391357,0.126978799700737,0.02580089122056961,0.011353905312716961,-0.16280080378055573,0.04787575080990791,0.1605503112077713,-0.14327417314052582,0.07709097862243652,-0.011843636631965637,-0.05315474420785904,0.09524018317461014,-0.05757083371281624,0.19686654210090637,0.04912398383021355,-0.12679502367973328,-0.08880624175071716,0.14822138845920563,-0.14471301436424255,-0.09332608431577682,0.0901947021484375,-0.12580467760562897,-0.2624993920326233,-0.2831212282180786,0.07277057319879532,0.47272396087646484,0.15428319573402405,-0.11698360741138458,-0.0026316731236875057,-0.011449554935097694,0.028287453576922417,0.017418181523680687,0.15770621597766876,-0.08723762631416321,-0.025652429088950157,-0.09771321713924408,-0.020099520683288574,0.26077306270599365,0.01459826435893774,-0.03881051018834114,0.24147257208824158,0.027601758018136024,0.05154414474964142,-0.003129750955849886,0.026435941457748413,-0.0774015411734581,0.053049005568027496,-0.0955686941742897,-0.020409567281603813,-0.0330398865044117,-0.04549393430352211,0.042021702975034714,0.07099536061286926,-0.18071366846561432,0.12620098888874054,-0.04554276913404465,-0.0009026812040247023,-0.08148770779371262,0.0796591192483902,-0.11200235784053802,0.013889472931623459,0.10555233806371689,-0.24830615520477295,0.2024989128112793,0.23803575336933136,0.08520752191543579,0.13167044520378113,0.12094083428382874,0.015240499749779701,0.026138905435800552,0.004841019865125418,-0.13049881160259247,-0.05609425529837608,0.12715329229831696,-0.09072843939065933,0.12738284468650818,0.0371958427131176]', 1, '2025-12-11 20:15:53'),
(1003, 'asa', 'mitaka', 'ndea@gmail.com', '$2y$10$OKirODI..QswbwEdQpWZ2.Di73p1N56sBxpssBOlQSds15Q0qmI.O', '20259405', NULL, 'docente', NULL, 'uploads/biometria/biometria_1765484511_7399.png', '[-0.17705072462558746,0.06279908120632172,0.026791246607899666,-0.07741276174783707,-0.16038848459720612,-0.001189740956760943,-0.0020657978020608425,-0.07327772676944733,0.12216801196336746,-0.11129157245159149,0.2316960096359253,-0.021729586645960808,-0.2201666235923767,-0.025797802954912186,-0.0791499987244606,0.17623795568943024,-0.15288732945919037,-0.15347565710544586,-0.06840548664331436,-0.0007752305245958269,0.043190017342567444,-0.003232284914702177,-0.018734754994511604,0.05951378867030144,-0.1157851293683052,-0.2904078960418701,-0.0641431212425232,-0.05658014863729477,-0.07156365364789963,-0.04765398055315018,0.038085147738456726,-0.00762972142547369,-0.17053720355033875,-0.057910215109586716,0.038819797337055206,0.11836770176887512,-0.0531066358089447,-0.04108436033129692,0.15616334974765778,0.030353723093867302,-0.24661989510059357,0.07739314436912537,0.10257399827241898,0.2398645281791687,0.1356610208749771,0.09555620700120926,0.01848418638110161,-0.15045657753944397,0.1674843579530716,-0.22126954793930054,-0.015888001769781113,0.09745398163795471,0.12076660990715027,0.03135222569108009,0.03626066446304321,-0.12790174782276154,0.06570642441511154,0.15252657234668732,-0.13479532301425934,0.03588654845952988,0.029897958040237427,-0.024223793298006058,0.04110913351178169,-0.08322744071483612,0.22925885021686554,0.03624546900391579,-0.1271311640739441,-0.13114772737026215,0.15883970260620117,-0.11598725616931915,-0.1272326558828354,0.14042720198631287,-0.10045177489519119,-0.22754202783107758,-0.2612692415714264,0.05463424324989319,0.4598362147808075,0.13396015763282776,-0.10594906657934189,0.017402466386556625,-0.03359298035502434,-0.008883287198841572,0.03482402116060257,0.14093711972236633,-0.056087542325258255,-0.005260186269879341,-0.08514389395713806,-0.03782752528786659,0.2781800627708435,-0.012549303472042084,-0.02186671271920204,0.2242402583360672,0.04141596332192421,0.09544466435909271,0.0022934197913855314,0.016772456467151642,-0.09424242377281189,0.056820545345544815,-0.11776414513587952,-0.04828174412250519,0.0016940028872340918,-0.042601265013217926,0.03376277536153793,0.07090644538402557,-0.1771339327096939,0.10642915964126587,-0.06026365980505943,0.015104936435818672,-0.04003386199474335,0.053725797683000565,-0.06849222630262375,0.010440856218338013,0.10490848869085312,-0.2539389431476593,0.1999109834432602,0.20113083720207214,0.07461655884981155,0.09128479659557343,0.1775740683078766,-0.0029921287205070257,0.02167733758687973,-0.0009549640235491097,-0.1793183982372284,-0.0589209608733654,0.09931422024965286,-0.09841807186603546,0.1286485344171524,0.01154304388910532]', 1, '2025-12-11 20:21:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vacancies`
--

CREATE TABLE `vacancies` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `area` varchar(100) DEFAULT NULL,
  `cupos` int(3) NOT NULL DEFAULT 1,
  `activa` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `teacher_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `vacancies`
--

INSERT INTO `vacancies` (`id`, `company_id`, `titulo`, `descripcion`, `area`, `cupos`, `activa`, `created_at`, `teacher_id`) VALUES
(4, 1000, 'Back', '', '', 1, 1, '2025-12-11 20:22:50', 1003);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `weekly_reports`
--

CREATE TABLE `weekly_reports` (
  `id` int(11) NOT NULL,
  `internship_id` int(11) NOT NULL,
  `semana_numero` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `archivo_adjunto` varchar(255) DEFAULT NULL,
  `estado` enum('pendiente','aprobado','observado') DEFAULT 'pendiente',
  `comentarios_docente` text DEFAULT NULL,
  `fecha_subida` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_app_user` (`user_id`),
  ADD KEY `fk_app_vacancy` (`vacancy_id`);

--
-- Indices de la tabla `chat_history`
--
ALTER TABLE `chat_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_chat_user` (`user_id`);

--
-- Indices de la tabla `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ruc` (`ruc`);

--
-- Indices de la tabla `evaluations`
--
ALTER TABLE `evaluations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_eval_intern` (`internship_id`);

--
-- Indices de la tabla `internships`
--
ALTER TABLE `internships`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_intern_student` (`student_id`);

--
-- Indices de la tabla `student_profiles`
--
ALTER TABLE `student_profiles`
  ADD PRIMARY KEY (`user_id`);

--
-- Indices de la tabla `tracking_documents`
--
ALTER TABLE `tracking_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_doc_intern` (`internship_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- Indices de la tabla `vacancies`
--
ALTER TABLE `vacancies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_vacancy_company` (`company_id`),
  ADD KEY `fk_vacancy_teacher` (`teacher_id`);

--
-- Indices de la tabla `weekly_reports`
--
ALTER TABLE `weekly_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_report_intern` (`internship_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `chat_history`
--
ALTER TABLE `chat_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1001;

--
-- AUTO_INCREMENT de la tabla `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `internships`
--
ALTER TABLE `internships`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tracking_documents`
--
ALTER TABLE `tracking_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1004;

--
-- AUTO_INCREMENT de la tabla `vacancies`
--
ALTER TABLE `vacancies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `weekly_reports`
--
ALTER TABLE `weekly_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `fk_app_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_app_vacancy` FOREIGN KEY (`vacancy_id`) REFERENCES `vacancies` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `chat_history`
--
ALTER TABLE `chat_history`
  ADD CONSTRAINT `fk_chat_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `evaluations`
--
ALTER TABLE `evaluations`
  ADD CONSTRAINT `fk_eval_intern` FOREIGN KEY (`internship_id`) REFERENCES `internships` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `internships`
--
ALTER TABLE `internships`
  ADD CONSTRAINT `fk_intern_student` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `student_profiles`
--
ALTER TABLE `student_profiles`
  ADD CONSTRAINT `fk_profile_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `tracking_documents`
--
ALTER TABLE `tracking_documents`
  ADD CONSTRAINT `fk_doc_intern` FOREIGN KEY (`internship_id`) REFERENCES `internships` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `vacancies`
--
ALTER TABLE `vacancies`
  ADD CONSTRAINT `fk_vacancy_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_vacancy_teacher` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `weekly_reports`
--
ALTER TABLE `weekly_reports`
  ADD CONSTRAINT `fk_report_intern` FOREIGN KEY (`internship_id`) REFERENCES `internships` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
