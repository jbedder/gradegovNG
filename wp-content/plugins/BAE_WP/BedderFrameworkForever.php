<?php
//Bedder Object Base

namespace BOB;

class BOB {
    
    var $primaryReference
	
	//$ref should be a string corresponding to the primary identifier of the object.
	function setPrimaryReference($ref) {
		$this->primaryReference[gettype($ref)]=$ref;
	}
	
	function populateVars($array) {
		foreach($array as $key=>$data) {
            if(property_exists(get_class($this),$key)){
				if($data=="") {
					eval("\$this->".$key."='';");
				}
				else {
					eval("\$this->".$key."='".addslashes($data)."';");
				}	
            }		
		}
	}
	
	
	function asArray() {
		return get_object_vars($this);
	}
	
	//universal function to save objects to a DB schema mirroring their internal variables. Built in ability to update / save new
	//according to some predetermined variable.
	
	
	function save($refType="int",$dataSet="") {
		if(is_array($dataSet)) {
		
			$this->populateVars($array);
		}
		if(eval("\$this->".$this->primaryReference[$refType])!=""){	
			$sql="update ".get_class($this)." Set ";
			foreach(get_object_vars($this) as $key=>$value) {           
				$sql.=$key."='".$value."',";
			}
			$sql=substr($sql,0,strlen($sql)-1);
			$sql.=" Where ".$this->primaryReference[$refType]."='".eval("\$this->".$this->primaryReference[$refType])."'";
		}
		else {
			$sql="Insert Into ".get_class($this)." (";	
			if(is_array($dataSet)) {
				foreach($dataSet as $key=>$value) {
					if(property_exists(get_class($this),$key)){
						if($value=="") {
							eval("\$this->".$key."='';");
						}
						else {         	
							eval("\$this->".$key."='".addslashes($value)."';");
						
						}
				    
					}
				}
			}
            foreach(get_object_vars($this) as $key=> $value) {
				$columns.=$key.",";
                $values.="'".$value."',";
			}
			$columns=substr($columns,0,strlen($columns)-1);
			$values=substr($values,0,strlen($values)-1);
			$sql.=$columns.") Values (".$values.")";
	
	
		}
			
	}
	
//END SAVE METHOD

	function load($reference="") {
	   
	   if($reference!=""&&!is_array($reference)) {
		    if(is_numeric($reference)){		
				$query = "SELECT * FROM ".get_class($this)." WHERE ".$this->primaryReference[gettype($reference)]."= '$reference'";
			}
			else {
				$query = "SELECT * FROM ".get_class($this)." WHERE ". $this->primaryReference[gettype($reference)]." LIKE '$reference'";
			}
			$result = mysql_query($query);
			$this->populateVars(mysql_fetch_array($result));
		}
	}
	
	
}

class GeoObject extends BOB{
    
	var $address;
	var $city;
	var $state;
	var $zipCode;
	var $country;
	
	function normalize($array) {
		$highway="/(\s|\A)(highway|hwy|Hwy)\.?(\s|\z) (\d+)/i";
		$street="/(\s|\A)(st|ST|St|street)\.?(\s|\z)/";
		$avenue="/(\s|\A)(ave|ave|avenue)\.?(\s|\z)/";
		$boulevard="/(\s|\A)(blvd|blvd|Blvd)\.?(\s|\z)/";
		$road="/(\s|\A)(rd|Rd)\.?(\s|\z)/";
		$direction['s']="/(\s|\A)(s|S)\.?(\s|\z)/";
		$direction['n']="/(\s|\A)(n|N)\.?(\s|\z)/";
		$direction['e']="/(\s|\A)(e|E)\.?(\s|\z)/";
		$direction['w']="/(\s|\A)(w|W)\.?(\s|\z)/";
		$direction['ne']="/(\s|\A)(ne|NE|Ne)\.?(\s|\z)/";
		$direction['nw']="/(\s|\A)(nw|NW|Nw)\.?(\s|\z)/";
		$direction['se']="/(\s|\A)(se|SE|Se)\.?(\s|\z)/";
		$direction['sw']="/(\s|\A)(sw|SW|Sw)\.?(\s|\z)/";
		$dir['s']=" South ";
		$dir['n']=" North ";
		$dir['w']=" West ";
		$dir['e']=" East ";
		$dir['ne']=" Northeast ";
		$dir['nw']=" Northwest ";
		$dir['se']=" Southeast ";
		$dir['sw']=" Southwest ";
	
	
		$this->address=preg_replace($highway,"$4/US ",strtolower($this->address));
		$this->address=preg_replace($street," Street ",strtolower($this->address));
		$this->address=preg_replace($avenue," Avenue ",strtolower($this->address));
		$this->address=preg_replace($boulevard," Boulevard ",strtolower($this->address));
		$this->address=preg_replace($direction, $dir,strtolower($this->address));
		$this->address=preg_replace($road," Road",strtolower($this->address));
	}
	
