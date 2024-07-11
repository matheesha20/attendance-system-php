
CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `working_days_per_month` int(11) NOT NULL,
  `work_days` varchar(255) NOT NULL,
  `work_start_time` time NOT NULL,
  `work_end_time` time NOT NULL,
  `salary_start_date` int(2) NOT NULL,
  `salary_end_date` int(2) NOT NULL,
  `ot_interval` int(11) NOT NULL,
  `regular_ot_days` varchar(255) NOT NULL,
  `double_ot_days` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `working_days_per_month`, `work_days`, `work_start_time`, `work_end_time`, `salary_start_date`, `salary_end_date`, `ot_interval`, `regular_ot_days`, `double_ot_days`) VALUES
(1, 20, 'Monday,Tuesday,Wednesday,Thursday,Friday', '08:00:00', '17:30:00', 26, 25, 30, 'Saturday', 'Sunday');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `in_time` time DEFAULT NULL,
  `off_time` time DEFAULT NULL,
  `type` enum('Present','Paid Leave','Unpaid Leave','Half Day','Short Leave','Absent') DEFAULT 'Present'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;



CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `emp_no` varchar(50) DEFAULT NULL,
  `basic_salary` decimal(10,2) DEFAULT NULL,
  `working_days_per_month` int(11) DEFAULT 20,
  `work_days` varchar(50) DEFAULT 'Monday,Tuesday,Wednesday,Thursday,Friday',
  `work_time_range` time DEFAULT '09:00:00',
  `work_start_time` time DEFAULT '09:00:00',
  `work_end_time` time DEFAULT '17:00:00',
  `salary_start_date` int(2) NOT NULL DEFAULT 1,
  `salary_end_date` int(2) NOT NULL DEFAULT 30,
  `ot_interval` int(11) NOT NULL DEFAULT 30,
  `regular_ot_days` varchar(255) NOT NULL DEFAULT '',
  `double_ot_days` varchar(255) NOT NULL DEFAULT '',
  `epf_rate` decimal(5,2) DEFAULT 8.00,
  `loan_amount` decimal(10,2) DEFAULT 0.00,
  `loan_deduction` decimal(10,2) DEFAULT 0.00,
  `loan_start_date` date DEFAULT NULL,
  `loan_duration` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `username`, `password`, `company`, `position`, `emp_no`, `basic_salary`, `working_days_per_month`, `work_days`, `work_time_range`, `work_start_time`, `work_end_time`, `salary_start_date`, `salary_end_date`, `ot_interval`, `regular_ot_days`, `double_ot_days`, `epf_rate`, `loan_amount`, `loan_deduction`, `loan_start_date`, `loan_duration`) VALUES
(1, 'Admin', 'User', 'admin', '$2y$10$hGueXxaBRW7tJLQ0NEMQXuPJy0uykZbbyiJcyGBtP8u5yAX3odZ5K', '', 'Administrator', '', 0.00, 20, 'Monday,Tuesday,Wednesday,Thursday,Friday', '09:00:00', '09:00:00', '17:00:00', 1, 30, 30, '', '', 0.00, 0.00, 0.00, NULL, 0);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=130;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
