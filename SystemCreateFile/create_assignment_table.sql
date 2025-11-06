-- Create assignment Table
-- If you get a tablespace error, run this in phpMyAdmin:
-- 1. Go to phpMyAdmin
-- 2. Select your database (schoolerpbeta)
-- 3. Click on SQL tab
-- 4. Paste and run this SQL

-- First, try to drop the table if it exists (ignore errors)
DROP TABLE IF EXISTS `assignment`;

-- If you get a tablespace error, manually delete the .ibd file from your MySQL data directory
-- Or use this workaround: Create a dummy table, discard tablespace, then drop it
-- CREATE TABLE `assignment_temp` (`dummy` int) ENGINE=InnoDB;
-- ALTER TABLE `assignment_temp` DISCARD TABLESPACE;
-- DROP TABLE `assignment_temp`;

-- Now create the assignment table
CREATE TABLE `assignment` (
  `srno` int(11) NOT NULL AUTO_INCREMENT,
  `class` varchar(100) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `assignmentdate` date NOT NULL,
  `assignmentcompletiondate` date NOT NULL,
  `remark` varchar(1000) CHARACTER SET utf8 NOT NULL,
  `assignmentURL` varchar(1000) NOT NULL,
  `status` varchar(10) NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `response_date` date NOT NULL,
  `send_fcm` varchar(10) NOT NULL DEFAULT 'Pending',
  PRIMARY KEY (`srno`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;