	function prepareCURLConnection(&$ch){
		curl_setopt($ch,CURLOPT_RETURNTRANSFER , TRUE); // return value instead of direct output.
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION, FALSE);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 5);
		
	}
	function nominatimLookup(){
	    $tries=array("http://nominatim.openstreetmap.org/search/".$address[0]."/".ltrim(str_replace(" ","%20",$address[1]))."/".rtrim(str_replace(" ","%20",$location['City']))."?format=json&addressdetails=1&limit=1",
		"http://nominatim.openstreetmap.org/search/".$address[0]."/".ltrim(str_replace(" ","%20",$address[1]))."?format=json&addressdetails=1&limit=1");
		$ch = curl_init(); 
		$resultSet=array();

	    $this->prepareCURLConnection($ch);
		$address=explode(" ",$this->address,2);
	
	
      
		$address[1]=$this->normalize($address[1]);
		//nomantim is very finicky. in the ?q format it uses '+'. The structured format requires "%20"
		//replace spaces with url equivalent for both street name and city. That ensures that a city such as Los Angeles will be processed correctly.
		
		$i=0;
	    while((empty($resultSet)||$resultSet=="[]")&&$tries) {
			curl_setopt($ch, CURLOPT_URL, $tries[$i]);
			$resultSet=curl_exec($ch);
			$i++;
        }
       
		curl_close($ch);

		return json_decode($resultSet,true);
	}
	
	function googleLookup(){
	    $ch = curl_init(); 
		$resultSet=array();
		$this->prepareCURLConnection($ch);
		
		$url="http://maps.googleapis.com/maps/api/geocode/json?address=".$address[0]."+".ltrim(str_replace(" ","+",$address[1])).",+".rtrim(str_replace(" ","+",$location['City'])).",+".rtrim(str_replace(" ","+",$location['State']));
		curl_setopt($ch, CURLOPT_URL, $url); //uncomment
		$resultSet=curl_exec($ch);//uncomment
		//echo($resultSet);
	}
}

//single depth list;
class BList extends BOB{
	var &$parent;
	var &$child;
	
	function __construct($parent=NULL){
		$this->parent=$parent;
		$this->child=NULL;
	}
	function add() { //C of CRUD
		$this->child=new BList($this);
	}
	function delete() { //D of CRUD
		$this->parent->child=$this->child;
		$this->child->parent=$this->parent;
		unset($this);
	}
	
	function first() { //return first object of the list
		while($this->parent!=NULL) {
			$this=$this->parent;
		}
		return $this;
	}
	function last() { //return last object of the list
		while($this->child!=NULL) {
			$this=$this->child;
		}
		return $this;
	}
	
	function moveDown() { //Update List Order
	   $temp=&$this->parent;
	   if(!($this===$this->first())){
		$temp->child=$this->child; //switch prev next with this next.
		$this->parent=$temp->parent; //switch this prev with prev prev
		$temp->parent=$this; //set this as prev prev
		$this->child=$temp; //set this next as prev
	   }
	}
	
	function moveUp() { //Update List Order
		$temp=&$this->child;
		if(!($this===$this->last())){
		    $temp->parent=$this->parent; //switch next prev with this prev
		    $this->child=$temp=>child; //set this next as next next
			$temp->child=$this; //switch next next with this.
			$this->parent=$temp; //set this prev as next
		}
	
	}
	function next() {
		return $this->child;
	}
	
	function prev() {
		
		return $this->parent;
	}
	
	abstract function find() {
	      //while traversal is universal, the search criteria is variable.
	}
}

class BField extends BList {
	var $name;
	var $type;
	var $value;
	
   function __construct($parent=NULL,$type=NULL,$name=NULL) {
		$this->parent=$parent;
		$this->child=NULL;
		$this->type=$type;
		$this->name=$name;
		$this->value="";
   }
   
   function find($name) {
		$index=$this->first();
		while($index->name!=$name){
			$index=$index->next();
		}
		return($index);
   }
   function value($value) {
		$this->value=$value;
   }
   
}

class BText extends BField {

	function render() {
	
		return("<input type='text' name='".$this->name."'>".$this->value."</input>");
	}
}
class BTextArea extends BField {
	function render() {
	
		return("<textarea name='".$this->name."'>".$this->value."</textarea>");
	}
	
}

class BCheckBox extends BField {
	var $checked;

	function render() {
		return("<input type='checkbox' name='".$this->name."'>".$this->value."</input>");
	}
}

class BOption extends BField {
	function render() {
		return("<option name='".$this->name."'>".$this->value."</option>");
	}
}
class BSelectBox extends BField {
	var $options;
	
	function __construct($parent=NULL,$type=NULL,$name=NULL){
	
		BField::__construct($parent,$type,$name);
		$options=new BOption(......///work continues here
	}
}


class BForm extends BOB{
    var $form;
	var $fields;
  
	function __construct($fields=NULL) {
        $this->fields=new BField();
		
		if($fields){
			foreach($fields as $field) {
			$this->fields->type=$field['type'];
			$this->fields->name=$field['name'];
			eval("\$this->fields->next=new B".$field['type']."(\$this->field);");
			}
		}
		
	}
	
	function addField($field,$type){
		eval("\$this->fields->next=new B".$type."(\$this->field,array('type'=>".$type.",'name'=>".$field."));");
	}
	
	function removeField($field) {
		$this->find($field)->prev()->next()=$this->find($field)->next();	
		$this->find($field)->next()->prev()=$this->find($field)->prev();
		unset($this->find($field));
	}
	

}









?>