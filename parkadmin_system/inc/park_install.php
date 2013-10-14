<?php
  
function DbInstallParkingSystem()
{
	global $wpdb;
	$prefix=$wpdb->prefix; 
	$tables="CREATE TABLE IF NOT EXISTS `".$prefix."lot` (
  `LotID` int(11) NOT NULL AUTO_INCREMENT,
  `Visible` int(11) NOT NULL,
  `LotName` varchar(100) NOT NULL,
  `LotShortName` varchar(50) NOT NULL,
  `Location` text NOT NULL,
  `Deposit` float NOT NULL,
  `YearlyCost` float NOT NULL,
  `QuarterlyCost` float NOT NULL,
  `MonthlyCost` float NOT NULL,
  `DailyCost` float NOT NULL,
  `Archived` datetime DEFAULT NULL,
  PRIMARY KEY (`LotID`),
  KEY `LotName` (`LotName`),
  KEY `Deposit` (`Deposit`),
  KEY `YearlyCost` (`YearlyCost`),
  KEY `QuarterlyCost` (`QuarterlyCost`),
  KEY `MonthlyCost` (`MonthlyCost`),
  KEY `DailyCost` (`DailyCost`),
  KEY `Archived` (`Archived`)
) DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `".$prefix."permit_user_relation` (
  `PURID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL,
  `PermitID` int(11) NOT NULL,
  `Ammount` float NOT NULL,
  `Duration` int(11) NOT NULL,
  `DurationType` varchar(20) NOT NULL,
  `RegisterDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `PaymentID` int(11) NOT NULL,
  `Status` varchar(20) NOT NULL,
  `PermitStartDate` datetime NOT NULL,
  PRIMARY KEY (`PURID`),
  KEY `PermitID` (`PermitID`),
  KEY `UserID` (`UserID`),
  KEY `Ammount` (`Ammount`),
  KEY `PaymentID` (`PaymentID`)
) DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
  
  
CREATE TABLE IF NOT EXISTS `".$prefix."paymenttransaction` (
  `PaymentID` int(11) NOT NULL AUTO_INCREMENT,
  `PURIDD` int(11) NOT NULL,
  `PermitID` int(11) NOT NULL,
  `PaymentType` int(11) NOT NULL,
  `ProcessingDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Confirmation` varchar(50) NOT NULL,
  `DepositAmmount` float NOT NULL,
  `PayerID` int(11) NOT NULL,
  `PermitAmmount` float NOT NULL,
  `TransactionID` varchar(200) NOT NULL,
  PRIMARY KEY (`PaymentID`),
  KEY `PermitID` (`PermitID`),
  KEY `DepositAmmount` (`DepositAmmount`),
  KEY `PayerID` (`PayerID`),
  KEY `PermitAmmount` (`PermitAmmount`),
  KEY `PURIDD` (`PURIDD`)
)  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

 
CREATE TABLE IF NOT EXISTS `".$prefix."permit` (
  `PermitId` int(11) NOT NULL AUTO_INCREMENT,
  `PermitNo` varchar(20) NOT NULL,
  `Lot` int(11) NOT NULL,
  `Archived` date DEFAULT NULL,
  `Visible` int(11) NOT NULL,
  PRIMARY KEY (`PermitId`),
  KEY `PermitNo` (`PermitNo`),
  KEY `Lot` (`Lot`),
  KEY `Visible` (`Visible`)
) DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
  
