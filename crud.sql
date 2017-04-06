--
-- Структура на таблица `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL,
  `first_name` varchar(64) NOT NULL,
  `last_name` varchar(64) NOT NULL,
  `created_on` date DEFAULT NULL,
  `status` tinyint(1) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Схема на данните от таблица `users`
--

INSERT INTO `users` (`id`, `username`, `first_name`, `last_name`, `created_on`, `status`) VALUES
(1, 'tonich', 'Tony', 'Tanchevski', '2016-09-25', 1),
(2, 'dina', 'Kostadinka', 'Miteva', '2016-09-25', 1),
(6, 'tweb', 'Antonio', 'Banderas', '2016-09-25', 1),
(9, 'pinko', 'Pink', 'Panter', '2016-09-25', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);


--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
