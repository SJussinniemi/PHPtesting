CREATE TABLE `phptest`.`Contact` ( `ID` INT NOT NULL , `Job` VARCHAR(255) NOT NULL , `Firstname` VARCHAR(255) NOT NULL , `Lastname` VARCHAR(255) NOT NULL , PRIMARY KEY (`ID`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;

INSERT INTO `contact` (`ID`, `Job`, `Firstname`, `Lastname`) VALUES ('1', 'BossMan', 'Teemu', 'Terava');
INSERT INTO `contact` (`ID`, `Job`, `Firstname`, `Lastname`) VALUES ('2', 'Worker', 'Saija', 'Saamaton');