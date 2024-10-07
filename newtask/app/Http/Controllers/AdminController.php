<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Subject;
use App\Models\Subject_User;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
  public function destroy(Student $student_id){
     $user=User::where('id',$student_id->user_id)->first();
     if($student_id->subjects){$student_id->subjects()->detach();}
     $student_id->delete();
     $user->delete();
     return response()->json(['message' => 'Student deleted successfully!']); 
  }
   
  public function update(Student $student_id){
     $student_id->isActive="E";
     $student_id->save();

     return response()->json([ 'message' => 'Student status updated successfully!']);
    }

    public function edit(Student $student_id, Request $request)
    {
        // التحقق من صحة البيانات
        $validatedData = $request->validate([
            'username' => 'required|string|max:255',
            'isActive' => 'required',
        ]);
    

        if (!empty($validatedData['username'])) {
            $student_id->full_name = $validatedData['username'];
        }
        if ($validatedData['isActive'] !== null) {
            $student_id->isActive = $validatedData['isActive'];
        }    
       
        $student_id->save();
    
        return response()->json(['success' => true, 'message' => 'Student information updated successfully!']);
    }
    



    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'username' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string',
        ]);
    
        $validatedData['password'] = Hash::make($validatedData['password']);

        $user = new User();
        $user->username = $validatedData['username'];
        $user->email = $validatedData['email'];
        $user->password = ($validatedData['password']);
        $user->role="student";
        $user->save();
    


         $student= new Student();
         $student->full_name=$validatedData['full_name'];
         $student->user_id=$user->id;
         $student->isActive="E";
        $student->save();

        return response()->json(['success' => 'User added successfully!']);
    }
    



    public function create_subject(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'pass_mark' => 'required|integer',
        ]);
    
        $subject = new Subject();
        $subject->name = $validatedData['name'];
        $subject->pass_mark = $validatedData['pass_mark'];
        $subject->save();
    
        return response()->json(['success' => 'Subject added successfully!']);
    }



    public function getNotRegisteredStudents(Request $request)
     {
      $subjectId = $request->input('subject_id');
      
      $students = Student::with('user')->whereDoesntHave('subjects', function($query) use ($subjectId) {
          $query->where('subject_id', $subjectId);
      })->get();
  
      return response()->json(['students' => $students]);

  }

    
  public function registerSubject(Request $request)
  {
      $validatedData = $request->validate([
          'subject_id' => 'required|exists:subjects,id',
          'student_id' => 'required|exists:students,user_id',
      ]);
  
      $subject = Subject::find($validatedData['subject_id']);
     $subject_student=new Subject_User();
     $subject_student->user_id=$validatedData['student_id'];
     $subject_student->subject_id=$validatedData['subject_id'];
     $subject_student->name=$subject->name;
     $subject_student->save();
      
      return response()->json(['success' => 'Student registered for the subject successfully!']);
  }


public function getRegisteredStudents(Request $request)
{
    $students = Student::whereHas('subjects', function ($query) use ($request) {
        $query->where('subject_id', $request->subject_id);
    })->get();
    return response()->json(['students' => $students]);
}

  public function addMark(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'student_id' => 'required|exists:students,user_id',
            'mark' => 'required|numeric|min:0|max:100', // تحديد نطاق العلامة
        ]);

         Subject_User::updateOrCreate(
            [
                'subject_id' => $request->subject_id,
                'user_id' => $request->student_id,
            ],
            ['mark' => $request->mark]
        );

        return response()->json(['message' => 'Mark added successfully!']);
    }
  

}
