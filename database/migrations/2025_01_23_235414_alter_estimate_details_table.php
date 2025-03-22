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
        Schema::table('estimate_details', function (Blueprint $table) {
            $table->renameColumn('client_role_id', 'person_role_id');
        });
        Schema::table('xlog_estimate_details', function (Blueprint $table) {
            $table->renameColumn('client_role_id', 'person_role_id');
        });
        
        DB::unprepared('DROP TRIGGER IF EXISTS trg_estimate_details_update');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_estimate_details_delete');

        DB::unprepared('CREATE TRIGGER trg_estimate_details_update AFTER UPDATE ON `estimate_details` FOR EACH ROW
            BEGIN
                INSERT INTO `xlog_estimate_details`
                    (`opr`, `estm_detail_id`, `estimate_id`, `estm_dtl_order`, `service_id`, `person_role_id`, 
                    `estm_dtl_title`, `estm_dtl_unit_price`, `estm_dtl_quantity`, `estm_dtl_unit`, 
                    `estm_dtl_tax_type`, `estm_dtl_tax`, `estm_dtl_amount`, `estm_dtl_acc_item`, 
                    `notes`, `isvalid`) 
                VALUES 
                    ("U", OLD.id, OLD.estimate_id, OLD.estm_dtl_order, OLD.service_id, OLD.person_role_id, 
                    OLD.estm_dtl_title, OLD.estm_dtl_unit_price, OLD.estm_dtl_quantity, OLD.estm_dtl_unit, 
                    OLD.estm_dtl_tax_type, OLD.estm_dtl_tax, OLD.estm_dtl_amount, OLD.estm_dtl_acc_item, 
                    OLD.notes, OLD.isvalid);
            END');

        DB::unprepared('CREATE TRIGGER trg_estimate_details_delete AFTER DELETE ON `estimate_details` FOR EACH ROW
            BEGIN
                INSERT INTO `xlog_estimate_details`
                    (`opr`, `estm_detail_id`, `estimate_id`, `estm_dtl_order`, `service_id`, `person_role_id`, 
                    `estm_dtl_title`, `estm_dtl_unit_price`, `estm_dtl_quantity`, `estm_dtl_unit`, 
                    `estm_dtl_tax_type`, `estm_dtl_tax`, `estm_dtl_amount`, `estm_dtl_acc_item`, 
                    `notes`, `isvalid`) 
                VALUES 
                    ("D", OLD.id, OLD.estimate_id, OLD.estm_dtl_order, OLD.service_id, OLD.person_role_id, 
                    OLD.estm_dtl_title, OLD.estm_dtl_unit_price, OLD.estm_dtl_quantity, OLD.estm_dtl_unit, 
                    OLD.estm_dtl_tax_type, OLD.estm_dtl_tax, OLD.estm_dtl_amount, OLD.estm_dtl_acc_item, 
                    OLD.notes, OLD.isvalid);
            END');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_estimate_details_update');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_estimate_details_delete');

        Schema::table('estimate_details', function (Blueprint $table) {
            $table->renameColumn('person_role_id', 'client_role_id');
        });
        Schema::table('xlog_estimate_details', function (Blueprint $table) {
            $table->renameColumn('person_role_id', 'client_role_id');
        });
    }
};
