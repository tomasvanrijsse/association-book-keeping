<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class gmod extends CI_Controller {
      
    private $part1      = '<?php  if ( ! defined(\'BASEPATH\')) exit(\'No direct script access allowed!\');

class %s extends PNCT_Model {

%s
    ';
    private $part2 = '/** CUSTOM %s FUNCTIONS **/

    /*function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }*/

}';
    
	public function index()
	{       
        if(ENVIRONMENT != 'development'){
            trigger_error ("This script only runs in a development environment");
            die();
        }
        
		$query = $this->db->query('SHOW TABLES');
        $tables = array();
        foreach ($query->result() as $row)
        {
            foreach($row as $table){
                $tables[] = $table;
            }
        }
        
        $MPATH = FCPATH.'application\models\\';
        
        foreach($tables as $table){
            $filename = $MPATH.$table.'.php';
            
            $query = $this->db->query('SHOW COLUMNS FROM `'.$table.'`');
            $fields = '';
            foreach ($query->result() as $row)
            {
                $fields .= "    public $".$row->Field.";\n";
            }
            
            if(file_exists($filename)){
                echo $table.'.php updated<br/>';
                
                $filecontent = file_get_contents($filename);
                
                $pos = strpos($filecontent, sprintf('/** CUSTOM %s FUNCTIONS **/',$table));
                
                if($pos === false ){
                    $pos = strpos($filecontent, sprintf('function',$table));
                }
                if($pos === false ){
                    $modelstring = $this->part1.$this->part2;
                    $filecontent = sprintf($modelstring,$table,$fields,$table);
                } else {
                    $part1 = sprintf($this->part1,$table,$fields);
                    $part2 = substr($filecontent,$pos);               
                    $filecontent = $part1.$part2;
                }
                
            } else {
                echo $table.'.php is new<br/>';                
                $modelstring = $this->part1.$this->part2;
                $filecontent = sprintf($modelstring,$table,$fields,$table);
            }
            
            $fp = fopen($filename, 'w');
            fwrite($fp, $filecontent);
            fclose($fp);
        }
	}
}