<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property $id
 * @property $status
 * @property $bedrag
 * @property $rekeningnaam
 * @property $gewicht
 * @property $transactie_id
 */
class CounterSuggestion extends Model {

    protected $table = 'countersuggestie';
    function gewicht_naam()
    {
        switch ($this->gewicht) {
            case '1':
                return 'bedrag en rekening';
                break;
            case '2':
                return 'lid';
                break;
            case '3':
                return 'bedrag';
                break;
            case '4':
                return 'rekening';
                break;
        }
    }
    function findCounterTransactions(){
        $this->load->model('counterSuggestion');

        $transactionSets = array();
        $sql = "
SELECT bedrag,van_naam, SUM(saldo) as optelling, GROUP_CONCAT(t_id) as transaction_ids, SUM(IF(saldo<0,1,0)) as aantaldebit, SUM(IF(saldo>0,1,0)) as aantalcredit
FROM (
 SELECT t.id As t_id, t.bedrag,t.van,t.van_naam, case `type` WHEN 'credit' THEN bedrag WHEN 'debet' THEN bedrag * -1 END as saldo
 FROM transaction t
 WHERE status = 1 AND t.datum > DATE_ADD(NOW(), INTERVAL -1 YEAR)
 ) as incsaldo
 GROUP BY %s
 HAVING aantaldebit > 0 AND aantalcredit > 0";
        $query = $this->db->query(sprintf($sql,'van,bedrag'));
        $this->createBatch($query,1);

        // puur op rekeningnr geeft teveel overeenkomsten
        //$query = $this->db->query(sprintf($sql,'van'));
        //$this->createBatch($query,4);

        $lidsql = "SELECT bedrag,van_naam, SUM(saldo) as optelling, GROUP_CONCAT(t_id) as transaction_ids, SUM(IF(saldo<0,1,0)) as aantaldebit, SUM(IF(saldo>0,1,0)) as aantalcredit
FROM (
 SELECT t.id As t_id, t.bedrag,t.van,t.van_naam, case `type` WHEN 'credit' THEN bedrag WHEN 'debet' THEN bedrag * -1 END as saldo,
 FROM transaction t
 LEFT JOIN boeking b ON b.transactie_id = t.id
 WHERE b.budget_id IS NULL AND t.status = 1 AND t.datum > DATE_ADD(NOW(), INTERVAL -1 YEAR)
 ) as incsaldo
 GROUP BY incsaldo.van
 HAVING aantaldebit > 0 AND aantalcredit > 0";
        // puur op lid geeft te veel overeenkomsten
        //$query = $this->db->query($lidsql);
        //$this->createBatch($query,2);

        $query = $this->db->query(sprintf($sql,'bedrag'));
        $this->createBatch($query,3);

        return $transactionSets;
    }

    private function createBatch($query,$gewicht){
        foreach($query->result() as $row){
            $countersug = new counterSuggestion();
            if($gewicht==1||$gewicht==2||$gewicht==4)
                $countersug->rekeningnaam = $row->van_naam;
            if($gewicht==1||$gewicht==3)
                $countersug->bedrag       = $row->bedrag;
            $countersug->gewicht    = $gewicht;

            $countersug->transaction_ids = $row->transaction_ids;
            $countersug->create();
        }
    }

    public function hide($id){
        $this->db->where('id',$id);
        $this->db->update($this->getTableName(), array('status' => 0));
    }

    public function used($id,$tid){
        $this->db->where('id',$id);
        $this->db->update($this->getTableName(), array('status' => 2,'transactie_id'=>$tid));
    }

    public function create(){
        $t_ids = explode(',',$this->transaction_ids);

        //first check if the suggestion is present
        $sql = "SELECT GROUP_CONCAT(cst.transactie_id) as ids
FROM counterSuggestion cs
JOIN countersuggestie_transactie cst ON cs.id = cst.counter_id
GROUP BY cs.id";
        $query = $this->db->query($sql);
        foreach($query->result() as $countersug){
            $ids = explode(',',$countersug->ids);
            $has_all = true;

            foreach($t_ids as $t_id){
                if(!in_array($t_id,$ids)){
                    $has_all = false;
                }
            }

            if($has_all){ //this countersug contains at least all the same transactions.
                return false;
            }
        }

        $this->status = 1;

        $this->load->model('countersuggestie_transactie');

        $transaction_ids = explode(',',$this->transaction_ids);
        unset($this->transaction_ids);

        parent::create();

        foreach($transaction_ids as $id){
            /* @var $transaction transactie*/
            $ct = new countersuggestie_transactie();
            $ct->counter_id = $this->id;
            $ct->transactie_id = $id;
            $ct->status = 0;
            $ct->create();
        }

    }

     function getCounterTransactions($status){
        $statuscode = ($status=='open'?1:($status=='verborgen'?0:2));
        $this->status = $statuscode;
        if($status==1){
            $this->db->order_by('gewicht DESC');
        } else {
            $this->db->order_by('lastupdate DESC');
        }
        $transactionSets = $this->readAllByVars();

        $this->load->model('countersuggestie_transactie');
        foreach($transactionSets as $set){
            $ct = new countersuggestie_transactie();
            $set->transacties = $ct->getTransactions($set->id);
        }
        return $transactionSets;
    }

}
