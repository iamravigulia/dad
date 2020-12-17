<?php
namespace edgewizz\Dad\Controllers;

use App\Http\Controllers\Controller;
use Edgewizz\Dad\Models\DadAns;
use Edgewizz\Dad\Models\DadQues;
use Edgewizz\Edgecontent\Models\ProblemSetQues;
use Illuminate\Http\Request;

class DadController extends Controller
{
    //
    public function test()
    {
        dd('hello');
    }
    public function store(Request $request)
    {
        // dd($request->ans_correct1);
        $DadQues = new DadQues();
        $DadQues->question = $request->question;
        $DadQues->difficulty_level_id = $request->difficulty_level_id;
        $DadQues->save();
        /*  */
        if ($request->answer1) {
            $DadAns1 = new DadAns();
            $DadAns1->question_id = $DadQues->id;
            $DadAns1->answer = $request->answer1;
            if ($request->ans_correct1) {
                $DadAns1->arrange = 1;
            }
            $DadAns1->save();
        }
        /*  */
        /*  */
        if ($request->answer2) {
            $DadAns2 = new DadAns();
            $DadAns2->question_id = $DadQues->id;
            $DadAns2->answer = $request->answer2;
            if ($request->ans_correct2) {
                $DadAns2->arrange = 1;
            }
            $DadAns2->save();
        }
        /*  */
        /*  */
        if ($request->answer3) {
            $DadAns3 = new DadAns();
            $DadAns3->question_id = $DadQues->id;
            $DadAns3->answer = $request->answer3;
            if ($request->ans_correct3) {
                $DadAns3->arrange = 1;
            }
            $DadAns3->save();
        }
        /*  */
        /*  */
        if ($request->answer4) {
            $DadAns4 = new DadAns();
            $DadAns4->question_id = $DadQues->id;
            $DadAns4->answer = $request->answer4;
            if ($request->ans_correct4) {
                $DadAns4->arrange = 1;
            }
            $DadAns4->save();
        }
        /*  */
        /*  */
        if ($request->answer5) {
            $DadAns5 = new DadAns();
            $DadAns5->question_id = $DadQues->id;
            $DadAns5->answer = $request->answer5;
            if ($request->ans_correct5) {
                $DadAns5->arrange = 1;
            }
            $DadAns5->save();
        }
        /*  */
        /*  */
        if ($request->answer6) {
            $DadAns6 = new DadAns();
            $DadAns6->question_id = $DadQues->id;
            $DadAns6->answer = $request->answer6;
            if ($request->ans_correct6) {
                $DadAns6->arrange = 1;
            }
            $DadAns6->save();
        }
        /*  */
        if($request->problem_set_id && $request->format_type_id){
            $pbq = new ProblemSetQues();
            $pbq->problem_set_id = $request->problem_set_id;
            $pbq->question_id = $DadQues->id;
            $pbq->format_type_id = $request->format_type_id;
            $pbq->save();
        }
        return back();
    }
    public function update($id, Request $request){
        $q = DADQues::where('id', $id)->first();
        $q->question = $request->question;
        $q->difficulty_level_id = $request->difficulty_level_id;
        // $q->level = $request->question_level;
        // $q->score = $request->question_score;
        $q->hint = $request->question_hint;
        $q->save();
        $answers = DADAns::where('question_id', $q->id)->get();
        foreach($answers as $ans){
            if($request->ans.$ans->id){
                $inputAnswer = 'answer'.$ans->id;
                $inputArrange = 'ans_correct'.$ans->id;
                $ans->answer = $request->$inputAnswer;
                if($request->$inputArrange){
                    $ans->arrange = 1;
                }else{
                    $ans->arrange = 0;
                }
                $ans->save();
            }
        }
        return back();
    }
    public function delete($id){
        $f = DadQues::where('id', $id)->first();
        $f->delete();
        $ans = DadAns::where('question_id', $f->id)->pluck('id');
        foreach($ans as $a){
            $f_ans = DadAns::where('id', $a)->first();
            $f_ans->delete();
        }
        // dd($ans);
        return back();
    }
    public function inactive($id){
        $f = DadQues::where('id', $id)->first();
        $f->active = '0';
        $f->save();
        return back();
    }
    public function active($id){
        $f = DadQues::where('id', $id)->first();
        $f->active = '1';
        $f->save();
        return back();
    }
    public function uploadFile(Request $request)
    {
        
            $file = $request->file('file');
            // dd($file);
            // File Details
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $tempPath = $file->getRealPath();
            $fileSize = $file->getSize();
            $mimeType = $file->getMimeType();
            // Valid File Extensions
            $valid_extension = array("csv");
            // 2MB in Bytes
            $maxFileSize = 2097152;
            // Check file extension
            if (in_array(strtolower($extension), $valid_extension)) {
                // Check file size
                if ($fileSize <= $maxFileSize) {
                    // File upload location
                    $location = 'uploads';
                    // Upload file
                    $file->move($location, $filename);
                    // Import CSV to Database
                    $filepath = public_path($location . "/" . $filename);
                    // Reading file
                    $file = fopen($filepath, "r");
                    $importData_arr = array();
                    $i = 0;
                    while (($filedata = fgetcsv($file, 1000, ",")) !== false) {
                        $num = count($filedata);
                        // Skip first row (Remove below comment if you want to skip the first row)
                        if($i == 0){
                            $i++;
                            continue;
                        }
                        for ($c = 0; $c < $num; $c++) {
                            $importData_arr[$i][] = $filedata[$c];
                        }
                        $i++;
                    }
                    fclose($file);
                    // Insert to MySQL database
                    foreach ($importData_arr as $importData) {
                        $insertData = array(
                                "question" => $importData[1],
                                "answer1" => $importData[2],
                                "arrange1" => $importData[3],
                                "answer2" => $importData[4],
                                "arrange2" => $importData[5],
                                "answer3" => $importData[6],
                                "arrange3" => $importData[7],
                                "answer4" => $importData[8],
                                "arrange4" => $importData[9],
                                "answer5" => $importData[10],
                                "arrange5" => $importData[11],
                                "answer6" => $importData[12],
                                "arrange6" => $importData[13],
                                "level" => $importData[14],
                                "hint" => $importData[15],
                            );
                            // var_dump($insertData['answer1']); 
                            /*  */
                            if($insertData['question']){
                                $fill_Q = new DadQues();
                                $fill_Q->question = $insertData['question'];
                                if(!empty($insertData['level'])){
                                    if($insertData['level'] == 'easy'){
                                        $fill_Q->difficulty_level_id = 1;
                                    }else if($insertData['level'] == 'medium'){
                                        $fill_Q->difficulty_level_id = 2;
                                    }else if($insertData['level'] == 'hard'){
                                        $fill_Q->difficulty_level_id = 3;
                                    }
                                }
                                if($insertData['hint'] == '-'){
                                }else{
                                    $fill_Q->hint = $insertData['hint'];
                                }
                                $fill_Q->save();

                                if($request->problem_set_id && $request->format_type_id){
                                    $pbq = new ProblemSetQues();
                                    $pbq->problem_set_id = $request->problem_set_id;
                                    $pbq->question_id = $fill_Q->id;
                                    $pbq->format_type_id = $request->format_type_id;
                                    $pbq->save();
                                }
                                
                                if($insertData['answer1'] == '-'){
                                }else{
                                    $f_Ans1 = new DadAns();
                                    $f_Ans1->question_id = $fill_Q->id;
                                    $f_Ans1->answer = $insertData['answer1'];
                                    $f_Ans1->arrange = $insertData['arrange1'];
                                    $f_Ans1->save();
                                }
                                if($insertData['answer2'] == '-'){
                                }else{
                                    $f_Ans2 = new DadAns();
                                    $f_Ans2->question_id = $fill_Q->id;
                                    $f_Ans2->answer = $insertData['answer2'];
                                    $f_Ans2->arrange = $insertData['arrange2'];
                                    $f_Ans2->save();
                                }
                                if($insertData['answer3'] == '-'){
                                }else{
                                    $f_Ans3 = new DadAns();
                                    $f_Ans3->question_id = $fill_Q->id;
                                    $f_Ans3->answer = $insertData['answer3'];
                                    $f_Ans3->arrange = $insertData['arrange3'];
                                    $f_Ans3->save();
                                }
                                if($insertData['answer4'] == '-'){
                                }else{
                                    $f_Ans4 = new DadAns();
                                    $f_Ans4->question_id = $fill_Q->id;
                                    $f_Ans4->answer = $insertData['answer4'];
                                    $f_Ans4->arrange = $insertData['arrange4'];
                                    $f_Ans4->save();
                                }
                                if($insertData['answer5'] == '-'){
                                }else{
                                    $f_Ans5 = new DadAns();
                                    $f_Ans5->question_id = $fill_Q->id;
                                    $f_Ans5->answer = $insertData['answer5'];
                                    $f_Ans5->arrange = $insertData['arrange5'];
                                    $f_Ans5->save();
                                }
                                if($insertData['answer6'] == '-'){
                                }else{
                                    $f_Ans6 = new DadAns();
                                    $f_Ans6->question_id = $fill_Q->id;
                                    $f_Ans6->answer = $insertData['answer6'];
                                    $f_Ans6->arrange = $insertData['arrange6'];
                                    $f_Ans6->save();
                                }
                            }
                            /*  */
                        }
                    // Session::flash('message', 'Import Successful.');
                } else {
                    // Session::flash('message', 'File too large. File must be less than 2MB.');
                }
            } else {
                // Session::flash('message', 'Invalid File Extension.');
            }
        return back();
    }
}
