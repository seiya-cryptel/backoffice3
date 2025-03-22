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
        Schema::table('client_persons', function (Blueprint $table) {
            $table->renameColumn('id_client', 'client_id');
        });
        Schema::table('xlog_client_persons', function (Blueprint $table) {
            $table->renameColumn('id_client', 'client_id');
        });

        DB::unprepared('DROP TRIGGER IF EXISTS trg_client_persons_update');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_client_persons_delete');

        DB::unprepared('CREATE TRIGGER trg_client_persons_update AFTER UPDATE ON `client_persons` FOR EACH ROW
            BEGIN
                INSERT INTO `xlog_client_persons`
                    (`opr`, `id_client_persons`, `client_id`,`psn_order`,`psn_name`,`psn_email`,`psn_zip`,`psn_addr1`,`psn_addr2`,`psn_tel`,`psn_fax`,`psn_branch`,`psn_title`,`psn_keisho`,`notes`,`isvalid`) 
                VALUES 
                    ("U", OLD.id, OLD.client_id,OLD.psn_order,OLD.psn_name,OLD.psn_email,OLD.psn_zip,OLD.psn_addr1,OLD.psn_addr2,OLD.psn_tel,OLD.psn_fax,OLD.psn_branch,OLD.psn_title,OLD.psn_keisho,OLD.notes,OLD.isvalid);
            END');

        DB::unprepared('CREATE TRIGGER trg_client_persons_delete AFTER DELETE ON `client_persons` FOR EACH ROW
            BEGIN
                INSERT INTO `xlog_client_persons`
                    (`opr`, `id_client_persons`, `client_id`,`psn_order`,`psn_name`,`psn_email`,`psn_zip`,`psn_addr1`,`psn_addr2`,`psn_tel`,`psn_fax`,`psn_branch`,`psn_title`,`psn_keisho`,`notes`,`isvalid`) 
                VALUES 
                    ("D", OLD.id, OLD.client_id,OLD.psn_order,OLD.psn_name,OLD.psn_email,OLD.psn_zip,OLD.psn_addr1,OLD.psn_addr2,OLD.psn_tel,OLD.psn_fax,OLD.psn_branch,OLD.psn_title,OLD.psn_keisho,OLD.notes,OLD.isvalid);
            END');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_client_persons_update');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_client_persons_delete');

        Schema::table('client_persons', function (Blueprint $table) {
            $table->renameColumn('client_id', 'id_client');
        });
        Schema::table('xlog_client_persons', function (Blueprint $table) {
            $table->renameColumn('client_id', 'id_client');
        });
    }
};
