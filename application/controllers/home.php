<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class home extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        if(!isAdmin()){
            redirect('/public_home/index');
        }
    }
    
    public function index(){
        $this->load->model('countersuggestie');
        $this->countersuggestie->status = 1;
        
        $this->db->select('MAX(datum) as datum');
        $query = $this->db->get('transactie');
        if ($query->num_rows() > 0)
        {
            $row = $query->row(); 
            $last_transaction = date('d-m-Y',strtotime($row->datum));
        } else {
            $last_transaction = false;
        }
            
        $data = array(
            'aantalCounter' => $this->countersuggestie->countByVars(),
            'last_import' => getSetting(LAST_IMPORT),
            'last_transaction' => $last_transaction
        );
        
        set_title('Home');
        
        $this->load->view('home/index',$data);
    }
    
    public function switchType(){
        $this->session->set_userdata('account_id',$this->input->post('account_id'));
    }

 
    public function rescanCounter(){
        //find new counter transactions
        $this->load->model('countersuggestie');
        $this->countersuggestie->findCounterTransactions();

        redirect('/home');
    }
    
    public function graphs(){
        
        $sql_credit = 'SELECT t.bedrag, t.datum, IFNULL(p.naam,"") as "budget"
FROM transactie t
INNER JOIN boeking b ON b.transactie_id = t.id
INNER JOIN budget p ON p.id = b.budget_id
WHERE type = "credit" AND naar = "%s" AND t.status IN (1,2)

UNION 
SELECT b.bedrag,MIN(t.datum) as datum, p.naam
FROM boeking b
INNER JOIN creditgroep c ON c.id = b.creditgroep_id
INNER JOIN budget p ON p.id = b.budget_id
INNER JOIN transactie t ON t.creditgroep_id = c.id
WHERE b.creditgroep_id IS NOT NULL AND c.account_id =  %d
GROUP BY b.id
';
        
        $sql_debit = 'SELECT t.bedrag*-1 as bedrag,t.datum, IFNULL(p.naam,"") as "budget"
FROM transactie t
LEFT JOIN boeking b ON b.transactie_id = t.id
LEFT JOIN budget p ON p.id = b.budget_id 
LEFT JOIN countersuggestie cs ON cs.transactie_id = t.id
WHERE type = "debet" AND naar = "%s"
AND t.status IN (1,2) /* voor actieve en ge*merged*e transacties */
ORDER BY datum';
        
        $personal = sprintf($sql_credit,"NL73TRIO0198483325",2).' UNION '.sprintf($sql_debit,"NL73TRIO0198483325");
        $business = sprintf($sql_credit,"NL97TRIO0198507046",1).' UNION '.sprintf($sql_debit,"NL97TRIO0198507046");
        
        //debit only
        //$personal = sprintf($sql_debit,"NL73TRIO0198483325");
        //$business = sprintf($sql_debit,"NL97TRIO0198507046");
        
        
        $business2 = "SELECT bedrag,datum, `type` as budget FROM transactie WHERE naar = 'NL97TRIO0198507046'";
        
        $query = $this->db->query($personal);
        $pivot1 = $this->buildPivot($query->result());
        $this->dumpPivot($this->sumPivot($pivot1));
        echo '<hr>';
        $query2 = $this->db->query($business);
        $pivot2 = $this->buildPivot($query2->result());
        $this->dumpPivot($this->sumPivot($pivot2));
        //$this->dumpPivot($pivot2); only debit per year
        echo '<hr>';
        $query3 = $this->db->query($business2);
        $pivot3 = $this->buildPivot($query3->result());
        $this->dumpPivot($this->sumPivot($pivot3));
    }
    
    private function dumpPivot($pivot){
        echo '<table>';
        
        echo '<tr><td>&nbsp;</td>';
        $firstrow = false;
        foreach($pivot as $row){
            if($firstrow) continue;
            foreach ($row as $col => $val){
                echo '<td>'.$col.'</td>';
            }
            $firstrow = true;
        }
        echo '</tr>';
        
        foreach($pivot as $key => $row){
            echo "<tr>";
            echo "<td>".$key.'</td>';
            foreach ($row as $val){
                echo "<td>".round($val).'</td>';    
            }
            echo '</tr>';
        }
        echo '</table>';
    }


    private function sumPivot($pivot){
        
        foreach($pivot as &$row){
            $sum = 0;
            foreach($row as $key => $col){
                $sum += $col;
                $row[$key] = $sum;
            }
        }
        
        return $pivot;
    }
    
    private function buildPivot($query_result){
        $pivot = array();
        $rows = array();
        $columns = array();
        foreach($query_result as $credit){
            $r = $credit->budget;
            $c = substr($credit->datum,0,7); // per month
            //$c= substr($credit->datum,0,4); // per year
            
            $columns[] = $c;
            
            if(!array_key_exists($r, $pivot)) $pivot[$r] = array();
            if(!array_key_exists($c, $pivot[$r])) $pivot[$r][$c] = 0;
            $pivot[$r][$c] += $credit->bedrag;
        }
        
        $columns = array_unique($columns);
        
        foreach($pivot as $rowname => &$row){
            foreach($columns as $col){
                if(!array_key_exists($col, $row)){
                    $row[$col] = 0;
                }
            }
            ksort ( $row );
        }
        return $pivot;
    }
}