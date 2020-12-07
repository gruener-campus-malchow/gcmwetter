<?php
session_start();

class WetterStation
{
	private $dbm;
	
	public function __construct()
	{
		$this->loadDatabase();
	}
	
	public function storeValues($values)
	{
		print_r($values);
		$record = explode(';', $values['sensordata']);
		print_r($record);
		$statement='
			INSERT INTO
				messwerte(sensoren_sensor_pk, wert, timecode) 
			VALUES
				(
					:sensor,
					:value,
					:timecode
				)	
			';
		$this->dbm->loadStatementFromOutside($statement);
		//store gcm0101-temp
		if($record[3]>0)
		{
			$this->dbm->bindValue('sensor', 1);
			$value = str_replace(',', '.', $record[3]);
			$this->dbm->bindValue('value', $value);
			$this->dbm->bindValue('timecode', $values['timestamp']);
			$this->dbm->executePreparedStatement();		
		}
		//store gcm0101-luftf
		if($record[11]>0)
		{
			$this->dbm->bindValue('sensor', 2);
			$value = str_replace(',', '.', $record[11]);
			$this->dbm->bindValue('value', $value);
			$this->dbm->bindValue('timecode', $values['timestamp']);
			$this->dbm->executePreparedStatement();		
		}
		//store gcm0102-temp
		if($record[4]>0)
		{
			$this->dbm->bindValue('sensor', 4);
			$value = str_replace(',', '.', $record[4]);
			$this->dbm->bindValue('value', $value);
			$this->dbm->bindValue('timecode', $values['timestamp']);
			$this->dbm->executePreparedStatement();		
		}
		//store gcm01kombi-temp
		if(strlen($record[19])>0)
		{
			$this->dbm->bindValue('sensor', 5);
			$value = str_replace(',', '.', $record[19]);
			$this->dbm->bindValue('value', $value);
			$this->dbm->bindValue('timecode', $values['timestamp']);
			$this->dbm->executePreparedStatement();		
		}
		//store gcm01kombi-luftf
		if(strlen($record[20])>0)
		{
			$this->dbm->bindValue('sensor', 6);
			$value = str_replace(',', '.', $record[20]);
			$this->dbm->bindValue('value', $value);
			$this->dbm->bindValue('timecode', $values['timestamp']);
			$this->dbm->executePreparedStatement();		
		}
		//store gcm01kombi-wind
		if(strlen($record[21])>0)
		{
			$this->dbm->bindValue('sensor', 7);
			$value = str_replace(',', '.', $record[21]);
			$this->dbm->bindValue('value', $value);
			$this->dbm->bindValue('timecode', $values['timestamp']);
			$this->dbm->executePreparedStatement();		
		}
		//store gcm01kombi-niederschlmenge (Wippe)
		if(strlen($record[22])>0)
		{
			$this->dbm->bindValue('sensor', 8);
			$value = str_replace(',', '.', $record[22]);
			$this->dbm->bindValue('value', $value);
			$this->dbm->bindValue('timecode', $values['timestamp']);
			$this->dbm->executePreparedStatement();		
		}
		//store gcm01kombi-regen
		if(strlen($record[23])>0)
		{
			$this->dbm->bindValue('sensor', 9);
			$value = str_replace(',', '.', $record[23]);
			$this->dbm->bindValue('value', $value);
			$this->dbm->bindValue('timecode', $values['timestamp']);
			$this->dbm->executePreparedStatement();		
		}
	
	}
	private function loadDatabase()
	{
		require_once '../lib/databasemanager.php';
		$this -> dbm = new DatabaseManager();
	}
	public function getData($getVars)
	{
		$path = '../wetterstation/querys/'.$getVars['request'].'/query.sql';
		$query = file_get_contents($path);

		if($getVars['debug']=='1')
		{
			echo'<hr>.DEBUG-MODE.<hr>';
			echo 'Path: '.$path.'<hr>Query:<textfield>'.$query.'</textfield>';
		}
		
		$this->dbm->loadStatementFromOutside($query);
		$this->dbm->executePreparedStatement();	
		$json = json_encode($this->dbm->fetchDataAssocFromStatement());
		return $json;
/*		
		if($getVars['request'] == 'last1000Kombi')
		{
			$statement='
				SELECT timecode FROM messwerte WHERE sensoren_sensor_pk=5 ORDER BY timecode DESC LIMIT 1000
			';
			$this->dbm->loadStatementFromOutside($statement);
			$this->dbm->executePreparedStatement();	
			$json = json_encode($this->dbm->fetchDataFromStatement());
			return $json;
		}
		if($getVars['request'] == 'last1000All')
		{
			$statement='
				SELECT 
					m1.timecode AS Zeit, 
					REPLACE(m1.wert,".",",") AS Temperatur, 
					m2.wert AS Luftfeuchte, 
					REPLACE(m3.wert,".",",") AS Windgeschwindigkeit,
					m4.wert AS Niederschlagsmenge,
					m5.wert AS Regen 
				FROM messwerte AS m1
				LEFT JOIN messwerte AS m2 ON m1.timecode = m2.timecode AND m1.sensoren_sensor_pk=5 AND m2.sensoren_sensor_pk=6
				LEFT JOIN messwerte AS m3 ON m1.timecode = m3.timecode AND m3.sensoren_sensor_pk=7
				LEFT JOIN messwerte AS m4 ON m1.timecode = m4.timecode AND m4.sensoren_sensor_pk=8
				LEFT JOIN messwerte AS m5 ON m1.timecode = m5.timecode AND m5.sensoren_sensor_pk=9
				WHERE m1.sensoren_sensor_pk=5	
				ORDER BY m1.timecode DESC LIMIT 1000
			';
			$this->dbm->loadStatementFromOutside($statement);
			$this->dbm->executePreparedStatement();	
			$json = json_encode($this->dbm->fetchDataAssocFromStatement());
			return $json;
		}
*/
	}
	
}


$ws = new Wetterstation();

$jsonString = $_POST['comment'];
$jsonArray = json_decode($jsonString, true);
//print_r($jsonArray);

if ($jsonArray['sensorID']=='gcmwetter01')
{
	//echo 'here';
	$ws->storeValues($jsonArray);
}

if($_GET['getData']==1)
{
	echo $ws->getData($_GET);
}

//print_r($_GET);
//echo'beng';
//print_r($_FILES);


?>
