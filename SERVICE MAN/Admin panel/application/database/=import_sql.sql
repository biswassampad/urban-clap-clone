-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 17, 2018 at 12:52 PM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.5.35

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `codecan_servpro_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

DROP TABLE IF EXISTS `address`;
CREATE TABLE `address` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `delivery_zipcode` varchar(255) NOT NULL,
  `delivery_address` longtext NOT NULL,
  `delivery_landmark` longtext CHARACTER SET utf8 NOT NULL,
  `delivery_fullname` varchar(255) CHARACTER SET utf8 NOT NULL,
  `delivery_mobilenumber` varchar(15) CHARACTER SET utf8 NOT NULL,
  `delivery_city` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

DROP TABLE IF EXISTS `appointment`;
CREATE TABLE `appointment` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `address_id` int(11) NOT NULL,
  `appointment_date` date NOT NULL,
  `start_time` time NOT NULL,
  `time_token` int(11) NOT NULL,
  `visit_at` time NOT NULL,
  `promo_code` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `payment_type` varchar(100) NOT NULL,
  `payment_ref` varchar(255) NOT NULL,
  `payment_mode` varchar(100) NOT NULL,
  `payment_amount` double NOT NULL,
  `discount` double NOT NULL,
  `total_amount` double NOT NULL,
  `extra_charges` double NOT NULL,
  `net_amount` double NOT NULL,
  `total_time` time NOT NULL,
  `pros_id` int(200) NOT NULL,
  `start_at` datetime NOT NULL,
  `end_at` datetime NOT NULL,
  `start_lat` double NOT NULL,
  `end_lat` double NOT NULL,
  `start_lon` double NOT NULL,
  `end_lon` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `appointment_extra`
--

DROP TABLE IF EXISTS `appointment_extra`;
CREATE TABLE `appointment_extra` (
  `appo_extra_id` int(11) NOT NULL,
  `appointment_id` int(55) NOT NULL,
  `title` varchar(255) NOT NULL,
  `charge` varchar(255) NOT NULL,
  `qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `appointment_services`
--

