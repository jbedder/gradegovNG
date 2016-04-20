<?php

include("\inc\settings.php");
$db=new mysqli_connect($DBSERVER,$DBUSERNAME,$DBPASSWORD,$DB);

if ($db->connect_errno) {
    echo "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
}
class  rating extends BAE_Base {
	
	
	
	function __construct::($id=null,$field=null){
		parent::__construct($id,$field);	
	}
	
	float function average {
		if(is_array($this->democraticContribution){
			return ((array_sum($this->democraticContribution)+array_sum($this->floorContribution)+array_sum($this->generalConduct)+array_sum($this->sponsoredProjects)+
						array_sum($this->voterEngagement))/5)/count($this->democraticContribution); 
		}
		else{
			return ($this->democraticContribution+$this->floorContribution+$this->generalConduct+$this->sponsoredProjects+
						$this->voterEngagement)/5; 
		}
	}
	
	int function myRating {
		return average(new rating($_SESSION['myId'],'userId'));
	}
	
	
	
	
	
	
}


?>