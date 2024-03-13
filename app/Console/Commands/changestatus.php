<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\SubtaskUser;
use App\Models\task;
use Carbon\Carbon;
use App\Mail\MyCustomMail;
use App\Models\subtask;
use App\Models\UserTask;

class changestatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:Status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'change the task status';

    /**
     * Execute the console command.
     */
    public function handle()
    {


        $date = Carbon::now()->addHours(3)->format('Y-m-d H:i:s');
        $allEmails = [];
         $allsubtask=[];
         $alltaskId=[];
        $allTasks=[];

        $task = task::where('endDate',"<=",$date)->where('status',"!=",3)->get();

            foreach ($task as $row) {
   /////// get the subTask and there users Id

                ////////////  update the task status if the mathcing the endDate value
                $row->endDate = $date;

                $row->update([
                      'status' => '3',
                ]);
                //////////// get the user task for who matching value

                $users = UserTask::join('tasks','tasks.id','=','task_user.task_id')
                ->join('users','users.id','=','task_user.users_id')
                ->where('task_user.task_id',$row->id)->pluck('users.email')->toArray();

                $allEmails = array_merge($allEmails, $users);

                $allTasks[] = $row->id;
            }

            foreach ($allEmails as $email) {
                $subject = 'hi mr ' . $email;

                Mail::to($email)->send(new MyCustomMail($subject));
            }


            $allsubtask = [];
            $allemailUsersubtask = [];

            foreach ($allTasks as $row) {
                $subtask = subtask::where('task_id',$row)->get()->toArray();
                $allsubtask = array_merge($allsubtask, $subtask);


                foreach ($allsubtask as $subtask) {
                    $usersuubtask= SubtaskUser::join('subtasks','subtasks.id','=','subtask_users.subtask_id')
                    ->join('users','users.id','=','subtask_users.user_id')
                    ->where('subtask_users.subtask_id', $subtask['id'])->pluck('users.email')->toArray();
                    $allemailUsersubtask=array_merge($allemailUsersubtask, $usersuubtask);

                 }
            }

 foreach ($allemailUsersubtask as $email) {
//   $Smail_data = [
//         'recipient'=>'live.smtp.mailtrap.io',
//         'fromEmail'=>$subtask,
//         'FromName'=>$request->name,
//         'subject'=>$request->subject,
//         'body'=>$request->message
//         ];
$subject = 'hi mr ' . $email;

        Mail::to($email)->send(new MyCustomMail($subject));

        }
    }
}

function sendEmail($to,$title,$body){
    $header ="From anasalsamman2027@gmail.com" .  "\n" > "cc:anasalsa2027@gmail.com";
    mail($to, $title, $body, $header);
}