DROP TABLE IF EXISTS `appointment_services`;
CREATE TABLE `appointment_services` (
  `appointment_services_id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `service_qty` int(11) NOT NULL,
  `service_time` time NOT NULL,
  `service_amount` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `area_country`
--

DROP TABLE IF EXISTS `area_country`;
CREATE TABLE `area_country` (
  `country_id` int(11) NOT NULL,
  `country_name` varchar(128) NOT NULL,
  `iso_code_2` varchar(2) NOT NULL,
  `iso_code_3` varchar(3) NOT NULL,
  `address_format` text NOT NULL,
  `postcode_required` tinyint(1) NOT NULL,
  `currency` varchar(4) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `area_country`
--

INSERT INTO `area_country` (`country_id`, `country_name`, `iso_code_2`, `iso_code_3`, `address_format`, `postcode_required`, `currency`, `status`) VALUES
(1, 'Afghanistan', 'AF', 'AFG', '', 0, 'IND', 1),
(2, 'Albania', 'AL', 'ALB', '', 0, '', 0),
(3, 'Algeria', 'DZ', 'DZA', '', 0, '', 0),
(4, 'American Samoa', 'AS', 'ASM', '', 0, '', 0),
(5, 'Andorra', 'AD', 'AND', '', 0, '', 0),
(6, 'Angola', 'AO', 'AGO', '', 0, '', 0),
(7, 'Anguilla', 'AI', 'AIA', '', 0, '', 0),
(8, 'Antarctica', 'AQ', 'ATA', '', 0, '', 0),
(9, 'Antigua and Barbuda', 'AG', 'ATG', '', 0, '', 0),
(10, 'Argentina', 'AR', 'ARG', '', 0, '', 0),
(11, 'Armenia', 'AM', 'ARM', '', 0, '', 0),
(12, 'Aruba', 'AW', 'ABW', '', 0, '', 0),
(13, 'Australia', 'AU', 'AUS', '', 0, '', 0),
(14, 'Austria', 'AT', 'AUT', '', 0, '', 0),
(15, 'Azerbaijan', 'AZ', 'AZE', '', 0, '', 0),
(16, 'Bahamas', 'BS', 'BHS', '', 0, '', 0),
(17, 'Bahrain', 'BH', 'BHR', '', 0, '', 0),
(18, 'Bangladesh', 'BD', 'BGD', '', 0, '', 0),
(19, 'Barbados', 'BB', 'BRB', '', 0, '', 0),
(20, 'Belarus', 'BY', 'BLR', '', 0, '', 0),
(21, 'Belgium', 'BE', 'BEL', '{firstname} {lastname}\r\n{company}\r\n{address_1}\r\n{address_2}\r\n{postcode} {city}\r\n{country}', 0, '', 0),
(22, 'Belize', 'BZ', 'BLZ', '', 0, '', 0),
(23, 'Benin', 'BJ', 'BEN', '', 0, '', 0),
(24, 'Bermuda', 'BM', 'BMU', '', 0, '', 0),
(25, 'Bhutan', 'BT', 'BTN', '', 0, '', 0),
(26, 'Bolivia', 'BO', 'BOL', '', 0, '', 0),
(27, 'Bosnia and Herzegovina', 'BA', 'BIH', '', 0, '', 0),
(28, 'Botswana', 'BW', 'BWA', '', 0, '', 0),
(29, 'Bouvet Island', 'BV', 'BVT', '', 0, '', 0),
(30, 'Brazil', 'BR', 'BRA', '', 0, '', 0),
(31, 'British Indian Ocean Territory', 'IO', 'IOT', '', 0, '', 0),
(32, 'Brunei Darussalam', 'BN', 'BRN', '', 0, '', 0),
(33, 'Bulgaria', 'BG', 'BGR', '', 0, '', 0),
(34, 'Burkina Faso', 'BF', 'BFA', '', 0, '', 0),
(35, 'Burundi', 'BI', 'BDI', '', 0, '', 0),
(36, 'Cambodia', 'KH', 'KHM', '', 0, '', 0),
(37, 'Cameroon', 'CM', 'CMR', '', 0, '', 0),
(38, 'Canada', 'CA', 'CAN', '', 0, '', 0),
(39, 'Cape Verde', 'CV', 'CPV', '', 0, '', 0),
(40, 'Cayman Islands', 'KY', 'CYM', '', 0, '', 0),
(41, 'Central African Republic', 'CF', 'CAF', '', 0, '', 0),
(42, 'Chad', 'TD', 'TCD', '', 0, '', 0),
(43, 'Chile', 'CL', 'CHL', '', 0, '', 0),
(44, 'China', 'CN', 'CHN', '', 0, '', 0),
(45, 'Christmas Island', 'CX', 'CXR', '', 0, '', 0),
(46, 'Cocos (Keeling) Islands', 'CC', 'CCK', '', 0, '', 0),
(47, 'Colombia', 'CO', 'COL', '', 0, '', 0),
(48, 'Comoros', 'KM', 'COM', '', 0, '', 0),
(49, 'Congo', 'CG', 'COG', '', 0, '', 0),
(50, 'Cook Islands', 'CK', 'COK', '', 0, '', 0),
(51, 'Costa Rica', 'CR', 'CRI', '', 0, '', 0),
(52, 'Cote D''Ivoire', 'CI', 'CIV', '', 0, '', 0),
(53, 'Croatia', 'HR', 'HRV', '', 0, '', 0),
(54, 'Cuba', 'CU', 'CUB', '', 0, '', 0),
(55, 'Cyprus', 'CY', 'CYP', '', 0, '', 0),
(56, 'Czech Republic', 'CZ', 'CZE', '', 0, '', 0),
(57, 'Denmark', 'DK', 'DNK', '', 0, '', 0),
(58, 'Djibouti', 'DJ', 'DJI', '', 0, '', 0),
(59, 'Dominica', 'DM', 'DMA', '', 0, '', 0),
(60, 'Dominican Republic', 'DO', 'DOM', '', 0, '', 0),
(61, 'East Timor', 'TL', 'TLS', '', 0, '', 0),
(62, 'Ecuador', 'EC', 'ECU', '', 0, '', 0),
(63, 'Egypt', 'EG', 'EGY', '', 0, '', 0),
(64, 'El Salvador', 'SV', 'SLV', '', 0, '', 0),
(65, 'Equatorial Guinea', 'GQ', 'GNQ', '', 0, '', 0),
(66, 'Eritrea', 'ER', 'ERI', '', 0, '', 0),
(67, 'Estonia', 'EE', 'EST', '', 0, '', 0),
(68, 'Ethiopia', 'ET', 'ETH', '', 0, '', 0),
(69, 'Falkland Islands (Malvinas)', 'FK', 'FLK', '', 0, '', 0),
(70, 'Faroe Islands', 'FO', 'FRO', '', 0, '', 0),
(71, 'Fiji', 'FJ', 'FJI', '', 0, '', 0),
(72, 'Finland', 'FI', 'FIN', '', 0, '', 0),
(74, 'France, Metropolitan', 'FR', 'FRA', '{firstname} {lastname}\r\n{company}\r\n{address_1}\r\n{address_2}\r\n{postcode} {city}\r\n{country}', 1, '', 0),
(75, 'French Guiana', 'GF', 'GUF', '', 0, '', 0),
(76, 'French Polynesia', 'PF', 'PYF', '', 0, '', 0),
(77, 'French Southern Territories', 'TF', 'ATF', '', 0, '', 0),
(78, 'Gabon', 'GA', 'GAB', '', 0, '', 0),
(79, 'Gambia', 'GM', 'GMB', '', 0, '', 0),
(80, 'Georgia', 'GE', 'GEO', '', 0, '', 0),
(81, 'Germany', 'DE', 'DEU', '{company}\r\n{firstname} {lastname}\r\n{address_1}\r\n{address_2}\r\n{postcode} {city}\r\n{country}', 1, '', 0),
(82, 'Ghana', 'GH', 'GHA', '', 0, '', 0),
(83, 'Gibraltar', 'GI', 'GIB', '', 0, '', 0),
(84, 'Greece', 'GR', 'GRC', '', 0, '', 0),
(85, 'Greenland', 'GL', 'GRL', '', 0, '', 0),
(86, 'Grenada', 'GD', 'GRD', '', 0, '', 0),
(87, 'Guadeloupe', 'GP', 'GLP', '', 0, '', 0),
(88, 'Guam', 'GU', 'GUM', '', 0, '', 0),
(89, 'Guatemala', 'GT', 'GTM', '', 0, '', 0),
(90, 'Guinea', 'GN', 'GIN', '', 0, '', 0),
(91, 'Guinea-Bissau', 'GW', 'GNB', '', 0, '', 0),
(92, 'Guyana', 'GY', 'GUY', '', 0, '', 0),
(93, 'Haiti', 'HT', 'HTI', '', 0, '', 0),
(94, 'Heard and Mc Donald Islands', 'HM', 'HMD', '', 0, '', 0),
(95, 'Honduras', 'HN', 'HND', '', 0, '', 0),
(96, 'Hong Kong', 'HK', 'HKG', '', 0, '', 0),
(97, 'Hungary', 'HU', 'HUN', '', 0, '', 0),
(98, 'Iceland', 'IS', 'ISL', '', 0, '', 0),
(99, 'India', 'IN', 'IND', '', 0, 'INR', 1),
(100, 'Indonesia', 'ID', 'IDN', '', 0, '', 0),
(101, 'Iran (Islamic Republic of)', 'IR', 'IRN', '', 0, '', 0),
(102, 'Iraq', 'IQ', 'IRQ', '', 0, '', 0),
(103, 'Ireland', 'IE', 'IRL', '', 0, '', 0),
(104, 'Israel', 'IL', 'ISR', '', 0, '', 0),
(105, 'Italy', 'IT', 'ITA', '', 0, '', 0),
(106, 'Jamaica', 'JM', 'JAM', '', 0, '', 0),
(107, 'Japan', 'JP', 'JPN', '', 0, '', 0),
(108, 'Jordan', 'JO', 'JOR', '', 0, '', 0),
(109, 'Kazakhstan', 'KZ', 'KAZ', '', 0, '', 0),
(110, 'Kenya', 'KE', 'KEN', '', 0, '', 0),
(111, 'Kiribati', 'KI', 'KIR', '', 0, '', 0),
(112, 'North Korea', 'KP', 'PRK', '', 0, '', 0),
(113, 'Korea, Republic of', 'KR', 'KOR', '', 0, '', 0),
(114, 'Kuwait', 'KW', 'KWT', '', 0, '', 0),
(115, 'Kyrgyzstan', 'KG', 'KGZ', '', 0, '', 0),
(116, 'Lao People''s Democratic Republic', 'LA', 'LAO', '', 0, '', 0),
(117, 'Latvia', 'LV', 'LVA', '', 0, '', 0),
(118, 'Lebanon', 'LB', 'LBN', '', 0, '', 0),
(119, 'Lesotho', 'LS', 'LSO', '', 0, '', 0),
(120, 'Liberia', 'LR', 'LBR', '', 0, '', 0),
(121, 'Libyan Arab Jamahiriya', 'LY', 'LBY', '', 0, '', 0),
(122, 'Liechtenstein', 'LI', 'LIE', '', 0, '', 0),
(123, 'Lithuania', 'LT', 'LTU', '', 0, '', 0),
(124, 'Luxembourg', 'LU', 'LUX', '', 0, '', 0),
(125, 'Macau', 'MO', 'MAC', '', 0, '', 0),
(126, 'FYROM', 'MK', 'MKD', '', 0, '', 0),
(127, 'Madagascar', 'MG', 'MDG', '', 0, '', 0),
(128, 'Malawi', 'MW', 'MWI', '', 0, '', 0),
(129, 'Malaysia', 'MY', 'MYS', '', 0, '', 0),
(130, 'Maldives', 'MV', 'MDV', '', 0, '', 0),
(131, 'Mali', 'ML', 'MLI', '', 0, '', 0),
(132, 'Malta', 'MT', 'MLT', '', 0, '', 0),
(133, 'Marshall Islands', 'MH', 'MHL', '', 0, '', 0),
(134, 'Martinique', 'MQ', 'MTQ', '', 0, '', 0),
(135, 'Mauritania', 'MR', 'MRT', '', 0, '', 0),
(136, 'Mauritius', 'MU', 'MUS', '', 0, '', 0),
(137, 'Mayotte', 'YT', 'MYT', '', 0, '', 0),
(138, 'Mexico', 'MX', 'MEX', '', 0, '', 0),
(139, 'Micronesia, Federated States of', 'FM', 'FSM', '', 0, '', 0),
(140, 'Moldova, Republic of', 'MD', 'MDA', '', 0, '', 0),
(141, 'Monaco', 'MC', 'MCO', '', 0, '', 0),
(142, 'Mongolia', 'MN', 'MNG', '', 0, '', 0),
(143, 'Montserrat', 'MS', 'MSR', '', 0, '', 0),
(144, 'Morocco', 'MA', 'MAR', '', 0, '', 0),
(145, 'Mozambique', 'MZ', 'MOZ', '', 0, '', 0),
(146, 'Myanmar', 'MM', 'MMR', '', 0, '', 0),
(147, 'Namibia', 'NA', 'NAM', '', 0, '', 0),
(148, 'Nauru', 'NR', 'NRU', '', 0, '', 0),
(149, 'Nepal', 'NP', 'NPL', '', 0, '', 0),
(150, 'Netherlands', 'NL', 'NLD', '', 0, '', 0),
(151, 'Netherlands Antilles', 'AN', 'ANT', '', 0, '', 0),
(152, 'New Caledonia', 'NC', 'NCL', '', 0, '', 0),
(153, 'New Zealand', 'NZ', 'NZL', '', 0, '', 0),
(154, 'Nicaragua', 'NI', 'NIC', '', 0, '', 0),
(155, 'Niger', 'NE', 'NER', '', 0, '', 0),
(156, 'Nigeria', 'NG', 'NGA', '', 0, '', 0),
(157, 'Niue', 'NU', 'NIU', '', 0, '', 0),
(158, 'Norfolk Island', 'NF', 'NFK', '', 0, '', 0),
(159, 'Northern Mariana Islands', 'MP', 'MNP', '', 0, '', 0),
(160, 'Norway', 'NO', 'NOR', '', 0, '', 0),
(161, 'Oman', 'OM', 'OMN', '', 0, '', 0),
(162, 'Pakistan', 'PK', 'PAK', '', 0, '', 0),
(163, 'Palau', 'PW', 'PLW', '', 0, '', 0),
(164, 'Panama', 'PA', 'PAN', '', 0, '', 0),
(165, 'Papua New Guinea', 'PG', 'PNG', '', 0, '', 0),
(166, 'Paraguay', 'PY', 'PRY', '', 0, '', 0),
(167, 'Peru', 'PE', 'PER', '', 0, '', 0),
(168, 'Philippines', 'PH', 'PHL', '', 0, '', 0),
(169, 'Pitcairn', 'PN', 'PCN', '', 0, '', 0),
(170, 'Poland', 'PL', 'POL', '', 0, '', 0),
(171, 'Portugal', 'PT', 'PRT', '', 0, '', 0),
(172, 'Puerto Rico', 'PR', 'PRI', '', 0, '', 0),
(173, 'Qatar', 'QA', 'QAT', '', 0, '', 0),
(174, 'Reunion', 'RE', 'REU', '', 0, '', 0),
(175, 'Romania', 'RO', 'ROM', '', 0, '', 0),
(176, 'Russian Federation', 'RU', 'RUS', '', 0, '', 0),
(177, 'Rwanda', 'RW', 'RWA', '', 0, '', 0),
(178, 'Saint Kitts and Nevis', 'KN', 'KNA', '', 0, '', 0),
(179, 'Saint Lucia', 'LC', 'LCA', '', 0, '', 0),
(180, 'Saint Vincent and the Grenadines', 'VC', 'VCT', '', 0, '', 0),
(181, 'Samoa', 'WS', 'WSM', '', 0, '', 0),
(182, 'San Marino', 'SM', 'SMR', '', 0, '', 0),
(183, 'Sao Tome and Principe', 'ST', 'STP', '', 0, '', 0),
(184, 'Saudi Arabia', 'SA', 'SAU', '', 0, '', 0),
(185, 'Senegal', 'SN', 'SEN', '', 0, '', 0),
(186, 'Seychelles', 'SC', 'SYC', '', 0, '', 0),
(187, 'Sierra Leone', 'SL', 'SLE', '', 0, '', 0),
(188, 'Singapore', 'SG', 'SGP', '', 0, '', 0),
(189, 'Slovak Republic', 'SK', 'SVK', '{firstname} {lastname}\r\n{company}\r\n{address_1}\r\n{address_2}\r\n{city} {postcode}\r\n{zone}\r\n{country}', 0, '', 0),
(190, 'Slovenia', 'SI', 'SVN', '', 0, '', 0),
(191, 'Solomon Islands', 'SB', 'SLB', '', 0, '', 0),
(192, 'Somalia', 'SO', 'SOM', '', 0, '', 0),
(193, 'South Africa', 'ZA', 'ZAF', '', 0, '', 0),
(194, 'South Georgia &amp; South Sandwich Islands', 'GS', 'SGS', '', 0, '', 0),
(195, 'Spain', 'ES', 'ESP', '', 0, '', 0),
(196, 'Sri Lanka', 'LK', 'LKA', '', 0, '', 0),
(197, 'St. Helena', 'SH', 'SHN', '', 0, '', 0),
(198, 'St. Pierre and Miquelon', 'PM', 'SPM', '', 0, '', 0),
(199, 'Sudan', 'SD', 'SDN', '', 0, '', 0),
(200, 'Suriname', 'SR', 'SUR', '', 0, '', 0),
(201, 'Svalbard and Jan Mayen Islands', 'SJ', 'SJM', '', 0, '', 0),
(202, 'Swaziland', 'SZ', 'SWZ', '', 0, '', 0),
(203, 'Sweden', 'SE', 'SWE', '{company}\r\n{firstname} {lastname}\r\n{address_1}\r\n{address_2}\r\n{postcode} {city}\r\n{country}', 1, '', 0),
(204, 'Switzerland', 'CH', 'CHE', '', 0, '', 0),
(205, 'Syrian Arab Republic', 'SY', 'SYR', '', 0, '', 0),
(206, 'Taiwan', 'TW', 'TWN', '', 0, '', 0),
(207, 'Tajikistan', 'TJ', 'TJK', '', 0, '', 0),
(208, 'Tanzania, United Republic of', 'TZ', 'TZA', '', 0, '', 0),
(209, 'Thailand', 'TH', 'THA', '', 0, '', 0),
(210, 'Togo', 'TG', 'TGO', '', 0, '', 0),
(211, 'Tokelau', 'TK', 'TKL', '', 0, '', 0),
(212, 'Tonga', 'TO', 'TON', '', 0, '', 0),
(213, 'Trinidad and Tobago', 'TT', 'TTO', '', 0, '', 0),
(214, 'Tunisia', 'TN', 'TUN', '', 0, '', 0),
(215, 'Turkey', 'TR', 'TUR', '', 0, '', 0),
(216, 'Turkmenistan', 'TM', 'TKM', '', 0, '', 0),
(217, 'Turks and Caicos Islands', 'TC', 'TCA', '', 0, '', 0),
(218, 'Tuvalu', 'TV', 'TUV', '', 0, '', 0),
(219, 'Uganda', 'UG', 'UGA', '', 0, '', 0),
(220, 'Ukraine', 'UA', 'UKR', '', 0, '', 0),
(221, 'United Arab Emirates', 'AE', 'ARE', '', 0, '', 0),
(222, 'United Kingdom', 'GB', 'GBR', '', 1, '', 0),
(223, 'United States', 'US', 'USA', '{firstname} {lastname}\r\n{company}\r\n{address_1}\r\n{address_2}\r\n{city}, {zone} {postcode}\r\n{country}', 0, '', 0),
(224, 'United States Minor Outlying Islands', 'UM', 'UMI', '', 0, '', 0),
(225, 'Uruguay', 'UY', 'URY', '', 0, '', 0),
(226, 'Uzbekistan', 'UZ', 'UZB', '', 0, '', 0),
(227, 'Vanuatu', 'VU', 'VUT', '', 0, '', 0),
(228, 'Vatican City State (Holy See)', 'VA', 'VAT', '', 0, '', 0),
(229, 'Venezuela', 'VE', 'VEN', '', 0, '', 0),
(230, 'Viet Nam', 'VN', 'VNM', '', 0, '', 0),
(231, 'Virgin Islands (British)', 'VG', 'VGB', '', 0, '', 0),
(232, 'Virgin Islands (U.S.)', 'VI', 'VIR', '', 0, '', 0),
(233, 'Wallis and Futuna Islands', 'WF', 'WLF', '', 0, '', 0),
(234, 'Western Sahara', 'EH', 'ESH', '', 0, '', 0),
(235, 'Yemen', 'YE', 'YEM', '', 0, '', 0),
(237, 'Democratic Republic of Congo', 'CD', 'COD', '', 0, '', 0),
(238, 'Zambia', 'ZM', 'ZMB', '', 0, '', 0),
(239, 'Zimbabwe', 'ZW', 'ZWE', '', 0, '', 0),
(242, 'Montenegro', 'ME', 'MNE', '', 0, '', 0),
(243, 'Serbia', 'RS', 'SRB', '', 0, '', 0),
(244, 'Aaland Islands Test', 'AX', 'ALA', '', 0, '', 0),
(245, 'Bonaire, Sint Eustatius and Saba', 'BQ', 'BES', '', 0, '', 0),
(246, 'Curacao', 'CW', 'CUW', '', 0, '', 0),
(247, 'Palestinian Territory, Occupied', 'PS', 'PSE', '', 0, '', 0),
(248, 'South Sudan', 'SS', 'SSD', '', 0, '', 0),
(249, 'St. Barthelemy', 'BL', 'BLM', '', 0, '', 0),
(250, 'St. Martin (French part)', 'MF', 'MAF', '', 0, '', 0),
(251, 'Canary Islands', 'IC', 'ICA', '', 0, '', 0),
(252, 'Ascension Island (British)', 'AC', 'ASC', '', 0, '', 0),
(253, 'Kosovo, Republic of', 'XK', 'UNK', '', 0, '', 0),
(254, 'Isle of Man', 'IM', 'IMN', '', 0, '', 0),
(255, 'Tristan da Cunha', 'TA', 'SHN', '', 0, '', 0),
(256, 'Guernsey', 'GG', 'GGY', '', 0, '', 0),
(257, 'Jersey', 'JE', 'JEY', '', 0, '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `parent` int(50) NOT NULL,
  `leval` int(50) NOT NULL,
  `description` longtext NOT NULL,
  `image` varchar(200) NOT NULL,
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `title`, `slug`, `parent`, `leval`, `description`, `image`, `status`) VALUES
(2, 'Plumbing', '', 0, 0, 'Plumbing and all maintenance and repairing services', '5f07aa6804af019dfb35e5824a4f69da_img_plumbing.png', ''),
(3, 'Cleaning', '', 0, 0, 'Home, Office, Apartment all cleaning services. ', 'af26168bf1ccc98e0ccb5e1f0b09b337_img_cleaning.png', ''),
(4, 'Painting and Color', '', 0, 0, 'Paint your wall, floor etc..', '0744813897adf57859c6ad37e2a1ce86_img_painting.png', ''),
(5, 'Home Appliances', '', 0, 0, 'Repair and maintain all your home appliances... ', 'efcead39213423377add3dbd2db56f87_img_homeappliances.png', ''),
(6, 'Lighting and Decoration', '', 0, 0, 'Lighting and decoration on your occassions', '4b65936458ae5da357f42d0a0244d793_img_lighting_decore.png', '');

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ci_sessions`
--

INSERT INTO `ci_sessions` (`id`, `ip_address`, `timestamp`, `data`) VALUES
('h6o85s3kp7e9jbv686220h4g2pts2cm0', '::1', 1529153025, 0x5f5f63695f6c6173745f726567656e65726174657c693a313532393135323231363b6c616e67756167657c733a363a22617261626963223b757365725f6e616d657c733a383a22536572762050726f223b757365725f656d61696c7c733a31353a2261646d696e40676d61696c2e636f6d223b6c6f676765645f696e7c623a313b757365725f69647c733a323a223134223b757365725f747970655f69647c733a313a2231223b757365725f696d6167657c733a303a22223b),
('7bha4fe5kc8a1t38nlmgkcmrg80639mb', '::1', 1529218004, 0x5f5f63695f6c6173745f726567656e65726174657c693a313532393231363039363b757365725f6e616d657c733a383a22536572762050726f223b757365725f656d61696c7c733a31353a2261646d696e40676d61696c2e636f6d223b6c6f676765645f696e7c623a313b757365725f69647c733a323a223134223b757365725f747970655f69647c733a313a2231223b757365725f696d6167657c733a303a22223b),
('edhpktvvgo3vuk2h3ng7gce0kbo691po', '::1', 1529219347, 0x5f5f63695f6c6173745f726567656e65726174657c693a313532393231393233343b757365725f6e616d657c733a383a22536572762050726f223b757365725f656d61696c7c733a31353a2261646d696e40676d61696c2e636f6d223b6c6f676765645f696e7c623a313b757365725f69647c733a323a223134223b757365725f747970655f69647c733a313a2231223b757365725f696d6167657c733a303a22223b),
('tbi7ron94166dg127aeosft3fil1fg9p', '::1', 1529230565, 0x5f5f63695f6c6173745f726567656e65726174657c693a313532393232383639323b757365725f6e616d657c733a383a22536572762050726f223b757365725f656d61696c7c733a31353a2261646d696e40676d61696c2e636f6d223b6c6f676765645f696e7c623a313b757365725f69647c733a323a223134223b757365725f747970655f69647c733a313a2231223b757365725f696d6167657c733a303a22223b);

-- --------------------------------------------------------

--
-- Table structure for table `leaves`
--

DROP TABLE IF EXISTS `leaves`;
CREATE TABLE `leaves` (
  `leave_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `reason` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
CREATE TABLE `logs` (
  `log_id` int(11) NOT NULL,
  `data_table` varchar(120) NOT NULL,
  `data_action` varchar(20) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `log` longtext NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `ip` varchar(15) NOT NULL,
  `tablepkid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`log_id`, `data_table`, `data_action`, `shop_id`, `log`, `user_id`, `created_at`, `ip`, `tablepkid`) VALUES
(226, 'users', 'add', 0, '', 0, '2018-05-13 07:03:11', '::1', 14),
(227, 'users', 'add', 0, '', 14, '2018-06-17 12:14:37', '::1', 15),
(228, 'pros', 'add', 0, '', 14, '2018-06-17 12:14:37', '::1', 15),
(229, 'users', 'add', 0, '', 14, '2018-06-17 12:41:38', '::1', 16),
(230, 'pros', 'add', 0, '', 14, '2018-06-17 12:41:38', '::1', 16);

-- --------------------------------------------------------

--
-- Table structure for table `offer`
--

DROP TABLE IF EXISTS `offer`;
CREATE TABLE `offer` (
  `offer_id` int(11) NOT NULL,
  `offer_title` varchar(200) NOT NULL,
  `offer_description` longtext NOT NULL,
  `offer_start_date` datetime NOT NULL,
  `offer_end_date` datetime NOT NULL,
  `offer_coupon` varchar(50) NOT NULL,
  `offer_status` int(11) NOT NULL,
  `offer_discount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

DROP TABLE IF EXISTS `options`;
CREATE TABLE `options` (
  `id` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `value` longtext NOT NULL,
  `type` varchar(30) NOT NULL,
  `autoload` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`id`, `name`, `value`, `type`, `autoload`) VALUES
(14, 'site_name', 'Serv Pro', 'site_settings', 1),
(15, 'default_country', 'IN', 'site_settings', 1),
(16, 'default_timezone', 'ASIA', 'site_settings', 1),
(17, 'date_default_timezone', 'Asia/Kolkata', 'site_settings', 1),
(18, 'dateformat', 'd-m-Y', 'site_settings', 1),
(19, 'noti_on_appointment_book', 'yes', 'site_settings', 1),
(20, 'noti_on_appointment_status', 'yes', 'site_settings', 1),
(21, 'email_on_appointment_book', 'yes', 'site_settings', 1),
(22, 'email_on_appointment_status', 'yes', 'site_settings', 1),
(23, 'payment_mod', 'cod', 'site_settings', 1),
(24, 'app_dateformat', 'dd-MM-yyyy', 'site_settings', 1),
(25, 'app_timeformat', 'HH:ss A', 'site_settings', 1),
(26, 'email_id', 'admin@gmail.com', 'site_settings', 1),
(27, 'fcm_topic', 'Serv_Pro', 'site_settings', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pageapp`
--

DROP TABLE IF EXISTS `pageapp`;
CREATE TABLE `pageapp` (
  `id` int(11) NOT NULL,
  `pg_title` varchar(200) CHARACTER SET utf8 NOT NULL,
  `pg_slug` varchar(100) CHARACTER SET utf8 NOT NULL,
  `pg_descri` longtext CHARACTER SET utf8 NOT NULL,
  `pg_status` int(50) NOT NULL,
  `pg_foot` int(50) NOT NULL,
  `crated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pageapp`
--

INSERT INTO `pageapp` (`id`, `pg_title`, `pg_slug`, `pg_descri`, `pg_status`, `pg_foot`, `crated_date`) VALUES
(1, 'contact us', 'contact-us', '', 1, 0, '2018-01-03 06:35:11'),
(2, 'about us', 'about-us', '', 1, 0, '2018-01-08 14:31:35'),
(3, 'terms and conditions', 'terms-conditions', '', 1, 0, '2018-01-08 14:31:35'),
(4, 'privacy policy', 'privacy-policy', '', 1, 0, '2018-01-08 14:31:35');

-- --------------------------------------------------------

--
-- Table structure for table `photos`
--

DROP TABLE IF EXISTS `photos`;
CREATE TABLE `photos` (
  `id` int(11) NOT NULL,
  `photo_title` varchar(200) NOT NULL,
  `photo_image` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pros`
--

DROP TABLE IF EXISTS `pros`;
CREATE TABLE `pros` (
  `id` int(11) NOT NULL,
  `pros_name` varchar(255) NOT NULL,
  `pros_email` varchar(255) NOT NULL,
  `pros_degree` varchar(50) NOT NULL,
  `pros_exp` decimal(10,0) NOT NULL,
  `pros_photo` varchar(255) NOT NULL,
  `pros_id_proof` varchar(255) NOT NULL,
  `is_qualified` int(55) NOT NULL,
  `working_hour_start` varchar(255) NOT NULL,
  `working_hour_end` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pros_category_rel`
--

DROP TABLE IF EXISTS `pros_category_rel`;
CREATE TABLE `pros_category_rel` (
  `pros_id` int(55) NOT NULL,
  `cat_id` int(55) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reviews` longtext NOT NULL,
  `ratings` double NOT NULL,
  `on_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `appointment_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

DROP TABLE IF EXISTS `schedule`;
CREATE TABLE `schedule` (
  `id` int(11) NOT NULL,
  `working_days` varchar(50) NOT NULL,
  `morning_time_start` time DEFAULT NULL,
  `morning_time_end` time DEFAULT NULL,
  `morning_tokens` int(11) NOT NULL,
  `afternoon_time_start` time DEFAULT NULL,
  `afternoon_time_end` time DEFAULT NULL,
  `afternoon_tokens` int(11) NOT NULL,
  `evening_time_start` time DEFAULT NULL,
  `evening_time_end` time DEFAULT NULL,
  `evening_tokens` int(11) NOT NULL,
  `book_type` enum('slot','queue') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`id`, `working_days`, `morning_time_start`, `morning_time_end`, `morning_tokens`, `afternoon_time_start`, `afternoon_time_end`, `afternoon_tokens`, `evening_time_start`, `evening_time_end`, `evening_tokens`, `book_type`) VALUES
(1, 'sun,mon,tue,wed,thu,fri,sat', '08:00:00', '06:30:00', 20, '15:00:00', '18:00:00', 20, '20:00:00', '22:00:00', 20, 'queue');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `service_title` varchar(120) NOT NULL,
  `service_price` double NOT NULL,
  `service_discount` double NOT NULL,
  `service_approxtime` time NOT NULL,
  `service_icon` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `cat_id`, `service_title`, `service_price`, `service_discount`, `service_approxtime`, `service_icon`) VALUES
(19, 2, 'Bathroom Feetings', 3000, 0, '05:30:00', 'be1ff7c5c164ca444a3849d58604c9bd_ic_services_14.png'),
(20, 2, 'Leakage Repair', 600, 0, '00:30:00', '5b6e56e996ed1433335d10fd45bb30c1_ic_services_16.png'),
(21, 2, 'General Pipe fittings', 2400, 0, '12:00:00', 'e8660cba1efbef1b2c4142ce6edf2a39_ic_services_15.png'),
(23, 2, 'Tap fitting and repair', 140, 5, '02:33:00', 'fbe44718e6fff05abe8ccae691d9c273_ic_services_17.png'),
(24, 3, 'Complete Home Clean', 3000, 5, '12:50:00', '6144ddbef0e634a10cf9c93d70af415e_ic_services_59.png'),
(25, 2, 'Bathroom assesories', 2000, 0, '03:00:00', '07f4ce8d8163017c0b58bafea0a2f401_ic_services_18.png'),
(26, 2, 'Bath Tub', 1200, 0, '01:30:00', '5f480b7359b382d04e131cfefdd86562_ic_services_19.png'),
(27, 3, 'Bathroom Cleaning', 600, 0, '01:20:00', '628b740784bd100835005e98f3c5eeb5_ic_services_54.png'),
(28, 3, 'Reception Cleaning', 2000, 0, '03:30:00', '31f27932ff15bff06e08309b2328b1b0_ic_services_55.png'),
(29, 3, 'Kitchen Cleaning', 2300, 0, '02:30:00', '15d1ad960d8f2a64b0c7ef4892b692e4_ic_services_56.png'),
(30, 3, 'Office Cleaning', 4000, 0, '08:30:00', '62757b18314619c71796f7b964560d54_ic_services_57.png'),
(31, 3, 'Apartment Cleaning', 20000, 2, '08:30:00', '71eb8c603f8c450c6ca7e5ddf55c0b11_ic_services_58.png'),
(32, 3, 'Garden Cleaning', 3000, 0, '04:30:00', '79cb1e9674866c04ddfe06f575f821bf_ic_services_62.png'),
(33, 5, 'T.V.', 500, 0, '00:30:00', 'e666667315740492afea26014ac4b329_ic_services_32.png'),
(34, 5, 'Fan', 300, 0, '00:20:00', 'b515f91aafe4fe05c41cab669e9fe520_ic_services_34.png'),
(35, 5, 'Washing machine', 600, 0, '00:30:00', '373ed320d8c868d324bde78e6708c7c1_ic_services_35.png'),
(36, 5, 'Air Conditionar', 1200, 0, '01:30:00', '42b3f936c2efff818c1cea61ec9fa78d_ic_services_36.png'),
(37, 5, 'Refrigerator', 400, 0, '00:30:00', '4815f6ff25f0c98f3c710a88338491a8_ic_services_37.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_fullname` varchar(255) NOT NULL,
  `user_password` longtext NOT NULL,
  `user_type_id` int(11) NOT NULL,
  `user_bdate` date NOT NULL,
  `user_phone` varchar(30) NOT NULL,
  `is_email_varified` int(11) NOT NULL,
  `varified_token` varchar(255) NOT NULL,
  `user_gcm_code` longtext NOT NULL,
  `user_ios_token` longtext NOT NULL,
  `user_status` int(11) NOT NULL,
  `user_image` varchar(255) NOT NULL,
  `user_city` int(11) NOT NULL,
  `user_country` int(11) NOT NULL,
  `user_state` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `user_types`
--

DROP TABLE IF EXISTS `user_types`;
CREATE TABLE `user_types` (
  `user_type_id` int(11) NOT NULL,
  `user_type_title` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_types`
--

INSERT INTO `user_types` (`user_type_id`, `user_type_title`) VALUES
(1, 'Admin'),
(2, 'Pros'),
(3, 'User');

-- --------------------------------------------------------

--
-- Table structure for table `user_type_access`
--

DROP TABLE IF EXISTS `user_type_access`;
CREATE TABLE `user_type_access` (
  `user_type_id` int(11) NOT NULL,
  `class` varchar(30) NOT NULL,
  `method` varchar(30) NOT NULL,
  `access` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_type_access`
--

INSERT INTO `user_type_access` (`user_type_id`, `class`, `method`, `access`) VALUES
(1, 'Access', '*', 1),
(1, 'Access', 'user_access', 1),
(1, 'Access', 'user_types', 1),
(1, 'Access', 'user_type_delete', 1),
(1, 'Admin', '*', 1),
(1, 'Admin', 'check_email_exist', 1),
(1, 'Admin', 'dashboard', 1),
(1, 'Admin', 'delete_user', 1),
(1, 'Admin', 'edit_user', 1),
(1, 'Admin', 'signout', 1),
(1, 'Appointment', '*', 1),
(1, 'Appointment', 'add', 1),
(1, 'Appointment', 'ajax_edit', 1),
(1, 'Appointment', 'ajax_edit_extra', 1),
(1, 'Appointment', 'delete', 1),
(1, 'Appointment', 'details', 1),
(1, 'Appointment', 'edit', 1),
(1, 'Appointment', 'extra_add', 1),
(1, 'Appointment', 'extra_delete', 1),
(1, 'Appointment', 'extra_update', 1),
(1, 'Appointment', 'index', 1),
(1, 'Appointment', 'services_delete', 1),
(1, 'Appointment', 'services_update', 1),
(1, 'Appointment', 'service_add', 1),
(1, 'Appointment', 'view', 1),
(1, 'Appuser', '*', 1),
(1, 'Appuser', 'index', 1),
(1, 'Category', '*', 1),
(1, 'Category', 'add', 1),
(1, 'Category', 'delete', 1),
(1, 'Category', 'edit', 1),
(1, 'Category', 'index', 1),
(1, 'Crop', '*', 1),
(1, 'Crop', 'image', 1),
(1, 'Leave', '*', 1),
(1, 'Leave', 'add', 1),
(1, 'Leave', 'delete_leave', 1),
(1, 'Leave', 'edit', 1),
(1, 'Leave', 'index', 1),
(1, 'Login', '*', 1),
(1, 'Notification', '*', 1),
(1, 'Notification', 'index', 1),
(1, 'Offer', '*', 1),
(1, 'Offer', 'add', 1),
(1, 'Offer', 'delete_offer', 1),
(1, 'Offer', 'edit', 1),
(1, 'Offer', 'index', 1),
(1, 'Pageapp', '*', 1),
(1, 'Pageapp', 'add', 1),
(1, 'Pageapp', 'deletepage', 1),
(1, 'Pageapp', 'edit', 1),
(1, 'Pageapp', 'index', 1),
(1, 'Photos', '*', 1),
(1, 'Pros', '*', 1),
(1, 'Pros', 'add', 1),
(1, 'Pros', 'dashboard', 1),
(1, 'Pros', 'delete', 1),
(1, 'Pros', 'edit', 1),
(1, 'Pros', 'index', 1),
(1, 'Schedule', '*', 1),
(1, 'Schedule', 'add', 1),
(1, 'Schedule', 'delete', 1),
(1, 'Schedule', 'edit', 1),
(1, 'Schedule', 'index', 1),
(1, 'Services', '*', 1),
(1, 'Services', 'add', 1),
(1, 'Services', 'delete', 1),
(1, 'Services', 'edit', 1),
(1, 'Services', 'index', 1),
(1, 'Settings', '*', 1),
(1, 'Users', '*', 1),
(1, 'Users', 'add_user', 1),
(1, 'Users', 'change_password', 1),
(1, 'Users', 'delete_user', 1),
(1, 'Users', 'edit_user', 1),
(1, 'Users', 'forgot', 1),
(1, 'Users', 'index', 1),
(1, 'Users', 'listuser', 1),
(1, 'Users', 'modify_password', 1),
(2, 'Access', '*', 1),
(2, 'Admin', '*', 1),
(2, 'Appointment', '*', 1),
(2, 'Appointment', 'details', 1),
(2, 'Appointment', 'edit', 1),
(2, 'Appointment', 'extra_update', 1),
(2, 'Appointment', 'services_update', 1),
(2, 'Appuser', '*', 1),
(2, 'Category', '*', 1),
(2, 'Crop', '*', 1),
(2, 'Leave', '*', 1),
(2, 'Login', '*', 1),
(2, 'Notification', '*', 1),
(2, 'Offer', '*', 1),
(2, 'Pageapp', '*', 1),
(2, 'Photos', '*', 1),
(2, 'Pros', '*', 1),
(2, 'Pros', 'add', 1),
(2, 'Pros', 'dashboard', 1),
(2, 'Pros', 'delete', 1),
(2, 'Pros', 'edit', 1),
(2, 'Pros', 'index', 1),
(2, 'Schedule', '*', 1),
(2, 'Schedule', 'edit', 1),
(2, 'Schedule', 'index', 1),
(2, 'Services', '*', 1),
(2, 'Services', 'edit', 1),
(2, 'Services', 'index', 1),
(2, 'Users', '*', 1),
(2, 'Users', 'add_user', 1),
(2, 'Users', 'change_password', 1),
(2, 'Users', 'delete_user', 1),
(2, 'Users', 'edit_user', 1),
(2, 'Users', 'forgot', 1),
(2, 'Users', 'index', 1),
(2, 'Users', 'listuser', 1),
(2, 'Users', 'modify_password', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appointment_extra`
--
ALTER TABLE `appointment_extra`
  ADD PRIMARY KEY (`appo_extra_id`);

--
-- Indexes for table `appointment_services`
--
ALTER TABLE `appointment_services`
  ADD PRIMARY KEY (`appointment_services_id`),
  ADD UNIQUE KEY `busness_appointment_id` (`appointment_id`,`service_id`);

--
-- Indexes for table `area_country`
--
ALTER TABLE `area_country`
  ADD PRIMARY KEY (`country_id`),
  ADD UNIQUE KEY `country_id` (`country_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD KEY `ci_sessions_timestamp` (`timestamp`);

--
-- Indexes for table `leaves`
--
ALTER TABLE `leaves`
  ADD PRIMARY KEY (`leave_id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `offer`
--
ALTER TABLE `offer`
  ADD PRIMARY KEY (`offer_id`);

--
-- Indexes for table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `pageapp`
--
ALTER TABLE `pageapp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `photos`
--
ALTER TABLE `photos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pros`
--
ALTER TABLE `pros`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_email` (`user_email`);

--
-- Indexes for table `user_types`
--
ALTER TABLE `user_types`
  ADD PRIMARY KEY (`user_type_id`);

--
-- Indexes for table `user_type_access`
--
ALTER TABLE `user_type_access`
  ADD UNIQUE KEY `user_type_id` (`user_type_id`,`class`,`method`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `appointment_extra`
--
ALTER TABLE `appointment_extra`
  MODIFY `appo_extra_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `appointment_services`
--
ALTER TABLE `appointment_services`
  MODIFY `appointment_services_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `area_country`
--
ALTER TABLE `area_country`
  MODIFY `country_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=260;
--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `leaves`
--
ALTER TABLE `leaves`
  MODIFY `leave_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=231;
--
-- AUTO_INCREMENT for table `offer`
--
ALTER TABLE `offer`
  MODIFY `offer_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `options`
--
ALTER TABLE `options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT for table `pageapp`
--
ALTER TABLE `pageapp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `photos`
--
ALTER TABLE `photos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pros`
--
ALTER TABLE `pros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `user_types`
--
ALTER TABLE `user_types`
  MODIFY `user_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
