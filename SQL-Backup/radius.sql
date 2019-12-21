-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: 18 Sep 2019 pada 21.51
-- Versi Server: 10.1.38-MariaDB-0+deb9u1
-- PHP Version: 7.3.8-1+0~20190807.43+debian9~1.gbp7731bf

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `radius`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `nas`
--

CREATE TABLE `nas` (
  `id` int(10) NOT NULL,
  `nasname` varchar(128) NOT NULL,
  `shortname` varchar(32) DEFAULT NULL,
  `type` varchar(30) DEFAULT 'other',
  `ports` int(5) DEFAULT NULL,
  `secret` varchar(60) NOT NULL DEFAULT 'secret',
  `server` varchar(64) DEFAULT NULL,
  `community` varchar(50) DEFAULT NULL,
  `description` varchar(200) DEFAULT 'RADIUS Client'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `radacct`
--

CREATE TABLE `radacct` (
  `radacctid` bigint(21) NOT NULL,
  `acctsessionid` varchar(64) NOT NULL DEFAULT '',
  `acctuniqueid` varchar(32) NOT NULL DEFAULT '',
  `username` varchar(64) NOT NULL DEFAULT '',
  `realm` varchar(64) DEFAULT '',
  `nasipaddress` varchar(15) NOT NULL DEFAULT '',
  `nasportid` varchar(15) DEFAULT NULL,
  `nasporttype` varchar(32) DEFAULT NULL,
  `acctstarttime` datetime DEFAULT NULL,
  `acctupdatetime` datetime DEFAULT NULL,
  `acctstoptime` datetime DEFAULT NULL,
  `acctinterval` int(12) DEFAULT NULL,
  `acctsessiontime` int(12) UNSIGNED DEFAULT NULL,
  `acctauthentic` varchar(32) DEFAULT NULL,
  `connectinfo_start` varchar(50) DEFAULT NULL,
  `connectinfo_stop` varchar(50) DEFAULT NULL,
  `acctinputoctets` bigint(20) DEFAULT NULL,
  `acctoutputoctets` bigint(20) DEFAULT NULL,
  `calledstationid` varchar(50) NOT NULL DEFAULT '',
  `callingstationid` varchar(50) NOT NULL DEFAULT '',
  `acctterminatecause` varchar(32) NOT NULL DEFAULT '',
  `servicetype` varchar(32) DEFAULT NULL,
  `framedprotocol` varchar(32) DEFAULT NULL,
  `framedipaddress` varchar(15) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `radcheck`
--

CREATE TABLE `radcheck` (
  `id` int(11) UNSIGNED NOT NULL,
  `userid` varchar(100) NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `attribute` varchar(64) NOT NULL DEFAULT '',
  `op` char(2) NOT NULL DEFAULT '==',
  `value` varchar(253) NOT NULL DEFAULT '',
  `dateaddrad` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `radgroupcheck`
--

CREATE TABLE `radgroupcheck` (
  `id` int(11) UNSIGNED NOT NULL,
  `groupname` varchar(64) NOT NULL DEFAULT '',
  `attribute` varchar(64) NOT NULL DEFAULT '',
  `op` char(2) NOT NULL DEFAULT '==',
  `value` varchar(253) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `radgroupreply`
--

CREATE TABLE `radgroupreply` (
  `id` int(11) UNSIGNED NOT NULL,
  `groupname` varchar(64) NOT NULL DEFAULT '',
  `attribute` varchar(64) NOT NULL DEFAULT '',
  `op` char(2) NOT NULL DEFAULT '=',
  `value` varchar(253) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `radippool`
--

CREATE TABLE `radippool` (
  `id` int(11) UNSIGNED NOT NULL,
  `pool_name` varchar(30) NOT NULL,
  `framedipaddress` varchar(15) NOT NULL DEFAULT '',
  `nasipaddress` varchar(15) NOT NULL DEFAULT '',
  `calledstationid` varchar(30) NOT NULL,
  `callingstationid` varchar(30) NOT NULL,
  `expiry_time` datetime DEFAULT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `pool_key` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `radpostauth`
--

CREATE TABLE `radpostauth` (
  `id` int(11) NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `pass` varchar(64) NOT NULL DEFAULT '',
  `reply` varchar(32) NOT NULL DEFAULT '',
  `authdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `radreply`
--

CREATE TABLE `radreply` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `attribute` varchar(64) NOT NULL DEFAULT '',
  `op` char(2) NOT NULL DEFAULT '=',
  `value` varchar(253) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `radusergroup`
--

CREATE TABLE `radusergroup` (
  `id` int(20) NOT NULL,
  `guid` varchar(100) NOT NULL DEFAULT '',
  `username` varchar(64) NOT NULL DEFAULT '',
  `groupname` varchar(64) NOT NULL DEFAULT '',
  `priority` int(11) NOT NULL DEFAULT '1',
  `status` enum('disable','enable') NOT NULL DEFAULT 'enable'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `nas`
--
ALTER TABLE `nas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nasname` (`nasname`);

--
-- Indexes for table `radacct`
--
ALTER TABLE `radacct`
  ADD PRIMARY KEY (`radacctid`),
  ADD UNIQUE KEY `acctuniqueid` (`acctuniqueid`),
  ADD KEY `username` (`username`),
  ADD KEY `framedipaddress` (`framedipaddress`),
  ADD KEY `acctsessionid` (`acctsessionid`),
  ADD KEY `acctsessiontime` (`acctsessiontime`),
  ADD KEY `acctstarttime` (`acctstarttime`),
  ADD KEY `acctinterval` (`acctinterval`),
  ADD KEY `acctstoptime` (`acctstoptime`),
  ADD KEY `nasipaddress` (`nasipaddress`);

--
-- Indexes for table `radcheck`
--
ALTER TABLE `radcheck`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`(32));

--
-- Indexes for table `radgroupcheck`
--
ALTER TABLE `radgroupcheck`
  ADD PRIMARY KEY (`id`),
  ADD KEY `groupname` (`groupname`(32));

--
-- Indexes for table `radgroupreply`
--
ALTER TABLE `radgroupreply`
  ADD PRIMARY KEY (`id`),
  ADD KEY `groupname` (`groupname`(32));

--
-- Indexes for table `radippool`
--
ALTER TABLE `radippool`
  ADD PRIMARY KEY (`id`),
  ADD KEY `radippool_poolname_expire` (`pool_name`,`expiry_time`),
  ADD KEY `framedipaddress` (`framedipaddress`),
  ADD KEY `radippool_nasip_poolkey_ipaddress` (`nasipaddress`,`pool_key`,`framedipaddress`);

--
-- Indexes for table `radpostauth`
--
ALTER TABLE `radpostauth`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `radreply`
--
ALTER TABLE `radreply`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`(32));

--
-- Indexes for table `radusergroup`
--
ALTER TABLE `radusergroup`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `username` (`username`(32));

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `nas`
--
ALTER TABLE `nas`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `radacct`
--
ALTER TABLE `radacct`
  MODIFY `radacctid` bigint(21) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1642;
--
-- AUTO_INCREMENT for table `radcheck`
--
ALTER TABLE `radcheck`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;
--
-- AUTO_INCREMENT for table `radgroupcheck`
--
ALTER TABLE `radgroupcheck`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `radgroupreply`
--
ALTER TABLE `radgroupreply`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `radippool`
--
ALTER TABLE `radippool`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `radpostauth`
--
ALTER TABLE `radpostauth`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1780;
--
-- AUTO_INCREMENT for table `radreply`
--
ALTER TABLE `radreply`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;
--
-- AUTO_INCREMENT for table `radusergroup`
--
ALTER TABLE `radusergroup`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
