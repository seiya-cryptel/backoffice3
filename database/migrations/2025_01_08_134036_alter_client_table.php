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
        Schema::table('clients', function (Blueprint $table) {
            $table->renameColumn('id_client_group', 'client_group_id');
        });

        Schema::table('xlog_clients', function (Blueprint $table) {
            $table->renameColumn('id_client_group', 'client_group_id');
        });

        DB::unprepared('DROP TRIGGER IF EXISTS trg_clients_update');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_clients_delete');

        DB::unprepared('CREATE TRIGGER trg_clients_update AFTER UPDATE ON `clients` FOR EACH ROW
            BEGIN
                INSERT INTO `xlog_clients`
                    (`opr`, `id_client`, `cl_mstno`,`cl_full_name`,`cl_short_name`,`cl_kana_name`,`client_group_id`,`cl_zip`,`cl_addr1`,`cl_addr2`,`cl_tel`,`cl_fax`,`notes`,`isvalid`) 
                VALUES 
                    ("U", OLD.id, OLD.cl_mstno,OLD.cl_full_name,OLD.cl_short_name,OLD.cl_kana_name,OLD.client_group_id,OLD.cl_zip,OLD.cl_addr1,OLD.cl_addr2,OLD.cl_tel,OLD.cl_fax,OLD.notes,OLD.isvalid);
            END');

        DB::unprepared('CREATE TRIGGER trg_clients_delete AFTER DELETE ON `clients` FOR EACH ROW
            BEGIN
                INSERT INTO `xlog_clients`
                    (`opr`, `id_client`, `cl_mstno`,`cl_full_name`,`cl_short_name`,`cl_kana_name`,`client_group_id`,`cl_zip`,`cl_addr1`,`cl_addr2`,`cl_tel`,`cl_fax`,`notes`,`isvalid`) 
                VALUES 
                    ("D", OLD.id, OLD.cl_mstno,OLD.cl_full_name,OLD.cl_short_name,OLD.cl_kana_name,OLD.client_group_id,OLD.cl_zip,OLD.cl_addr1,OLD.cl_addr2,OLD.cl_tel,OLD.cl_fax,OLD.notes,OLD.isvalid);
            END');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_clients_update');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_clients_delete');

        Schema::table('clients', function (Blueprint $table) {
            $table->renameColumn('client_group_id', 'id_client_group');
        });

        Schema::table('xlog_clients', function (Blueprint $table) {
            $table->renameColumn('client_group_id', 'id_client_group');
        });
    }
};
