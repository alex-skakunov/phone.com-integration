CREATE TABLE `queue` (
  `id` int(10) UNSIGNED NOT NULL,
  `call_id` varchar(50) NOT NULL,
  `status` enum('queued','processing','error','success') NOT NULL DEFAULT 'queued',
  `error_message` text,
  `created_at` datetime NOT NULL,
  `processing_time` decimal(10,2) UNSIGNED DEFAULT NULL COMMENT 'in seconds'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `queue`
--
ALTER TABLE `queue`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `call_id_2` (`call_id`),
  ADD KEY `call_id` (`call_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `queue`
--
ALTER TABLE `queue`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
