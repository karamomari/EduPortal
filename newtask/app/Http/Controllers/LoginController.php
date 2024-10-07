<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
   
   public function index(){
    return view('log.login');
   }
   
   
   public function show(Request $request){
     
        $validatedData = $request->validate([
          'email' => 'required|email|exists:users,email',
          'password'=>'required|string|',
      ]);

      $user = User::where('email', $validatedData['email'])->first();
   

      if (!Hash::check($request->password, $user->password)) {
          return back()->withErrors(['password' => 'Invalid password.']);
      }
  
       if($user->role==="admin"){
           $admin = Admin::getStudentById($user->id);
           $studentactive=Student::where('isActive',"E")->get();
           $studentunactive=Student::where('isActive',"D")->get();
           $subjects=Subject::all();
           return view('admin.index',['user'=>$user,'admin'=>$admin,'studentactive'=>$studentactive,'studentunactive'=>$studentunactive,'subjects'=>$subjects]);
      }



         $student = Student::getStudentById($user->id);
         if($student->isActive=="D"){
             return back()->withErrors(['isActive' => 'This account is inactive, please wait for the administrator to activate your account']);
         }
       
         $subjects = $student->subjects;
         $studentsInSubjects = [];
         $currentUsername = $user->username; 
         foreach ($subjects as $subject) {
          $studentsInSubjects[$subject->name] = $subject->users;
          }
    
      

         return view('student.index',['student'=>$user,'otherinfo'=>$student,'subjects'=>$subjects,'studentsInSubjects' => $studentsInSubjects,'currentUsername'=>$currentUsername]);
       

   }

    public function registration(){
     return view('log.registration');
    }
  

    public function store(Request $request){
        $validatedData = $request->validate([
          'email' => 'required|email|unique:users,email',
          'password'=>'required|string|',
          'Repeat_password' => 'required|same:password',
          'userame'=>'min:8',
      ]);
      $validatedData = $request->except('Repeat_password');
      $validatedData['role']="student";
      $validatedData['password'] = Hash::make($request->password);
      User::create($validatedData);

         return back()->withErrors(['isActive' => 'please wait for the administrator to activate your account']);
       
    }



}
