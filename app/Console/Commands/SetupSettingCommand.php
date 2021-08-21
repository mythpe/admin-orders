<?php


namespace App\Console\Commands;

use App\Console\Command;

class SetupSettingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:setting';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'application setting';

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
     * @return int|void
     */
    public function handle()
    {
        $this->applyCustomStyle();
        $this->alert($this->parseFunctionName($this->signature));
        setting()->forgetAll();
        setting([
            'start'    => 10000,
            'rst'      => 0,
            'rst_plus'  => 20000,
            'rst_minus' => -10000,
        ])->save();
        $this->info("Create setting finish");
    }
}
