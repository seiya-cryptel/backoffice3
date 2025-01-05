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
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();

            $table->string('sys_name', 255)->comment('変数名');
            $table->tinyInteger('sys_index')->default(0)->comment('インデックス');
            $table->tinyInteger('sys_istext')->default(1)->comment('テキストフラグ');
            $table->string('sys_txtval', 255)->nullable()->comment('テキスト値');
            $table->decimal('sys_numval', 12, 4)->nullable()->comment('数値');
            $table->string('notes', 255)->nullable()->comment('備考');

            $table->dateTime('created_at')->useCurrent()->nullable();
            $table->dateTime('updated_at')->useCurrent()->nullable();

            $table->unique(['sys_name', 'sys_index']);
        });

        Schema::create('xlog_app_settings', function (Blueprint $table) {
            $table->id();

            $table->dateTime('logged_at')->useCurrent();
            $table->string('opr', 1);
            $table->bigInteger('id_app_setting');

            $table->string('sys_name', 255)->comment('変数名');
            $table->tinyInteger('sys_index')->default(0)->comment('インデックス');
            $table->tinyInteger('sys_istext')->default(1)->comment('テキストフラグ');
            $table->string('sys_txtval', 255)->nullable()->comment('テキスト値');
            $table->decimal('sys_numval', 12, 4)->nullable()->comment('数値');
            $table->string('notes', 255)->nullable()->comment('備考');
        });

        DB::unprepared('CREATE TRIGGER trg_app_settings_update AFTER UPDATE ON `app_settings` FOR EACH ROW
            BEGIN
                INSERT INTO `xlog_app_settings`
                    (`opr`, `id_app_setting`, `sys_name`, `sys_index`, `sys_istext`, `sys_txtval`, `sys_numval`, `notes`) 
                VALUES 
                    ("U", OLD.id, OLD.sys_name, OLD.sys_index, OLD.sys_istext, OLD.sys_txtval, OLD.sys_numval, OLD.notes);
            END');

        DB::unprepared('CREATE TRIGGER trg_app_settings_delete AFTER DELETE ON `app_settings` FOR EACH ROW
            BEGIN
                INSERT INTO `xlog_app_settings`
                    (`opr`, `id_app_setting`, `sys_name`, `sys_index`, `sys_istext`, `sys_txtval`, `sys_numval`, `notes`) 
                VALUES 
                    ("D", OLD.id, OLD.sys_name, OLD.sys_index, OLD.sys_istext, OLD.sys_txtval, OLD.sys_numval, OLD.notes);
            END');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_app_settings_update');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_app_settings_delete');

        Schema::dropIfExists('app_settings');
        Schema::dropIfExists('xlog_app_settings');
    }
};
