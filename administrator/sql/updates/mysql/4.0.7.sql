ALTER TABLE `#__eiko_einsatzberichte` CHANGE `article_id` `article_id` VARCHAR(255) NULL DEFAULT '0',
CHANGE `image` `image` VARCHAR(255) NULL,
CHANGE `address` `address` VARCHAR(255) NULL,
CHANGE `date1` `alarmierungszeit` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
CHANGE `date2` `ausfahrtszeit` DATETIME NULL DEFAULT '0000-00-00 00:00:00',
CHANGE `date3` `einsatzende` DATETIME NULL DEFAULT '0000-00-00 00:00:00',
CHANGE `summary` `summary` VARCHAR(255) NULL,
CHANGE `boss` `einsatzleiter` VARCHAR(255) NULL,
CHANGE `boss2` `einsatzfuehrer` VARCHAR(255) NULL,
CHANGE `people` `people` VARCHAR(255) NULL,
CHANGE `department` `department` INT(11) NULL,
CHANGE `desc` `desc` TEXT NULL,
CHANGE `alerting` `alarmierungsart` TEXT NOT NULL,
CHANGE `gmap_report_latitude` `gmap_report_latitude` VARCHAR(255) NULL,
CHANGE `gmap_report_longitude` `gmap_report_longitude` VARCHAR(255) NULL,
CHANGE `gmap` `gmap` VARCHAR(255) NULL,
CHANGE `status_fb` `status_fb` VARCHAR(255) NULL DEFAULT '1',
CHANGE `presse_label` `presse_label` VARCHAR(255) NULL DEFAULT 'Presselink',
CHANGE `presse` `presse` VARCHAR(255) NULL,
CHANGE `presse2_label` `presse2_label` VARCHAR(255) NULL DEFAULT 'Presselink',
CHANGE `presse2` `presse2` VARCHAR(255) NULL,
CHANGE `presse3_label` `presse3_label` VARCHAR(255) NULL DEFAULT 'Presselink',
CHANGE `presse3` `presse3` VARCHAR(255) NULL,
CHANGE `einsatzticker` `einsatzticker` VARCHAR(255) NULL,
CHANGE `notrufticker` `notrufticker` VARCHAR(255) NULL,
CHANGE `vehicles` `vehicles` TEXT NULL,
CHANGE `ausruestung` `ausruestung` TEXT NULL,
CHANGE `status` `status` VARCHAR(255) NULL,
CHANGE `tickerkat` `einsatzkategorie` INT(10) NOT NULL,
CHANGE `data1` `einsatzart` INT(10) NULL;
