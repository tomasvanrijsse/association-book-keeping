<?php
/*
 * Version:     1.1
 * Description: Escapes data on reading, raw data will be inserted.
 */

class PNCT_Model extends CI_Model {

    function __construct($id=null)
    {
        parent::__construct();
        if(!is_null($id)){
            return $this->read($id);
        }
    }
    
    /**
     * Get the classname of the current object
     * Only visible within the current class
     * @return string 
     */
    protected function getClassName(){
        return strtolower(get_class($this));
    }

    /**
     * Get the table name by manipulating the filename
     * Only visible within the current class
     * @return string 
     */
    protected function getTableName(){
        $postfix = '';
        return str_replace($postfix,'',strtolower(get_class($this)));
    }

    /**
     * Set all properties to the object,
     * then call this function to save to the database
     * @param boolean $htmlspecialchars htmlspecialchars() the values when inserting in the database, default:true
     * @return PNCT_Model|boolean
     */
    function create()
    {
        $classname = $this->getClassName();
        $a = new $classname();
        $a->fillObject($this,TRUE,FALSE);
        if($this->db->insert($this->getTableName(), $a)){
            return $this->read($this->db->insert_id());
        } else {
            return false;
        }
    }

    /**
     * Use the id to search for a single entry as $obj->read($id)
     * @param int $id, default:null
     * @return PNCT_Model|boolean if succeeds the object will be returned otherwise FALSE
     */
    function read($id=null)
    {
        $query = null;
        if(is_numeric($id)){
            $this->id=$id;
        }
        if($this->id > 0){
            $query = $this->db->get_where($this->getTableName(), array($this->getTableName().'.id'=>$this->id));
        }
        if($query){
            if(is_object($query->row(0))){
                return $this->fillObject($query->row(0));
            }
            else { return FALSE; }
        }
        else { return FALSE; }
    }

    /**
     * Update the current object using its id
     * @param boolean $htmlspecialchars htmlspecialchars() the values when updating in the database, default:true
     */
    function update()
    {
        // check if all attributes are existing
        $classname = $this->getClassName();
        $a = new $classname();
        $a->fillObject($this,TRUE,FALSE);
        if ( $a->id > 0 ) {
            $this->db->where($this->getTableName().'.id', $a->id);
            $this->db->update($this->getTableName(), $a);
        }
    }

    /**
     * Delete the current object
     * @return boolean the result of $this->db->delete();
     */
    function delete()
    {
        if ( $this->id > 0) {
            return $this->db->delete($this->getTableName(), array($this->getTableName().'.id'=>$this->id));
        }
    }

    /**
     * Fetch ALL entries from the current object
     * @return array containing all table entries
     */
    function readAll() {
        $query = $this->db->get($this->getTableName());
        return $this->fillObjects($query);
    }

    /**
     * Search for all entries given the properties of the current object, using only the known properties
     * @param boolean $htmlspecialchars htmlspecialchars() the values when searching in the database, default:true
     * @return array containing objects
     */
    function readAllByVars(){
        foreach($this as $attr => $value){
            if(isset($value) && property_exists($this, $attr)){
                $this->db->where($this->getTableName().'.'.$attr,$value);
            }
        }
        return $this->readAll();
    }

    /**
     * Search for a single entry given the current properties,
     * if more or no entries are found FALSE will be returned.
     * when found the current object is filled with properties.
     * @param boolean $htmlspecialchars htmlspecialchars() the values when searching in the database, default:true
     * @return PNCT_Model|boolean 
     */
    function readByVars(){
        $result = $this->readAllByVars();
        if(count($result) == 1){
            $this->fillObject($result[0]);
            return $this;
        } else {
            return false;
        }
    }
    
    /**
     * Search for the single existence of an entry
     * @return boolean FALSE if none or more are found
     */
    function exists(){
        if($this->countByVars()==1){
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Clear all the values of the current object
     */
    public function clear(){
        foreach ($this as $key => $value) {
            unset($this->$key);
        }
    }
    
    /**
     * Query the amount of entries using the current properties
     * @return int amount of records
     */
    function countByVars(){
        $this->db->from($this->getTableName());
        foreach($this as $attr => $value){
            if(isset($value) && property_exists($this, $attr)){
                $this->db->where($this->getTableName().'.'.$attr,$value);
            }
        }
        return $this->db->count_all_results();
    }

    /**
     * Set properties of the current object with the attributes of the supplied array
     * @param array|object $row an array or object containing the right attributes
     * @param bool $property_exists when true it only sets the current properties, default:false
     * @param bool $htmlspecialchars all values will be htmlspecialchar escaped, default:true
     * @return PNCT_Model 
     */
    function fillObject($row, $property_exists=FALSE, $htmlspecialchars=TRUE){
        foreach($row as $var => $value){
            if ($htmlspecialchars && !is_null($value)) {
                $value = htmlspecialchars($value);
            }
            
            if($property_exists){
                if(property_exists($this->getClassName(), $var)) {
                    $this->$var = $value;                    
                }
            } else {
                $this->$var = $value;
            }
        }
        return $this;
    }

    /**
     * Convert the result of $this->db->query() into an array of clean objects
     * @param type $query the result of $this->db->query();
     * @return array  containing 
     */
    function fillObjects($query){
        $out =  array();
        foreach($query->result() as $row){
            $classname = $this->getClassName();
            $a = new $classname();
            $out[] = $a->fillObject($row);
        }
        return $out;
    }
    
    /**
     * @param string $key
     * @return mixed 
     */
    function __get($key){
        if(property_exists($this, $key)){
            return $this->$key;
        } else {
            $classname = $key;
            $propertyname = $key.'_id';
            
            //check if $key == date_nl or datetime_nl and convert to the dutch date notation
            if(property_exists($this, substr($key,0,-3))&& substr($key,-2) == 'nl'){
                $name = substr($key,0,-3);
                if($name == 'date' || $name == 'datum'){
                    return date('d-m-Y',strtotime($this->$name));
                } elseif($name == 'datetime'){
                    return date('d-m-Y H:i',strtotime($this->$name));
                }
            }
            //chec if $key is a known model and if this class contains a reference 
            elseif(class_exists($classname) && property_exists($this, $propertyname)){
                //isset om te checken of het obj al eens geladen is
                if(!isset($this->$classname)){            
                    $this->$classname = new $classname();
                    $this->$classname->read($this->$propertyname);
                }
                return $this->$classname;
            } else {
                $CI =& get_instance();
                return $CI->$key;
            }
        }
    }
}