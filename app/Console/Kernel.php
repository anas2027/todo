<?php

namespace App\Console;


use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('task:Status')->everySecond();

//             function () {
//              $myTask = task::all();
//              $date = Carbon::now()->format('Y-m-d H:i:s');;
//              $myTask->where('endDate',$date)->first();
//              foreach ($myTask as $row) {
//                 if ($row->endDate === $date) {
//                     $row->update([
//                         'status'=> 2,

//                     ]);

//                     $users = UserTask::join('tasks','tasks.id','=','task_user.task_id')
//  ->join('users','users.id','=','task_user.users_id')
//  ->where('task_user.users_id',$row->user_id)->select('users.email')->first();
//             sendEmail($users, "hi","the task is done" );
//             foreach ($users as $user) {
//                 sendEmail($user, "hi","the task is done" );}
//                 return true;
//                 }
//                 else{
//                     return false;
//                 }
//                 }
//             }
//             );
        }


    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
    }

    /**
     * Register the commands for the application.php artisan schedule:list

     */

