CREATE TABLE `mdl_ahe_deleted_events_log` (
  `id` bigint NOT NULL,
  `event` varchar(255) NOT NULL,
  `returnedobject` text,
  `contextid` bigint NOT NULL,
  `course_id` bigint NOT NULL,
  `action_done` varchar(255) NOT NULL,
  `timeaction` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE `mdl_ahe_deleted_events_log`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `mdl_ahe_deleted_events_log`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;
