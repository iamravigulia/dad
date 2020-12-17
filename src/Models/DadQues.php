<?php
namespace Edgewizz\Dad\Models;

use Illuminate\Database\Eloquent\Model;

class DadQues extends Model{
    public function answers(){
        return $this->hasOne('Edgewizz\Dad\Models\DadAns', 'question_id');
    }
    protected $table = 'fmt_dad_ques';
}