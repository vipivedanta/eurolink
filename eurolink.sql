-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 12, 2019 at 11:43 PM
-- Server version: 5.7.25-0ubuntu0.16.04.2
-- PHP Version: 7.0.33-0ubuntu0.16.04.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eurolink`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) UNSIGNED NOT NULL,
  `admin_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `description` text,
  `image` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `admin_id`, `title`, `slug`, `description`, `image`, `date`, `created_at`, `status`) VALUES
(1, 1, 'test news', NULL, 'this is a test description', NULL, '2019-04-25', '2019-04-12 04:20:54', 0),
(2, 1, 'Elections begins today', 'elections-begins-today', 'test newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest newstest news', NULL, '2019-04-17', '2019-04-12 04:22:16', 0),
(3, 1, 'This is today\'s big news', 'this-is-today\'s-big-news', 'wswswyxvx wb scxtrxw6 jhavaytx xbrheb  yxwxg  tex b xsxtgxb r sb xgw fxteded eddedededc eed', '190412115420139381.jpg', '2019-04-29', '2019-04-12 05:29:30', 1),
(4, 1, 'twbkjsbxjsxjsh', 'twbkjsbxjsxjsh', 'wswswyxvx wb scxtrxw6 jhavaytx xbrheb  yxwxg  tex b xsxtgxb r sb xgw fxt', NULL, '2019-04-25', '2019-04-12 05:30:27', 0),
(5, 1, 'twbkjsbxjsxjsh', 'twbkjsbxjsxjsh', 'wswswyxvx wb scxtrxw6 jhavaytx xbrheb  yxwxg  tex b xsxtgxb r sb xgw fxt', '190412110049267297.jpg', '2019-04-25', '2019-04-12 05:30:49', 0),
(6, 1, 'twbkjsbxjsxjsh', 'twbkjsbxjsxjsh', 'wswswyxvx wb scxtrxw6 jhavaytx xbrheb  yxwxg  tex b xsxtgxb r sb xgw fxt', '190412110115289406.jpg', '2019-04-25', '2019-04-12 05:31:15', 0),
(7, 1, 'test news two', 'test-news-two', 'test description test again', '', '2019-04-30', '2019-04-12 15:43:16', 1),
(8, 1, 'last test news again & again', 'last-test-news-again-&-again', 'jvjx  xbnxhsg b  vwysw s f edhv wbs wgs wgsw ws', '190412091349627287.jpg', '2019-04-16', '2019-04-12 15:43:49', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
