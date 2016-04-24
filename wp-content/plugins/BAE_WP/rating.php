<?php

include("\inc\settings.php");
$db=new mysqli_connect($DBSERVER,$DBUSERNAME,$DBPASSWORD,$DB);

if ($db->connect_errno) {
    echo "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
}
class  rating extends BAE_Base {
	
	
	
	function __construct::($id=null,$field='id'){
		parent::__construct($id,$field);	
	}
	
	
	float function average($rating=$this) {
		if(is_array($rating->democraticContribution){
			return ((array_sum($rating->democraticContribution)+array_sum($rating->floorContribution)+array_sum($rating->generalConduct)+array_sum($rating->sponsoredProjects)+
					array_sum($rating->voterEngagement))/5)/count($rating->democraticContribution); 
		}
		else{
			return ($rating->democraticContribution+$rating->floorContribution+$rating->generalConduct+$rating->sponsoredProjects+
						$rating->voterEngagement)/5; 
		}
	}
	
	int function myRating() {
		return average(new rating($_SESSION['myId'],'userId'));
	}
	
	float function overTime(&$dataPoints,$startDate,$endDate=now()) {
				$diff=($endDate->format('U')-$startDate->format('U'))/count($dataPoints); //interval to measure for each data point. Assumes we're using dateTime objects.
				for($i=0;$i<count($dataPoints);$i++) {
					$dataPoints[$i]=average(new rating(array($startDate+($diff*$i),$startDate+$(diff*($i+1))),"creationDate"));//
				}
	}
	
	
	
	
	
	
}


?>