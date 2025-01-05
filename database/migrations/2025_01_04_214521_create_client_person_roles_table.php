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
        Schema::create('client_person_roles', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('client_person_id')->comment('担当者ID');
            $table->unsignedBigInteger('person_role_id')->comment('顧客担当者役割ID');
            $table->tinyInteger('post_to')->default(1)->comment('送付フラグ');
            $table->string('notes', 255)->nullable()->comment('備考');
            $table->tinyInteger('isvalid')->default(1)->comment('有効');

            $table->dateTime('created_at')->useCurrent()->nullable();
            $table->dateTime('updated_at')->useCurrent()->nullable();        

            $table->unique(['client_person_id', 'person_role_id']);
        });

        Schema::create('xlog_client_person_roles', function (Blueprint $table) {
            $table->id();

            $table->dateTime('logged_at')->useCurrent();
            $table->string('opr', 1);
            $table->bigInteger('client_person_roles_id');

            $table->unsignedBigInteger('client_person_id')->comment('担当者ID');
            $table->unsignedBigInteger('person_role_id')->comment('顧客担当者役割ID');
            $table->tinyInteger('post_to')->default(1)->comment('送付フラグ');
            $table->string('notes', 255)->nullable()->comment('備考');
            $table->tinyInteger('isvalid')->default(1)->comment('有効');
        });

        DB::unprepared('CREATE TRIGGER trg_client_person_roles_update AFTER UPDATE ON `client_person_roles` FOR EACH ROW
            BEGIN
                INSERT INTO `xlog_client_person_roles`
                    (`opr`, `id_client_person_roles`, `client_person_id`,`person_role_id`,`post_to`,`notes`,`isvalid`) 
                VALUES 
                    ("U", OLD.id, OLD.client_person_id,OLD.person_role_id,OLD.post_to,OLD.notes,OLD.isvalid);
            END');

        DB::unprepared('CREATE TRIGGER trg_client_person_roles_delete AFTER DELETE ON `client_person_roles` FOR EACH ROW
            BEGIN
                INSERT INTO `xlog_client_person_roles`
                    (`opr`, `id_client_person_roles`, `client_person_id`,`person_role_id`,`post_to`,`notes`,`isvalid`) 
                VALUES 
                    ("D", OLD.id, OLD.client_person_id,OLD.person_role_id,OLD.post_to,OLD.notes,OLD.isvalid);
            END');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_client_person_roles_update');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_client_person_roles_delete');

        Schema::dropIfExists('xlog_client_person_roles');
        Schema::dropIfExists('client_person_roles');
    }
};
