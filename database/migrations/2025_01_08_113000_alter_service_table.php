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
        Schema::table('services', function (Blueprint $table) {
            $table->renameColumn('id_person_role', 'person_role_id');
        });

        Schema::table('xlog_services', function (Blueprint $table) {
            $table->renameColumn('id_person_role', 'person_role_id');
        });

        DB::unprepared('DROP TRIGGER IF EXISTS trg_services_update');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_services_delete');

        DB::unprepared('CREATE TRIGGER trg_services_update AFTER UPDATE ON `services` FOR EACH ROW
            BEGIN
                INSERT INTO `xlog_services`
                    (`opr`, `id_service`, `svc_cd`,`svc_name`,`person_role_id`,`svc_title_cover`,`svc_title_bill`,`svc_unit_price`,`svc_quantity`,`svc_unit`,`svc_tax_type`,`svc_acc_item`,`notes`,`isvalid`) 
                VALUES 
                    ("U", OLD.id, OLD.svc_cd,OLD.svc_name,OLD.person_role_id,OLD.svc_title_cover,OLD.svc_title_bill,OLD.svc_unit_price,OLD.svc_quantity,OLD.svc_unit,OLD.svc_tax_type,OLD.svc_acc_item,OLD.notes,OLD.isvalid);
            END');

        DB::unprepared('CREATE TRIGGER trg_services_delete AFTER DELETE ON `services` FOR EACH ROW
            BEGIN
                INSERT INTO `xlog_services`
                    (`opr`, `id_service`, `svc_cd`,`svc_name`,`person_role_id`,`svc_title_cover`,`svc_title_bill`,`svc_unit_price`,`svc_quantity`,`svc_unit`,`svc_tax_type`,`svc_acc_item`,`notes`,`isvalid`) 
                VALUES 
                    ("D", OLD.id, OLD.svc_cd,OLD.svc_name,OLD.person_role_id,OLD.svc_title_cover,OLD.svc_title_bill,OLD.svc_unit_price,OLD.svc_quantity,OLD.svc_unit,OLD.svc_tax_type,OLD.svc_acc_item,OLD.notes,OLD.isvalid);
            END');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_services_update');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_services_delete');
        
        Schema::create('services', function (Blueprint $table) {
            $table->renameColumn('person_role_id', 'id_person_role');
        });

        Schema::create('xlog_services', function (Blueprint $table) {
            $table->renameColumn('person_role_id', 'id_person_role');
        });
    }
};
