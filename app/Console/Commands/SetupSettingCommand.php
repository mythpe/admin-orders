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
            'start'       => 10000,
            'rst'         => 0,
            'rst_plus'    => 0,
            'rst_minus'   => 0,
            'lmt_up'      => 15000,
            'lmt_dn'      => 5000,
            'open_fields' => 1,
            'clr_plus'    => 100,
            'clr_minus'   => 100,
        ])->save();
        $this->info("Create setting finish");
    }
}
