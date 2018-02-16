<?php
	// Include the autoloader - edit this path! 
	require_once './wurfl-cloud-client-php-master/src/autoload.php'; 
	// Create a configuration object  
	$config = new ScientiaMobile\WurflCloud\Config();  
	// Codes.php has the Cloud API Key 
	include 'Codes.php';
	// Creating an Object.
	$api_key = new Codes();

	// Set your WURFL Cloud API Key  
	$config->api_key = $api_key->getApiKey();   
 
	// Open the ua.txt in read mode.
	$fh = fopen("ua.txt", "r");

	// Open output.tsv in write mode.
	$fw = fopen("outputv2.tsv","w");

	// Gives the heading for each Tab.
	fputcsv($fw, array('User Agent','is_mobile','complete_device_name','form_factor' ),"\t");
	
	while (true) {
		// Extracting each line from ua.txt
		$test=fgets($fh);
		// If the line is null which implies end of file. 
		if ($test == "") {
			exit;
		}
		// Create the WURFL Cloud Client 
		$client = new ScientiaMobile\WurflCloud\Client($config); 
		// Set the User Agent 
		$headers = array('HTTP_USER_AGENT' => $test);
		$client->detectDevice($headers);
		//Check the compactabilities and store in variables.
		$result = $client->getDeviceCapability('form_factor');
	    $result2 = $client->getDeviceCapability('complete_device_name');
	    if ($client->getDeviceCapability('is_mobile')) {  
	    echo "<br/> \n";
	    echo "This is  mobile device";  
	    $result1 ="Yes";
		}
		else{
			echo "This is not a mobile device";
			$result1 ="No";
		}
	    echo $result2; echo "\t \t"; 
	    echo $result;

	    // Adding the device capabilities as an array and writing it to the file. 
	    $data = array(
	    	'User Agent' => $test,
	    	'is_mobile' => $result1,
	    	'complete_device_name' => $result2,
	    	'form_factor' =>$result );
	    fputcsv($fw, $data,"\t");
	    echo "<br />\n";
	    echo "<br />\n"; 
	}

	// Closing both the files.
	fclose($fh);
	fclose($fw);
?>