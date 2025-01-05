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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();

            $table->string('cl_mstno', 8)->comment('顧客番号');
            $table->string('cl_full_name', 255)->comment('正式社名');
            $table->string('cl_short_name', 255)->comment('略称');
            $table->string('cl_kana_name', 255)->comment('社名カナ');
            $table->unsignedBigInteger('id_client_group')->nullable()->comment('顧客グループID');
            $table->string('cl_zip', 8)->nullable()->comment('本社郵便番号 123-4567');
            $table->string('cl_addr1', 255)->nullable()->comment('本社住所1');
            $table->string('cl_addr2', 255)->nullable()->comment('本社住所2');
            $table->string('cl_tel', 255)->nullable()->comment('本社電話番号');
            $table->string('cl_fax', 255)->nullable()->comment('本社FAX番号');
            $table->string('notes', 255)->nullable()->comment('備考');
            $table->tinyInteger('isvalid')->default(1)->comment('有効');

            $table->dateTime('created_at')->useCurrent()->nullable();
            $table->dateTime('updated_at')->useCurrent()->nullable();

            $table->unique(['cl_mstno']);
        });

        Schema::create('xlog_clients', function (Blueprint $table) {
            $table->id();

            $table->dateTime('logged_at')->useCurrent();
            $table->string('opr', 1);
            $table->bigInteger('id_client');

            $table->string('cl_mstno', 8)->comment('顧客番号');
            $table->string('cl_full_name', 255)->comment('正式社名');
            $table->string('cl_short_name', 255)->comment('略称');
            $table->string('cl_kana_name', 255)->comment('社名カナ');
            $table->unsignedBigInteger('id_client_group')->nullable()->comment('顧客グループID');
            $table->string('cl_zip', 8)->nullable()->comment('本社郵便番号 123-4567');
            $table->string('cl_addr1', 255)->nullable()->comment('本社住所1');
            $table->string('cl_addr2', 255)->nullable()->comment('本社住所2');
            $table->string('cl_tel', 255)->nullable()->comment('本社電話番号');
            $table->string('cl_fax', 255)->nullable()->comment('本社FAX番号');
            $table->string('notes', 255)->nullable()->comment('備考');
            $table->tinyInteger('isvalid')->default(1)->comment('有効');
        });

        DB::unprepared('CREATE TRIGGER trg_clients_update AFTER UPDATE ON `clients` FOR EACH ROW
            BEGIN
                INSERT INTO `xlog_clients`
                    (`opr`, `id_client`, `cl_mstno`,`cl_full_name`,`cl_short_name`,`cl_kana_name`,`id_client_group`,`cl_zip`,`cl_addr1`,`cl_addr2`,`cl_tel`,`cl_fax`,`notes`,`isvalid`) 
                VALUES 
                    ("U", OLD.id, OLD.cl_mstno,OLD.cl_full_name,OLD.cl_short_name,OLD.cl_kana_name,OLD.id_client_group,OLD.cl_zip,OLD.cl_addr1,OLD.cl_addr2,OLD.cl_tel,OLD.cl_fax,OLD.notes,OLD.isvalid);
            END');

        DB::unprepared('CREATE TRIGGER trg_clients_delete AFTER DELETE ON `clients` FOR EACH ROW
            BEGIN
                INSERT INTO `xlog_clients`
                    (`opr`, `id_client`, `cl_mstno`,`cl_full_name`,`cl_short_name`,`cl_kana_name`,`id_client_group`,`cl_zip`,`cl_addr1`,`cl_addr2`,`cl_tel`,`cl_fax`,`notes`,`isvalid`) 
                VALUES 
                    ("D", OLD.id, OLD.cl_mstno,OLD.cl_full_name,OLD.cl_short_name,OLD.cl_kana_name,OLD.id_client_group,OLD.cl_zip,OLD.cl_addr1,OLD.cl_addr2,OLD.cl_tel,OLD.cl_fax,OLD.notes,OLD.isvalid);
            END');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_clients_update');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_clients_delete');

        Schema::dropIfExists('xlog_clients');
        Schema::dropIfExists('clients');
    }
};
