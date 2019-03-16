--
-- Table structure for table `users`
--
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `profile_pic` varchar(256) DEFAULT NULL,
  `role` varchar(20) NOT NULL,
  `company` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `firstname`, `lastname`, `profile_pic`, `role`, `company`, `created_at`, `modified_at`) VALUES
(1, 'krimal@test.com', '$2y$10$CmB4u7NLZ3Q49M/d/WxdPuPOftH0MBSkXGnx45xlRFMLPgwwVyVXa', 'Krimal', 'Patel', NULL, 'company', 'ABC corporation', '2019-03-16 11:58:38', '2019-03-16 12:00:03'),
(2, 'rupal@test.com', '$2y$10$tJZo9Vqi7WSxKXhNrPZTv.xI34Ngh6sTQIZuET1GIiNQMpKkKbPQS', 'Rupal', 'javiya', 'http://abc.com/mypic.png', 'admin', '', '2019-03-16 11:58:49', '2019-03-16 12:00:22');

-- --------------------------------------------------------

--
-- Table structure for table `user_address`
--

CREATE TABLE `user_address` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `thoroughfare` varchar(256) NOT NULL,
  `premise` varchar(256) NOT NULL,
  `postalCode` varchar(20) NOT NULL,
  `locality` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_address`
--

INSERT INTO `user_address` (`id`, `user_id`, `thoroughfare`, `premise`, `postalCode`, `locality`, `created_at`, `updated_at`) VALUES
(1, 1, '123, Imagine Street', 'Himani Avenue', '30021', 'C.G.Raod', '2019-03-16 12:01:22', '0000-00-00 00:00:00'),
(2, 2, '456, Navodeep Street', 'Parth Avenue', '30012', 'M.K.Raod', '2019-03-16 12:01:22', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `user_cv`
--

CREATE TABLE `user_cv` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` varchar(10) NOT NULL,
  `phd` int(1) NOT NULL,
  `abitur` int(50) NOT NULL,
  `birthday` date NOT NULL,
  `salaryDesired` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_cv`
--

INSERT INTO `user_cv` (`id`, `user_id`, `status`, `phd`, `abitur`, `birthday`, `salaryDesired`, `created_at`, `updated_at`) VALUES
(1, 1, 'inactive', 1, 10, '1986-03-06', '30000', '2019-03-16 12:03:07', '2019-03-16 12:14:54'),
(2, 2, 'inactive', 1, 15, '1986-03-18', '50000', '2019-03-16 12:03:07', '2019-03-16 12:14:39');

-- --------------------------------------------------------

--
-- Table structure for table `user_experience`
--

CREATE TABLE `user_experience` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `from` varchar(4) NOT NULL,
  `to` varchar(4) NOT NULL,
  `employer` varchar(50) NOT NULL,
  `work` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_experience`
--

INSERT INTO `user_experience` (`id`, `user_id`, `from`, `to`, `employer`, `work`, `created_at`, `updated_at`) VALUES
(1, 1, '2001', '2002', 'ABC coporation', 'Trainee', '2019-03-16 12:02:20', '0000-00-00 00:00:00'),
(2, 1, '2002', '2004', 'XYZ coporation', 'Junior Engineer', '2019-03-16 12:02:20', '0000-00-00 00:00:00');

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_address`
--
ALTER TABLE `user_address`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_cv`
--
ALTER TABLE `user_cv`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_experience`
--
ALTER TABLE `user_experience`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_address`
--
ALTER TABLE `user_address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_cv`
--
ALTER TABLE `user_cv`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_experience`
--
ALTER TABLE `user_experience`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for table `user_address`
--
ALTER TABLE `user_address`
  ADD CONSTRAINT `user_address_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_address_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `user_cv`
--
ALTER TABLE `user_cv`
  ADD CONSTRAINT `user_cv_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `user_experience`
--
ALTER TABLE `user_experience`
  ADD CONSTRAINT `user_experience_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);