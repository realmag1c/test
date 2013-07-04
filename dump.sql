DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
`user_id` int(11) unsigned NOT NULL auto_increment,
`user_login` varchar(30) NOT NULL,
`user_password` varchar(32) NOT NULL,
`user_email` varchar(50) NOT NULL,
`user_hash` varchar(32) NOT NULL,
`user_ip` int(10) unsigned NOT NULL default '0',
PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ; 


CREATE TABLE `news` (
`id` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`title` VARCHAR( 255 ) NOT NULL ,
`post` text NOT NULL,
`lan` VARCHAR( 255 ) NOT NULL 
) ENGINE = MYISAM ;
