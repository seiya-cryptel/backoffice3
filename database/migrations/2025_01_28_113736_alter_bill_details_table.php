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
        Schema::table('bill_details', function (Blueprint $table) {
            $table->renameColumn('client_role_id', 'person_role_id');
        });
        Schema::table('xlog_bill_details', function (Blueprint $table) {
            $table->renameColumn('client_role_id', 'person_role_id');
        });

        DB::unprepared('DROP TRIGGER IF EXISTS trg_bill_details_update');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_bill_details_delete');

        DB::unprepared('CREATE TRIGGER trg_bill_details_update AFTER UPDATE ON `bill_details` FOR EACH ROW
            BEGIN
                INSERT INTO `xlog_bill_details`
                    (`opr`, `id_bill_detail`, `bill_id`, `bill_dtl_order`, `service_id`, `person_role_id`, 
                    `bill_dtl_title`, `bill_dtl_unit_price`, `bill_dtl_quantity`, `bill_dtl_unit`, 
                    `bill_dtl_tax_type`, `bill_dtl_tax`, `bill_dtl_amount`, `bill_dtl_acc_item`, 
                    `notes`, `isvalid`) 
                VALUES 
                    ("U", OLD.id, OLD.bill_id, OLD.bill_dtl_order, OLD.service_id, OLD.person_role_id, 
                    OLD.bill_dtl_title, OLD.bill_dtl_unit_price, OLD.bill_dtl_quantity, OLD.bill_dtl_unit, 
                    OLD.bill_dtl_tax_type, OLD.bill_dtl_tax, OLD.bill_dtl_amount, OLD.bill_dtl_acc_item, 
                    OLD.notes, OLD.isvalid);
            END');

        DB::unprepared('CREATE TRIGGER trg_bill_details_delete AFTER DELETE ON `bill_details` FOR EACH ROW
            BEGIN
                INSERT INTO `id_bill_detail`
                    (`opr`, `id_bill_detail`, `bill_id`, `bill_dtl_order`, `service_id`, `person_role_id`, 
                    `bill_dtl_title`, `bill_dtl_unit_price`, `bill_dtl_quantity`, `bill_dtl_unit`, 
                    `bill_dtl_tax_type`, `bill_dtl_tax`, `bill_dtl_amount`, `bill_dtl_acc_item`, 
                    `notes`, `isvalid`) 
                VALUES 
                    ("D", OLD.id, OLD.bill_id, OLD.bill_dtl_order, OLD.service_id, OLD.person_role_id, 
                    OLD.bill_dtl_title, OLD.bill_dtl_unit_price, OLD.bill_dtl_quantity, OLD.bill_dtl_unit, 
                    OLD.bill_dtl_tax_type, OLD.bill_dtl_tax, OLD.bill_dtl_amount, OLD.bill_dtl_acc_item, 
                    OLD.notes, OLD.isvalid);
            END');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_bill_details_update');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_bill_details_delete');

        Schema::table('bill_details', function (Blueprint $table) {
            $table->renameColumn('person_role_id', 'client_role_id');
        });
        Schema::table('xlog_bill_details', function (Blueprint $table) {
            $table->renameColumn('person_role_id', 'client_role_id');
        });
    }
};