CREATE TABLE IF NOT EXISTS `".$prefix."vehicle` (
  `VehicleID` int(11) NOT NULL AUTO_INCREMENT,
  `Active` tinyint(1) NOT NULL,
  `PlateNumber` varchar(100) NOT NULL,
  `States` varchar(11) NOT NULL,
  `MakeID` int(11) NOT NULL,
  `TypeID` int(11) NOT NULL,
  `Colour` varchar(50) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `comments` text NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `Year` int(11) NOT NULL,
  PRIMARY KEY (`VehicleID`),
  KEY `PlateNumber` (`PlateNumber`),
  KEY `UserID` (`UserID`),
  KEY `MakeID` (`MakeID`),
  KEY `TypeID` (`TypeID`),
  KEY `Colour` (`Colour`),
  KEY `Year` (`Year`),
  KEY `created` (`created`)
) DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
 
 
CREATE TABLE IF NOT EXISTS `".$prefix."vehiclemaker` (
  `VehicleMakerID` int(11) NOT NULL AUTO_INCREMENT,
  `MakerName` varchar(100) NOT NULL,
  `Extinfo` text NOT NULL,
  PRIMARY KEY (`VehicleMakerID`),
  KEY `MakerName` (`MakerName`)
) DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

  
CREATE TABLE IF NOT EXISTS `".$prefix."vehicletype` (
  `VehicleTypeID` int(11) NOT NULL AUTO_INCREMENT,
  `TypeName` varchar(100) NOT NULL,
  `extinfo` text NOT NULL,
  PRIMARY KEY (`VehicleTypeID`),
  KEY `TypeName` (`TypeName`)
) DEFAULT CHARSET=latin1 AUTO_INCREMENT=1; ";
	
 	$Install_query=explode(';',$tables);
	foreach($Install_query as $v){
    $response=$wpdb->query(trim($v));
	}
 	return true;
 	}  

function DbDel()
{
	global $wpdb;
	$prefix=$wpdb->prefix; 
 
$tables="	
		DROP TABLE IF EXISTS `".$prefix."lot`;
		DROP TABLE IF EXISTS `".$prefix."permit_user_relation`;	  
		DROP TABLE IF EXISTS `".$prefix."paymenttransaction`;	  
		DROP TABLE IF EXISTS `".$prefix."permit`;	  
		DROP TABLE IF EXISTS `".$prefix."vehicle`;	
		DROP TABLE IF EXISTS `".$prefix."vehiclemaker`;
		DROP TABLE IF EXISTS `".$prefix."vehicletype`;";
	
$tables="DROP TABLE IF EXISTS `".$prefix."vehiclemaker`;  
		 DROP TABLE IF EXISTS `".$prefix."vehicletype`;";
 	
	$Install_query=explode(';',$tables);
	foreach($Install_query as $v)
			{
					$response=$wpdb->query($v);
			}
		}
	
function ContentsInstall()
{
	
	global $wpdb;
	$prefix=$wpdb->prefix; 
	$content="INSERT INTO `".$prefix."vehiclemaker`  ( MakerName ) VALUES ('Adam'),('Alfa Romeo'),
		('Audi'),('Bentley'),('BMW'),('Buick'),('Cadillac'),('Changan'),('Chery'),('Chevrolet'),('Chrysler'),('Citroen'),
		('Classic Cars'),('Daewoo'),('Daihatsu'),('Daimler'),('Datsun'),('DFSK'),('Dodge'),('FAW'),('Ferrari '),
		('Fiat'),('Ford'),('Geely'),('GMC'),('Golden Dragon'),('Golf'),('HILLMAN'),('Hino'),('Honda'),
		('Hummer'),('Hyundai'),('Isuzu'),('Jaguar'),('Jeep'),('Kia'),('Lamborghini'),('Land Rover'),('Lexus'),('Lincoln'),
		('Master'),('Mazda'),('Mercedes Benz'),('MG'),('MINI'),('Mitsubishi'),('Morris'),('Moto Guzzi '),('Nissan'),
		('Oldsmobile'),('Opel'),('Others'),('Peugeot'),('Plymouth'),('Pontiac'),('Porsche'),('Proton'),('Range Rover'),('Renault'),
		('Rolls-Royce'),('Roma'),('Rover'),('Royal Enfield'),('Saab'),('Scion'),('Skoda'),('SMART'),('Sogo'),
		('Sokon'),('SsangYong'),('Subaru'),('Suzuki'),('Toyota'),('Triumph'),('Vauxhall'),('Volkswagen'),('Volvo');
 		
		INSERT INTO `".$prefix."vehicletype`  ( TypeName ) VALUES 
		('Bicycle'),('Bus'),('Cargo Van'),('Compact'),('Dump Truck'),('Hatchback'),('Mid-Size'),('Mini Van'),
		('Motorcycle'),('Pickup Truck'),('Sedan'),('Sport Utility Vehicle'),('Sports Car'),('Station Wagon'),('Unknown'),('Van');" ;		
 		$Install_query=explode(';',$content);	
		foreach($Install_query as $v){ $response=$wpdb->query($v); }
	}

?>