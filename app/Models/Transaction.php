<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

/**
 * @property $id
 * @property $bedrag
 * @property $van
 * @property $van_naam
 * @property $naar
 * @property $datum
 * @property $description
 * @property $transactietype
 * @property $type
 * @property $creditgroep_id
 * @property $status
 */
class Transaction extends Model {

    use SoftDeletes;

    protected $table = 'transactie';

    public function booking(): HasMany
    {
        return $this->hasMany(Booking::class, 'transactie_id');
    }

    public function scopeOnBudget(Builder $query, Budget $budget){
        $query->whereHas('booking', function (Builder $query) use ($budget) {
            $query->where('budget_id', $budget->id);
        });
    }

    /** CUSTOM transaction FUNCTIONS **/

    public function getOpenCredit(){
        $this->db->select('transaction.*');
        $this->db->order_by('transaction.datum DESC');
        $this->db->where('type','credit');
        $this->db->join('boeking','boeking.transactie_id = transaction.id','left');
        $this->db->where('boeking.id IS NULL');
        $this->db->where('transaction.creditgroep_id IS NULL');
        $this->db->where('status >=',1);
        return $this->readAll();
    }

    public function deactivate(){
        //transaction uit + debit uitschakelen
        $this->db->where('id',$this->id);
        $this->db->update($this->getTableName(), array('status' => '0'));

        //credit boeking verwijderen
        $this->db->where('transactie_id',$this->id);
        $this->db->delete('boeking');
    }

    public function getGroepTransacties($groep_id){
        $this->db->order_by('van_naam');
        $this->db->where('creditgroep_id',$groep_id);
        return $this->transactie->readAll();
    }

    /* aangepaste exists/countByVars voor de import, zodat niet alle Vars meegenomen worden. */
    function cleanExists(){
        $this->db->from($this->getTableName());
        foreach($this as $attr => $value){
            if(isset($value) && property_exists($this, $attr) && !in_array($attr,array('status'))){
                $this->db->where($this->getTableName().'.'.$attr,$value);
            }
        }
        if($this->db->count_all_results()==1){
            return true;
        } else {
            return false;
        }
    }

}
