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
        DB::unprepared('DROP TRIGGER IF EXISTS trg_contracts_update');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_contracts_delete');

        DB::unprepared('CREATE TRIGGER trg_contracts_update AFTER UPDATE ON `contracts` FOR EACH ROW
            BEGIN
                INSERT INTO `xlog_contracts`
                    (`opr`, `contract_id`, `client_id`,`contract_order`,`contract_title`,`contract_start`,`contract_end`,`contract_service_in`,`contract_first_bill`,`contract_end_bill`,`contract_interval`,`contract_month_ofs`,`contract_bill_month`,`contract_bill_day`,`contract_next_date`,`notes`,`isvalid`) 
                VALUES 
                    ("U", OLD.id, OLD.client_id,OLD.contract_order,OLD.contract_title,OLD.contract_start,OLD.contract_end,OLD.contract_service_in,OLD.contract_first_bill,OLD.contract_end_bill,OLD.contract_interval,OLD.contract_month_ofs,OLD.contract_bill_month,OLD.contract_bill_day,OLD.contract_next_date,OLD.notes,OLD.isvalid);
            END');

        DB::unprepared('CREATE TRIGGER trg_contracts_delete AFTER DELETE ON `contracts` FOR EACH ROW
            BEGIN
                INSERT INTO `xlog_contracts`
                    (`opr`, `contract_id`, `client_id`,`contract_order`,`contract_title`,`contract_start`,`contract_end`,`contract_service_in`,`contract_first_bill`,`contract_end_bill`,`contract_interval`,`contract_month_ofs`,`contract_bill_month`,`contract_bill_day`,`contract_next_date`,`notes`,`isvalid`) 
                VALUES 
                    ("D", OLD.id, OLD.client_id,OLD.contract_order,OLD.contract_title,OLD.contract_start,OLD.contract_end,OLD.contract_service_in,OLD.contract_first_bill,OLD.contract_end_bill,OLD.contract_interval,OLD.contract_month_ofs,OLD.contract_bill_month,OLD.contract_bill_day,OLD.contract_next_date,OLD.notes,OLD.isvalid);
            END');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_contracts_update');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_contracts_delete');
    }
};
