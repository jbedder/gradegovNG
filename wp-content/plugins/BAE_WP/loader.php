<?php

include("\inc\settings.php");
$db=new mysqli_connect($DBSERVER,$DBUSERNAME,$DBPASSWORD,$DB);

if ($db->connect_errno) {
    echo "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
}
class BAE_Base {
	
	
	 BAE_Base function load($id=null, $field=null){
		 //$field must refer to a primary key.
		 $this->primary_key=$field;
		 if(!($query=$db->query("SELECT * FROM ".get_class($this)." Where ".$field."=".$id))){
			echo "Error creating ".get_class($this).": ".$db->connect_errno;
		 }
		 else {
			$result=mysqli_fetch_array($query);
			for each($result as $key=>$value){
				eval("$this->".$key."=".$value); //needs sanitizing
			}
		 }
		return $this;
	 }
	 BAE_Base function save(){
		  $primary_key=$this->primary_key;
		  unset($this->primary_key);
		  $vars=get_object_vars($this);
		  $selector="";
		  $primary_value=array_search($primary_key_value,array_flip($vars));
		  for each($vars as $key=>$value){
		    $selector.=$key."=\'".$value."\' ,";
		  }
		  $selector=rtrim($selector,",");
		  if(!($query=$db->query("UPDATE  ".get_class($this)." Where ".$primary_key."=".$primary_key_value))){
			echo "Error Updating ".get_class($this).": ".$db->connect_errno;
		 }
	 }
	function __construct($id=null,$field=null){
		$this->load($id, $field);	
	}
	
	
	
	
}


?>