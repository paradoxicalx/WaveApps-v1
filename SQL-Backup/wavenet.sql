-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: 18 Sep 2019 pada 21.52
-- Versi Server: 10.1.38-MariaDB-0+deb9u1
-- PHP Version: 7.3.8-1+0~20190807.43+debian9~1.gbp7731bf

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wavenet`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_account`
--

CREATE TABLE `tb_account` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` varchar(500) NOT NULL,
  `balance` decimal(18,0) NOT NULL DEFAULT '0',
  `number` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `bankurl` varchar(200) NOT NULL,
  `memberlink` int(11) NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_col_account`
--

CREATE TABLE `tb_col_account` (
  `id` int(11) NOT NULL,
  `type` enum('all_account','account') NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `all_balance` int(100) NOT NULL,
  `transaction` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_devices`
--

CREATE TABLE `tb_devices` (
  `id` int(11) NOT NULL,
  `oid` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `model` varchar(100) NOT NULL,
  `catagory` enum('cpe','server','distribution','vm') NOT NULL,
  `area` varchar(100) NOT NULL,
  `ip` varbinary(16) NOT NULL,
  `member` int(11) NOT NULL,
  `useapi` enum('true','false') NOT NULL DEFAULT 'false',
  `apiname` varchar(100) NOT NULL,
  `apipass` varchar(100) NOT NULL,
  `autocheck` enum('true','false') NOT NULL DEFAULT 'false',
  `dateadd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('up','down','unknown') NOT NULL DEFAULT 'unknown',
  `lastup` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastdown` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `todaydown` int(100) NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_devices_oid`
--

CREATE TABLE `tb_devices_oid` (
  `id` int(11) NOT NULL,
  `oid` text NOT NULL,
  `comunity` varchar(100) NOT NULL DEFAULT 'public',
  `snmp_version` int(100) NOT NULL DEFAULT '1',
  `onstart` enum('0','1') NOT NULL,
  `router-name` varchar(100) NOT NULL,
  `router-version` varchar(100) NOT NULL,
  `total-memory` int(100) NOT NULL,
  `cpu` varchar(100) NOT NULL,
  `cpu-count` int(8) NOT NULL,
  `cpu-frequency` int(100) NOT NULL,
  `total-hdd` int(100) NOT NULL,
  `free-hdd` int(100) NOT NULL,
  `architecture-name` varchar(100) NOT NULL,
  `board-name` varchar(100) NOT NULL,
  `timezone` varchar(100) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_hs_template`
--

CREATE TABLE `tb_hs_template` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `css` text NOT NULL,
  `script_top` text NOT NULL,
  `script_bot` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_invoice`
--

CREATE TABLE `tb_invoice` (
  `id` int(100) NOT NULL,
  `identity` varchar(300) NOT NULL,
  `member` varchar(100) NOT NULL,
  `item` text NOT NULL,
  `date` date NOT NULL,
  `duedate` date NOT NULL,
  `total` decimal(18,0) NOT NULL DEFAULT '0',
  `subtotal` decimal(18,0) NOT NULL DEFAULT '0',
  `shipping` decimal(18,0) NOT NULL DEFAULT '0',
  `tax` int(3) NOT NULL DEFAULT '0',
  `paymentmethod` varchar(100) NOT NULL,
  `notes` text NOT NULL,
  `datepaid` date NOT NULL,
  `status` enum('paid','unpaid','refund') NOT NULL DEFAULT 'unpaid',
  `payto` int(100) NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `refundfrom` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_iplist`
--

CREATE TABLE `tb_iplist` (
  `id` int(11) NOT NULL,
  `ipaddress` varbinary(16) NOT NULL,
  `master` int(32) NOT NULL,
  `used` enum('1','0') NOT NULL DEFAULT '0',
  `useby` varchar(100) NOT NULL,
  `infid` varchar(100) NOT NULL,
  `type` enum('subnet','broadcast','host','other') NOT NULL DEFAULT 'other'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_ipmaster`
--

CREATE TABLE `tb_ipmaster` (
  `id` int(11) NOT NULL,
  `identity` text NOT NULL,
  `usage` varchar(100) NOT NULL,
  `notes` text NOT NULL,
  `netmask` varbinary(16) NOT NULL,
  `subnet` varbinary(16) NOT NULL,
  `broadcast` varbinary(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_log`
--

CREATE TABLE `tb_log` (
  `id` int(32) NOT NULL,
  `sesname` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `message` varchar(100) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_loginlog`
--

CREATE TABLE `tb_loginlog` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `date` datetime NOT NULL,
  `exp_date` datetime NOT NULL,
  `token` varchar(100) NOT NULL,
  `ipaddress` int(20) NOT NULL,
  `useragent` varchar(200) NOT NULL,
  `fingerprint` varchar(100) NOT NULL,
  `stat` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_maps`
--

CREATE TABLE `tb_maps` (
  `id` int(11) NOT NULL,
  `long` varchar(100) NOT NULL,
  `lat` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type` enum('ap','transmitter') NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_notes`
--

CREATE TABLE `tb_notes` (
  `id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `content` text NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_product`
--

CREATE TABLE `tb_product` (
  `id` int(8) NOT NULL,
  `name` varchar(200) NOT NULL,
  `price` decimal(18,0) NOT NULL DEFAULT '0',
  `type` enum('stuff','service') NOT NULL DEFAULT 'stuff',
  `rgroup` varchar(100) NOT NULL,
  `description` mediumtext NOT NULL,
  `image` text NOT NULL,
  `number` varchar(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_servicerule`
--

CREATE TABLE `tb_servicerule` (
  `id` int(11) NOT NULL,
  `device` varchar(64) NOT NULL,
  `attr` varchar(64) NOT NULL,
  `op` char(2) NOT NULL DEFAULT '=',
  `type` varchar(32) NOT NULL DEFAULT '',
  `info` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_setting`
--

CREATE TABLE `tb_setting` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `p1` varchar(200) DEFAULT NULL,
  `p2` varchar(200) DEFAULT NULL,
  `p3` varchar(200) DEFAULT NULL,
  `p4` varchar(200) DEFAULT NULL,
  `p5` varchar(200) DEFAULT NULL,
  `p6` varchar(200) DEFAULT NULL,
  `p7` varchar(200) DEFAULT NULL,
  `p8` varchar(200) NOT NULL,
  `p9` varchar(200) NOT NULL,
  `p10` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_ticket`
--

CREATE TABLE `tb_ticket` (
  `id` int(100) NOT NULL,
  `ticket_id` varchar(100) NOT NULL,
  `topic` enum('member','networking','billing','other') NOT NULL DEFAULT 'other',
  `creator` int(100) NOT NULL,
  `assign` int(100) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `member` int(100) NOT NULL,
  `device` int(100) NOT NULL,
  `invoice` int(100) NOT NULL,
  `title` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `priority` enum('critical','major','minor') NOT NULL,
  `status` enum('new','open','closed','pending','trash') DEFAULT 'new',
  `file` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_ticket_reply`
--

CREATE TABLE `tb_ticket_reply` (
  `id` int(11) NOT NULL,
  `ticketid` int(100) NOT NULL,
  `answerer` int(100) NOT NULL,
  `content` text NOT NULL,
  `file` varchar(100) NOT NULL,
  `ro_capture` varchar(100) NOT NULL,
  `hide` enum('0','1') NOT NULL DEFAULT '0',
  `edited` enum('0','1') NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_transfer`
--

CREATE TABLE `tb_transfer` (
  `id` int(11) NOT NULL,
  `from` varchar(200) NOT NULL,
  `to` varchar(200) NOT NULL,
  `nominal` decimal(18,0) NOT NULL DEFAULT '0',
  `description` mediumtext NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_translog`
--

CREATE TABLE `tb_translog` (
  `id` int(11) NOT NULL,
  `account` int(11) NOT NULL,
  `transid` int(11) NOT NULL,
  `type` enum('sales','deposit','expenses','newacc','transfer','wallet') NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `description` text NOT NULL,
  `amount` decimal(18,0) NOT NULL,
  `accbal` decimal(18,0) NOT NULL,
  `allbal` decimal(18,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_user`
--

CREATE TABLE `tb_user` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'inactive',
  `long` varchar(100) NOT NULL,
  `lat` varchar(100) NOT NULL,
  `image` varchar(200) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `notes` text NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `group` enum('admin','partner','customer') NOT NULL DEFAULT 'customer',
  `skin` varchar(32) NOT NULL DEFAULT 'skin-purple',
  `wallet` decimal(18,0) NOT NULL DEFAULT '0',
  `lastlogin` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_user_usage`
--

CREATE TABLE `tb_user_usage` (
  `id` int(32) NOT NULL,
  `user` varchar(100) NOT NULL,
  `dl` bigint(20) NOT NULL,
  `up` bigint(20) NOT NULL,
  `time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_wallet`
--

CREATE TABLE `tb_wallet` (
  `id` int(100) NOT NULL,
  `toacc` int(100) NOT NULL,
  `member` int(100) NOT NULL,
  `amount` decimal(18,0) NOT NULL,
  `description` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_account`
--
ALTER TABLE `tb_account`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_col_account`
--
ALTER TABLE `tb_col_account`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_devices`
--
ALTER TABLE `tb_devices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_devices_oid`
--
ALTER TABLE `tb_devices_oid`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_hs_template`
--
ALTER TABLE `tb_hs_template`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_invoice`
--
ALTER TABLE `tb_invoice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_iplist`
--
ALTER TABLE `tb_iplist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_ipmaster`
--
ALTER TABLE `tb_ipmaster`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_log`
--
ALTER TABLE `tb_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_loginlog`
--
ALTER TABLE `tb_loginlog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_maps`
--
ALTER TABLE `tb_maps`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_notes`
--
ALTER TABLE `tb_notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_product`
--
ALTER TABLE `tb_product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_servicerule`
--
ALTER TABLE `tb_servicerule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_setting`
--
ALTER TABLE `tb_setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_ticket`
--
ALTER TABLE `tb_ticket`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_ticket_reply`
--
ALTER TABLE `tb_ticket_reply`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_transfer`
--
ALTER TABLE `tb_transfer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_translog`
--
ALTER TABLE `tb_translog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_user_usage`
--
ALTER TABLE `tb_user_usage`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_wallet`
--
ALTER TABLE `tb_wallet`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_account`
--
ALTER TABLE `tb_account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `tb_col_account`
--
ALTER TABLE `tb_col_account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_devices`
--
ALTER TABLE `tb_devices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT for table `tb_devices_oid`
--
ALTER TABLE `tb_devices_oid`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT for table `tb_hs_template`
--
ALTER TABLE `tb_hs_template`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tb_invoice`
--
ALTER TABLE `tb_invoice`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=841998;
--
-- AUTO_INCREMENT for table `tb_iplist`
--
ALTER TABLE `tb_iplist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=969;
--
-- AUTO_INCREMENT for table `tb_ipmaster`
--
ALTER TABLE `tb_ipmaster`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT for table `tb_log`
--
ALTER TABLE `tb_log`
  MODIFY `id` int(32) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=930;
--
-- AUTO_INCREMENT for table `tb_loginlog`
--
ALTER TABLE `tb_loginlog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;
--
-- AUTO_INCREMENT for table `tb_maps`
--
ALTER TABLE `tb_maps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `tb_notes`
--
ALTER TABLE `tb_notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_product`
--
ALTER TABLE `tb_product`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `tb_servicerule`
--
ALTER TABLE `tb_servicerule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_setting`
--
ALTER TABLE `tb_setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_ticket`
--
ALTER TABLE `tb_ticket`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tb_ticket_reply`
--
ALTER TABLE `tb_ticket_reply`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `tb_transfer`
--
ALTER TABLE `tb_transfer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tb_translog`
--
ALTER TABLE `tb_translog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;
--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=160177;
--
-- AUTO_INCREMENT for table `tb_user_usage`
--
ALTER TABLE `tb_user_usage`
  MODIFY `id` int(32) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_wallet`
--
ALTER TABLE `tb_wallet`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
