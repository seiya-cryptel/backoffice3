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
        Schema::create('person_roles', function (Blueprint $table) {
            $table->id();

            $table->string('role_cd', 8)->comment('役割コード');
            $table->string('role_name', 255)->comment('役割名');
            $table->string('notes', 255)->nullable()->comment('備考');
            $table->tinyInteger('isvalid')->default(1)->comment('有効');

            $table->dateTime('created_at')->useCurrent()->nullable();
            $table->dateTime('updated_at')->useCurrent()->nullable();

            $table->unique(['role_cd']);
        });

        Schema::create('xlog_person_roles', function (Blueprint $table) {
            $table->id();

            $table->dateTime('logged_at')->useCurrent();
            $table->string('opr', 1);
            $table->bigInteger('id_person_role');

            $table->string('role_cd', 8)->comment('役割コード');
            $table->string('role_name', 255)->comment('役割名');
            $table->string('notes', 255)->nullable()->comment('備考');
            $table->tinyInteger('isvalid')->default(1)->comment('有効');
        });

        DB::unprepared('CREATE TRIGGER trg_person_roles_update AFTER UPDATE ON `person_roles` FOR EACH ROW
            BEGIN
                INSERT INTO `xlog_person_roles`
                    (`opr`, `id_person_role`, `role_cd`, `role_name`, `notes`, `isvalid`) 
                VALUES 
                    ("U", OLD.id, OLD.role_cd, OLD.role_name, OLD.notes, OLD.isvalid);
            END');

        DB::unprepared('CREATE TRIGGER trg_person_roles_delete AFTER DELETE ON `person_roles` FOR EACH ROW
            BEGIN
                INSERT INTO `xlog_person_roles`
                    (`opr`, `id_person_role`, `role_cd`, `role_name`, `notes`, `isvalid`) 
                VALUES 
                    ("D", OLD.id, OLD.role_cd, OLD.role_name, OLD.notes, OLD.isvalid);
            END');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_person_roles_update');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_person_roles_delete');

        Schema::dropIfExists('person_roles');
        Schema::dropIfExists('xlog_person_roles');
    }
};
