<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class import extends CI_Controller {
    
    public function upload(){
        
        $config['upload_path'] = FCPATH.'/temp/';
        $config['allowed_types'] = '*';
        $config['max_size']	= '1000'; //1mb

        $accounts = $this->account->readAll();
        
        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('csvfile'))
        {
            $this->session->set_flashdata('home_import_error', $this->upload->display_errors());
            redirect('/home');
        }
        else
        {
            saveSetting(LAST_IMPORT, date('d-m-Y H:i'));
            
            $upload_data = $this->upload->data();
            $csvconfig = array(
                'separator' => ',',
                'fields' => array('datum','naar','bedrag','type','van_naam','van','transactietype','description')
            );
            $this->load->library('csvreader',$csvconfig);
            $lines = $this->csvreader->parse_file($upload_data['full_path']);
            $aantal = 0;
            $bestondal = 0;
            foreach($lines as $line){
                $transactie = new transactie();
                $transactie->fillObject($line,true);
                $transactie->type = strtolower($transactie->type);
                $transactie->datum = date('Y-m-d',strtotime($transactie->datum));
                $transactie->bedrag = str_replace(',','.',str_replace('.','',$transactie->bedrag));
                $transactie->status = 1;
                
                foreach($accounts as $account){
                    if(strpos($transactie->van,$account->spaarrekening) !== FALSE || strpos($transactie->naar,$account->spaarrekening)!== FALSE){
                        $transactie->status = -1;//spaar rekening acties verbergen
                    }
                }
                
                if(!$transactie->cleanExists()){
                    $budget_id = false;
                    if($transactie->van == 'RABONL2U NL36RABO0128073454' && $transactie->type == 'debet' ){
                        $budget_id = 2; // Triodos Hypotheek
                    }
                    if($transactie->van == 'INGBNL2A NL98INGB0000845745' && $transactie->type == 'debet' ){
                        $budget_id = 7; // UPC internet
                    }
                    if($transactie->van == 'ABNANL2A NL98ABNA0513804498' && $transactie->type == 'debet' ){
                        $budget_id = 6; // nedasco
                    }
                    if($transactie->van == 'INGBNL2A NL18INGB0005309282' || $transactie->van == 'INGBNL2A NL13INGB0000008710'){
                        $budget_id = 4; // greenchoice of evides
                    }
                    if($transactie->van == 'RABONL2U NL28RABO0134901428' && $transactie->type == 'debet' ){
                        $budget_id = 8; // ambachstheer
                    }
                    
                    $bankrek = new bankrekening();
                    $bankrek->nummer = $line['van'];
                    if($bankrek->readByVars()){
                        $transactie->bankrekening_id = $bankrek->id;
                    }
                    if($transactie->create()){
                        if($budget_id){
                            $boeking = new boeking();
                            $bedrag = $transactie->bedrag;
                            if($transactie->type == 'debet'){
                                $bedrag *= -1;
                            }
                            $boeking->bedrag = $bedrag;
                            $boeking->budget_id = $budget_id;
                            $boeking->transactie_id = $transactie->id;
                            $boeking->datum = $transactie->datum;
                            $boeking->create();
                        }
                        $aantal++;
                    }
                } else {
                    $bestondal++;
                }
            }
            if($aantal == 0){
                $this->session->set_flashdata('home_import_notice','Alle transacties waren al eerder geïmporteerd');
            } else {
                $this->session->set_flashdata('home_import_success', 'Er zijn '.$aantal.' van de '.count($lines).' transacties geïmporteerd ('.floor($aantal/count($lines)*100).'%)');
                if($bestondal>0){
                    $this->session->set_flashdata('home_import_notice',$bestondal.' transacties waren al eerder geïmporteerd');
                }
            }
            
            //find new counter transactions
            $this->load->model('countersuggestie');
            $this->countersuggestie->findCounterTransactions();
            
            redirect('/home');
        }
    }
}