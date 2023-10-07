<?php

namespace App\Console\Commands;

use App\Mail\DeptReminderEmail;
use App\Models\DeptStatus;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDeptReminderEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:send-dept-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to send a dept reminder email to all users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $deptors = DeptStatus::all();

        if (! $deptors)
        return 0;

        foreach ($deptors as $deptor){
            Mail::to($deptor->user->client->email)->send(new DeptReminderEmail($deptor->user));
        }

        $this->info('Dept reminder sent successfully');
    }
}
