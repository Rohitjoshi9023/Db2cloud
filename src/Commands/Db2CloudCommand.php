<?php

namespace Db2Cloud\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

use Db2Cloud;


class Db2CloudCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:Backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Take backup of database anytime';

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
     * @return mixed
     */
    public function handle()
    {




        $this->alert("<fg=green>Db2cloud<fg=red>(Auto-schedule your DB backup)</></>");      // SHOW PACKAGE MENU
        $this->line("<fg=magenta>Creating backup of your database</>");                     // SET STATUS TO CREATING DATABASE FILE
        $this->output->progressStart(100);                                                  // START A PROGRESS
        self::progress(50);                                                               // INCREASE PROGRESS TO 30


        // CREATE BACKUP
        Db2Cloud::backup();


        self::progress(50);                                                                          // INCREASE PROGRESS TO 30
        $this->line("\n<fg=magenta>Uploading Database to ".config('backupconfig.storage_type')."</>"); // SET STATUS TO CREATING DATABASE FILE
        $this->output->progressStart(100);                                                          // START A NEW PROGRESS
        self::progress(50);                                                                       // INCREASE PROGRESS TO 30


        // UPLOAD DATABASE
        Db2Cloud::uploadDB();



        self::progress(50);                                                    // INCREASE PROGRESS TO 50
        $this->output->progressFinish();                                           // FINISH PROGRESS BAR

        $this->line("\n<fg=magenta>Removing default Database file.</>");
        // REMOVE FILE FROM LOCAL
        Db2Cloud::removeDBFile();

        $this->line("\n<fg=magenta>Database backup done successfully.</>");  // SET STATUS TO CREATING DATABASE FILE
    }

    /**
     *  FUNCTION IS USED TO CREATE A PROGRESS
     *
     * @param int $count
     */
    protected function progress($count=50){
        for ($i = 0; $i < $count; $i++) {
            usleep(10000);
            $this->output->progressAdvance();
        }
        return;
    }



}
