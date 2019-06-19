<?php
	require_once './wurfl-cloud-client-php/src/autoload.php'; 
	$config = new ScientiaMobile\WurflCloud\Config();  
	
	// Since the API key has to be abstracted an object will be created and
	// call method getKey() from that object which will fetch the API key and
	// set it to configuration object
	include 'GetAPI.php';
	$api_key = new GetAPI();
	$config->api_key = $api_key->getKey();
 
	// The input UA text file which will be opened in read mode
	$inFile = fopen("UA.txt", "r");
	
	// The output TSV file which will be opened in write mode
	$outFile = fopen("output.tsv","w");
	
	// Assigning the header row for the output file
	fputcsv($outFile, array('User Agent','is_mobile','complete_device_name','form_factor' ),"\t");

	while (true) {
		// Reading each line and checking if it is the end of the file
		$agnt=fgets($inFile);
		
		// If it is the end of the file code will exit
		if ($agnt == "") {
			exit;
		}
		
		// If it is not the end of the file, then user agent will be 
		// passed as the parameter for WURFL cloud client
		$client = new ScientiaMobile\WurflCloud\Client($config);
		$client->setUserAgent($agnt);
		echo $agnt; 
		echo "\t \t";
		
		// The device capabilities will be retrieved and written into 
		// the output file
		$devCap = $client->getDeviceCapability('form_factor');
	    if ($client->getDeviceCapability('is_mobile')) {  
			echo "\t \t";
			$bool = "Yes";
		}
		else{
			echo "\t \t";
			$bool = "No";
		}
	    $devName = $client->getDeviceCapability('complete_device_name');
	    echo $devName; echo "\t \t"; 
	    echo $devCap;
	    $result = array( 'User Agent' => $agnt, 'is_mobile' => $bool, 'complete_device_name' => $devName, 'form_factor' =>$devCap );
	    fputcsv($outFile, $result,"\t");
	    echo "<br />\n";
	    echo "<br />\n"; 
	}
	fclose($inFile);
	fclose($outFile);
?>