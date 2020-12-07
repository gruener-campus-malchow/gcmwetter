<?php
header('Access-Control-Allow-Origin:*');
//session_start();


class WetterStationFrontend
{
	private $html;
	private $data;
	private $data2;
    private $source;

	public function __construct()
	{
		 $Title="Campus Wetterstation";
		 $Header= '<!doctype html> 
		 			<html> 
		 			<head> 
		 				<meta charset="utf-8"> 
		 				<title>Wetterstation am Grünen Campus Malchow</title>
                        <link rel="stylesheet" href="style.css" media="screen">
		 				<style> 
		 					body {margin: 3em;} 
		 					header {background: #eee; padding: 1em;} 
		 					article {padding: 2em;} 
		 				</style> 
	 				</head> 
	 				<body>'."\n\n\n";
		 $Foot='</body></html>';
		 
//		$this->source='http://fbi.gruener-campus-malchow.de/cis/api/wetterstation?getData=1&request=last1000Kombi';
		$this->source='http://fbi.gruener-campus-malchow.de/cis/api/wetterstation?getData=1&request=';
		if (isset($_GET['analyse']))
		{
			$this->data = $this->getData($_GET['analyse']);
		}
		else
		{
			$this->data = $this->getData('default');
			$this->data2 = $this->getData('Forschungscontainer');
		}
		
		$this->html=$Header.'<header><img src="wetterstation_banner.jpg" /></header>';
		
		$path='./querys/';
		$entrys=scandir($path);
	 	$analysen=array();
	 	$i=1;
	 	$this->html.='<h1>Analysen zum Herunterladen</h1>
	 		<table border="1">
	 			<tr>
	 				<th>Name</th><th>Beschreibung</th><th>Data</th><th>Material</th>';
		 foreach($entrys as $entry)
		 {
			if(is_dir($path.$entry) and $entry!='.' and $entry!='..')
			{
				array_push($analysen,$entry);
				
				$titel=file_get_contents($path.$entry.'/title.txt');
				if (strlen($titel)==0)
				{
					$titel='Kein Titel definiert! Interner Name: '.$entry;
				}
				$comment=file_get_contents($path.$entry.'/comment.txt');
				if (strlen($comment)==0)
				{
					$comment='Kein Kommentar hinterlegt. Sorry!';
				}
				
				$this->html.='<tr>
								<td>
									'.$titel.'
								</td>
								<td>
									'.$comment.'
								</td>
								<td>
									<a href="?download=1&analyse='.$entry.'">csv</a><br>
                                    <a href="'.$this->source.$entry.'">json</a>
								</td>
								<td>
									<a href="'.$path.$entry.'/query.sql" target="_blank">DB-Anfrage</a><br>';
								
				$aufgaben = scandir($path.$entry.'/aufgaben/');
				foreach ($aufgaben as $aufgabe)
				{
					if ($aufgabe!='.' and $aufgabe!='..')
					{
						$this->html .= '<a href="'.$path.$entry.'/aufgaben/.'.$aufgabe.'" target="_blank">'.$aufgabe.'</a><br>';
					}
				}
//				$this->html.=print_r($aufgaben, true)	;
				$this->html.='	</td>
								</tr>';
								
				//$i++;
				
			}
		 }
		 $this->html.='</table>';
		 
		if($_GET['download']==0)
		{
			//$this->html.='<table border="1"><th>time</th><th>value</th>';
			//foreach ($array as $element)
			//{
			//	$this->html .= '<tr><td>'.$element['timecode'].'</td><td>'.str_replace('.', ',', $element['wert']).'</td></tr>';
			//}
			//$this->html.='</table';
						
			$this->html.= $this->createTable($this->data,'Letzte 10 Messungen vom Kombisensor (Dach)');
			$this->html.= $this->createTable($this->data2,'Letzte 100 Messungen Dach am Forschungscontainer');			
		}
		$this->html.=$Foot;
	}
	public function getColumnNames()
	{
		$singleRow=$this->data[0];
		
		return array_keys($singleRow);
	}
	
	public function getHtml()
	{
		
		return $this->html;
	}
	public function getData($analyse)
	{
		//echo'<hr>'.$this->source.$analyse.'</hr>';
		$json=file_get_contents($this->source.$analyse);
		$array=json_decode($json,true);
		return $array;
	}
	
	private function createTable($tableAsArray, $title)
	{
		$html='<div class="std_table">';
		if(strlen($title)>0)
		{		
			$html.='<h1>'.$title.'</h1>';
		}
		$html.='<table class="std_table" border="1">';
		$first=true;
		foreach ($tableAsArray as $row)
		{
			if($first == true)
			{
				$html.='<tr>';
				$columnames = array_keys($row);
				foreach ($columnames as $name)
				{
					$html.='<th>'.$name.'</th>';
				}
				$html.='</tr>';
				$first=false;
			}
			$html.='<tr>';
			foreach ($row as $column)
			{
				$html.='<td>'.$column.'</td>';
			}
			$html.='</tr>';
		}
		
		return '</table>'.$html;
		
	}
	

}

$wsf = new WetterstationFrontend();

if ($_GET['download']==1)
{
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename='.$_GET['analyse'].'.csv');

	// create a file pointer connected to the output stream
	$output = fopen('php://output', 'w');

	// output the column headings
	
	$tableHead = $wsf->getColumnNames();
	
	fputcsv($output, $tableHead, ';', '"');

	// loop over the rows, outputting them
	$wetterdaten = $wsf->getData($_GET['analyse']);
	foreach ($wetterdaten as $date)
	{
		fputcsv($output, $date, ';', '"');
	}

 
}
else
{
	echo $wsf->getHtml();
}







?>

