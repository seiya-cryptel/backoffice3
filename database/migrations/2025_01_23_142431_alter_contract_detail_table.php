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
        DB::unprepared('DROP TRIGGER IF EXISTS trg_contract_details_update');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_contract_details_delete');

        DB::unprepared('CREATE TRIGGER trg_contract_details_update AFTER UPDATE ON `contract_details` FOR EACH ROW
            BEGIN
                INSERT INTO `xlog_contract_details`
                    (`opr`, `contract_detail_id`, `contract_id`, `cont_dtl_order`, `service_id`, `client_role_id`, `cont_dtl_title`, `cont_dtl_unit_price`, `cont_dtl_quantity`, `cont_dtl_unit`, `cont_dtl_tax_type`, `cont_dtl_acc_item`, `notes`, `isvalid`) 
                VALUES 
                    ("U", OLD.id, OLD.contract_id, OLD.cont_dtl_order, OLD.service_id, OLD.client_role_id, OLD.cont_dtl_title, OLD.cont_dtl_unit_price, OLD.cont_dtl_quantity, OLD.cont_dtl_unit, OLD.cont_dtl_tax_type, OLD.cont_dtl_acc_item, OLD.notes, OLD.isvalid);
            END');

        DB::unprepared('CREATE TRIGGER trg_contract_details_delete AFTER DELETE ON `contract_details` FOR EACH ROW
            BEGIN
                INSERT INTO `xlog_contract_details`
                    (`opr`, `contract_detail_id`, `contract_id`, `cont_dtl_order`, `service_id`, `client_role_id`, `cont_dtl_title`, `cont_dtl_unit_price`, `cont_dtl_quantity`, `cont_dtl_unit`, `cont_dtl_tax_type`, `cont_dtl_acc_item`, `notes`, `isvalid`) 
                VALUES 
                    ("D", OLD.id, OLD.contract_id, OLD.cont_dtl_order, OLD.service_id, OLD.client_role_id, OLD.cont_dtl_title, OLD.cont_dtl_unit_price, OLD.cont_dtl_quantity, OLD.cont_dtl_unit, OLD.cont_dtl_tax_type, OLD.cont_dtl_acc_item, OLD.notes, OLD.isvalid);
            END');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_contract_details_update');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_contract_details_delete');
    }
};
