<?php


namespace App\Console\Commands;

use App\Console\Command;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SetupAppCommand extends Command
{
    /** @var User|null */
    public $user = null;
    /** @var Role|null */
    public $role = null;
    /** @var Role|null */
    public $manager = null;
    /** @var User|null */
    public $admin = null;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:app {--i|ini=skip : ini application data} 
    {--m|migrate=skip : Migrate fresh force}
    {--a|all=skip : Migrate fresh and ini data}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'make app first running';

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
        $all = $this->option('all') != 'skip';
        $migrate = $this->option('migrate') != 'skip' || $all;
        $ini = $this->option('ini') != 'skip' || $all;

        $this->call('storage:link');

        if ($migrate)
            $this->call('migrate:fresh', ['--force' => !0]);

        $this->call('passport:install', ['--force' => !0]);
        $this->call('passport:keys', ['--force' => !0]);
        $this->call('setup:setting');

        Schema::disableForeignKeyConstraints();
        $this->createRoles();
        $this->createUsers();
        $this->createPermissions();

        $this->lineGreen("Admin: -- {$this->admin->role->name}");
        $this->lineGreen("User: -- {$this->user->role->name}");
        //        if ($ini) {
        //            $this->call('ini:permissions');
        //            $this->call('ini:app');
        //            $this->call('ini:shop');
        //        }

    }

    public function createRoles()
    {
        DB::table(Role::getModelTable())->truncate();
        $this->manager = Role::create([
            'name' => User::ROLES['admin'],
        ]);
        $this->echo("Role: [ {$this->manager->name} ].");

        $this->role = Role::create([
            'name' => User::ROLES['user'],
        ]);
        $this->echo("Role: [ {$this->role->name} ].");

        $this->echo("");
    }

    public function createUsers()
    {
        DB::table(User::getModelTable())->truncate();
        $password = 123456;

        if ($this->manager) {
            $this->admin = $this->manager->users()->create([
                'name'      => User::ROLES['admin'],
                'username'  => User::ROLES['admin'],
                'password'  => $password,
            ]);
            $this->echo("Admin: [ {$this->admin->name} ].");
            $this->echo("Password: [ {$password} ].");
        }
        if ($this->role) {
            $this->user = $this->role->users()->create([
                'name'      => User::ROLES['user'],
                'username'  => User::ROLES['user'],
                'password'  => $password,
            ]);
            $this->echo("User: [ {$this->user->name} ].");

            $this->echo("Password: [ {$password} ].");
        }
        $this->echo("");

    }

    public function createPermissions()
    {
        DB::table(Permission::getModelTable())->truncate();
        if ($this->user) {
//            $permissions = ['open', 'close', 'user', 'rank', 'rankTotal'];
            $permissions = ['open', 'close', 'rank'];
            foreach ($permissions as $permission) {
                $model = Permission::create([
                    'name' => $permission,
                ]);
                $this->echo("Permission: [ {$model->name} ].");
                $this->user->permissions()->sync($model, !1);
            }
        }

        $this->echo("");
    }
}
