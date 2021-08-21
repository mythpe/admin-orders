<?php

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(User::getModelTable(), function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username');
            $table->string('role_id');
            $table->string('password');
            $table->string('positive_color')->nullable();
            $table->string('negative_color')->nullable();
            $table->timestamps();
        });
        Schema::create(Role::getModelTable(), function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
        Schema::create(Permission::getModelTable(), function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
        Schema::create('permission_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permission_id')->constrained(Permission::getModelTable())->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('user_id')->constrained(User::getModelTable())->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        foreach ( [
                      User::getModelTable(),
                      Role::getModelTable(),
                      Permission::getModelTable(),
                      'permission_user',
                  ] as $value)
        Schema::dropIfExists($value);
    }
}
