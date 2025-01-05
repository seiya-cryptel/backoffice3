<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('client_groups', function (Blueprint $table) {
            $table->id();

            $table->string('cl_group_cd', 8)->comment('役割コード');
            $table->string('cl_group_name', 255)->comment('役割名');
            $table->string('notes', 255)->nullable()->comment('備考');
            $table->tinyInteger('isvalid')->default(1)->comment('有効');

            $table->dateTime('created_at')->useCurrent()->nullable();
            $table->dateTime('updated_at')->useCurrent()->nullable();

            $table->unique(['cl_group_cd']);
        });

        Schema::create('xlog_client_groups', function (Blueprint $table) {
            $table->id();

            $table->dateTime('logged_at')->useCurrent();
            $table->string('opr', 1);
            $table->bigInteger('id_person_role');

            $table->string('cl_group_cd', 8)->comment('役割コード');
            $table->string('cl_group_name', 255)->comment('役割名');
            $table->string('notes', 255)->nullable()->comment('備考');
            $table->tinyInteger('isvalid')->default(1)->comment('有効');
        });

        DB::unprepared('CREATE TRIGGER trg_client_groups_update AFTER UPDATE ON `client_groups` FOR EACH ROW
            BEGIN
                INSERT INTO `xlog_client_groups`
                    (`opr`, `id_person_role`, `cl_group_cd`, `cl_group_name`, `notes`, `isvalid`) 
                VALUES 
                    ("U", OLD.id, OLD.cl_group_cd, OLD.cl_group_name, OLD.notes, OLD.isvalid);
            END');

        DB::unprepared('CREATE TRIGGER trg_client_groups_delete AFTER DELETE ON `client_groups` FOR EACH ROW
            BEGIN
                INSERT INTO `xlog_client_groups`
                    (`opr`, `id_person_role`, `cl_group_cd`, `cl_group_name`, `notes`, `isvalid`) 
                VALUES 
                    ("D", OLD.id, OLD.cl_group_cd, OLD.cl_group_name, OLD.notes, OLD.isvalid);
            END');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_client_groups_update');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_client_groups_delete');

        Schema::dropIfExists('client_groups');
        Schema::dropIfExists('xlog_client_groups');
    }
};
