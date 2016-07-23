<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class leden extends PNCT_Controller {

	public function index()
	{
            $data = array();
            
            $lid = new lid();
            $data['leden']  = $lid->readAll();
            
            $this->load->view('leden/index', $data);
	}
        
    public function details($id = null,$t_type='credit'){
        if(!is_numeric($id)){
            redirect('/leden');
        } else {
            enqueue_script('/js/libs/jquery.pajinate.js');
            enqueue_stylesheet('/css/leden_detail.css');
            enqueue_customscript("$(document).ready(function(){
    $('#betalingen').parent().pajinate({
        items_per_page : 10,
        show_first_last: false,
        item_container_id:'#betalingen',
        abort_on_small_lists: true
    });
}); 
");
            $data = array();

            $lid = new lid($id);
            $data['lid'] = $lid;

            $bank = new bankrekening();
            $bank->lid_id = $lid->id;
            $data['banken'] = $bank->readAllByVars();

            $inkomen = new inkomen();
            $inkomen->lid_id = $lid->id;
            $this->db->order_by('startdatum DESC');
            $data['inkomens'] = $inkomen->readAllByVars();

            $this->load->model('lid_ruimte');
            $lidruimte = new lid_ruimte();
            $lidruimte->lid_id = $lid->id;
            $this->db->order_by('startdatum DESC, einddatum DESC');
            $data['lidruimtes'] = $lidruimte->readAllByVars();

            $ruimte = new ruimte();
            $data['ruimtes'] = $ruimte->readAll();

            $tdata = array();
            $tdata['transacties'] = $this->transactie->getFromLid($id,date('Y'),$t_type);
            $data['betalingen'] = $this->load->view('leden/betalingen',$tdata,true);

            $this->load->view('leden/details', $data);
        }
    }

    public function addRuimte(){
        $this->load->model('lid_ruimte');

        $lidruimte = new lid_ruimte();
        $lidruimte->lid_id = $this->input->post('lid_id');
        $lidruimte->endActiveRuimte();

        $lidruimte->clear();
        $lidruimte->lid_id = $this->input->post('lid_id');
        $lidruimte->ruimte_id = $this->input->post('ruimte_id');
        $lidruimte->create();

        redirect('/leden/details/'.$this->input->post('lid_id'));
    }

    public function deleteRuimte($id = null){
        if(is_numeric($id)){
            $this->load->model('lid_ruimte');
            $lr = new lid_ruimte();
            $lr->id = $id;
            $lr->delete();
            echo 'success';
        } else {
            echo 'fail';
        }
    }

    public function addInkomen(){
        $inkomen = new inkomen();
        $inkomen->fillObject($this->input->post());
        $inkomen->einddatum = NULL;
        $inkomen->create();

        redirect('/leden/details/'.$this->input->post('lid_id'));
    }

    public function add(){
        $lid = new lid();
        $lid->fillObject($this->input->post());
        $lid->create();
        redirect('leden');
    }

}