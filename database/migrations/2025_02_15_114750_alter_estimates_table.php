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
        DB::unprepared('DROP TRIGGER IF EXISTS trg_estimates_update');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_estimates_delete');

        DB::unprepared('CREATE TRIGGER trg_estimates_update AFTER UPDATE ON `estimates` FOR EACH ROW
            BEGIN
                INSERT INTO `xlog_estimates`
                    (`opr`, `estimate_id`, `client_id`, `estimate_no`, `estimate_title`, `estimate_date`, 
                    `deliverly_date`, `deliverly_place`, `payment_notice`, `valid_until`, `show_ceo`, `notes`, `isvalid`) 
                VALUES 
                    ("U", OLD.id, OLD.client_id, OLD.estimate_no, OLD.estimate_title, OLD.estimate_date, 
                    OLD.deliverly_date, OLD.deliverly_place, OLD.payment_notice, OLD.valid_until, OLD.show_ceo, OLD.notes, OLD.isvalid);
            END');

        DB::unprepared('CREATE TRIGGER trg_estimates_delete AFTER DELETE ON `estimates` FOR EACH ROW
            BEGIN
                INSERT INTO `xlog_estimates`
                    (`opr`, `estimate_id`, `client_id`, `estimate_no`, `estimate_title`, `estimate_date`, 
                    `deliverly_date`, `deliverly_place`, `payment_notice`, `valid_until`, `show_ceo`, `notes`, `isvalid`) 
                VALUES 
                    ("D", OLD.id, OLD.client_id, OLD.estimate_no, OLD.estimate_title, OLD.estimate_date, 
                    OLD.deliverly_date, OLD.deliverly_place, OLD.payment_notice, OLD.valid_until, OLD.show_ceo, OLD.notes, OLD.isvalid);
            END');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_estimates_update');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_estimates_delete');
    }
};
