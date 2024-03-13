<?php

namespace App\Http\Controllers;

use App\Models\task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Mail\MyCustomMail;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
class usercontroller extends Controller
{
    //
    public function login(Request $request){

      $userEmail = $request->email;
      $credentials = [
        'email' => $request->email,
        'password' => $request->password
    ];
    if (auth()->attempt($credentials)) {

        $subject = Str::random(4);
        $otpData = [
            'user_email' =>$request-> email,
            'otp' => $subject,
            'created_at' => now(),
        ];

        $filePath = storage_path("app\\otp\\{$userEmail}.json");
        file_put_contents($filePath, json_encode($otpData));

        try {
            Mail::to($userEmail)->send(new MyCustomMail($subject));

           return response()->json(['message' => 'Email sent successfully please check your email ']);
       } catch (\Exception $e) {
           return response()->json(['message' => 'Failed to send email', 'error' => $e->getMessage()], 500);
       }


    } else {
        return response()->json(['error' => 'email or password is not correct '], 401);

            // $otp = Str::random(4);
            // $subject = 'hi mr ' . $userEmail;
    }


    }

   public function verfiy(Request $request){
     $userEmail = $request->email;
     $otp = $request->otp;
     $filePath = storage_path("app/otp/{$userEmail}.json");


// Construct the file path for storing and retrieving the OTP
$filePath = storage_path("app/otp/{$userEmail}.json");

// Check if the file exists for the user's email
if (file_exists($filePath)) {

    // Read the contents of the file and decode it from JSON
    $otpData = json_decode(file_get_contents($filePath), true);

    // Check if the stored OTP matches the submitted OTP
    if ($otpData['otp'] == $otp) {
        unlink($filePath);
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];
        if (auth()->attempt($credentials)) {
            $token = auth()->user()->createToken('TutsForWeb')->accessToken;
            $username =  User::where('email',$request->email)->first();
            return response()->json(['token' => $token,'username'=>$username], 200);
        } else {
            return response()->json(['error' => 'UnAuthorised'], 401);
        }

        // OTP is valid, perform desired actions

        // Remove the file to mark the used OTP

        // Perform additional actions

    } else {
        return response()->json(['error' => 'otp is not true go back and check '], 401);

    }

} else {


    return response()->json(['error'=> 'erorr please login first'],401);
    // File not found, handle accordingly (e.g., show an error message)
}

   }


    public function Logout(Request $request){
        $user = Auth::user()->token();
        if($user->revoke()){
            return response()->json(['success'=> 'logOutSuccsuflly'],200);
        }
        return response()->json(['error'=> 'check your internet'],400);
    }

    /**
     * Returns Authenticated User Details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function details()
    {
        return response()->json(['user' => auth()->user()], 200);
    }
    public function register(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:4',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $token = $user->createToken('Laravel10PassportAuth')->accessToken;

        return response()->json(['token' => $token], 200);






        // $vaildator = validator::make($request->all(), [
        // 'name'=> 'required|string',
        // 'email'=> 'required|email',
        // 'password'=> 'required',

        // ]);
        // if($vaildator->fails()){
        //     return $this->sendError('Validation Error ',  $vaildator->errors());
        // }
        // $input = $request->all();
        // $input ['password'] = bcrypt($input['password']);
        // $user = User::create($input);
        // $success['token']= $user->createToken('MyApp')->accessToken;
        // $success['name'] = $user->name;
        // return $this ->sendResponse('User Register succssfly', $success);
    }
   public function create(Request $request){

    $rule=[
        "title"=> "required|string",
        "endDate"=> "required|date_format:Y-m-d H:i:s",
    ];
    $validator = validator::make($request->all(), $rule);
    if($validator->fails()){
        return response()->json($validator->errors(),400);
      }
      task::create([
        'title'=> $request->title,
        'endDate'=> $request->endDate,
        'users_id'=>Auth::user()['id'],
      ]);
   }
   public function update(Request $request){
    $mytask = Task::where('id', $request->id)
                ->where("users_id",Auth::user()['id'])->first();
    if(!$mytask)
        return response()->json(['success'=> 'false'],404);
    $mytask->update([
        'title'=> $request->title,
        'endDate'=> $request->endDate,
    ]);
    return response()->json(['success'=> ''],200);
   }



   public function taskassigmentAddUser(Request $request){
      $mytask = Task::where('id', $request->taskid)->first();
      if($mytask->users_id == Auth::user()['id']){
         $user = User::find($request->userid);
        $user ->TasksUSer()->sync([$request->taskid]);

        //  $userAssi->save();
        //  $mytask->save();
        //   $userAssi->Tasks()->attach($mytask->id);
        // $userAssi->save();

        // $userAssi -> UserTask()->sync($request->taskid);

        // return $mytask->id;
      }
      else{
        return response()->json(['success'=> false],404);
      }
   }




   public function AddtaskAsssigmentToUser(Request $request){

    $user = User::where('id', Auth::user()['id'])->first();

    $mynewtask = new task();

    $mynewtask->title = $request->title ;
    $mynewtask->endDate = $request->endDate ;
    $mynewtask->users_id = Auth::user()['id'];
    $mynewtask->name= $request->name;
    $mynewtask->save();
    $user->TasksUSer()->sync($mynewtask);
    return response()->json(['success' , true, 'mynewtask' => $mynewtask]);
   }

public function updateUserProfile(Request $request ){
 User::find( Auth::user()['id'])->update([
    'name'=>$request->name,
    'email'=>$request->email,
   ]);
   return response()->json(['success'=> ''],200);
}
  public function getAllUser(Request $request){
    return User::all();
  }

  public function deleteUserAssign(Request $request){
    $user = User::find( $request ->user_id);
    $user->TasksUSer()->detach();

  }






}
