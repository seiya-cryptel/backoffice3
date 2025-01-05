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
        Schema::create('bills', function (Blueprint $table) {
            $table->id();

            $table->string('bill_no', 32)->comment('請求番号');
            $table->unsignedBigInteger('client_id')->comment('顧客ID');
            $table->unsignedBigInteger('client_role_id')->comment('顧客ID');
            $table->string('bill_title', 255)->comment('請求件名');
            $table->date('bill_date')->nullable()->comment('請求日');
            $table->string('payment_notice', 255)->default('お客様支払い条件による')->comment('支払条件');
            $table->tinyInteger('show_ceo')->default(0)->comment('代表者表示フラグ');
            $table->string('notes', 255)->nullable()->comment('備考');
            $table->tinyInteger('isvalid')->default(1)->comment('有効');

            $table->dateTime('created_at')->useCurrent()->nullable();
            $table->dateTime('updated_at')->useCurrent()->nullable();

            $table->unique(['bill_no']);
        });

        DB::unprepared('CREATE TRIGGER trg_bills_update AFTER UPDATE ON `bills` FOR EACH ROW
            BEGIN
                INSERT INTO `xlog_bills`
                    (`opr`, `id_bill`, `bill_no`, `client_id`, `client_role_id`, `bill_title`, `bill_date`, `payment_notice`, `show_ceo`, `notes`, `isvalid`) 
                VALUES 
                    ("U", OLD.id, OLD.bill_no, OLD.client_id, OLD.client_role_id, OLD.bill_title, OLD.bill_date, OLD.payment_notice, OLD.show_ceo, OLD.notes, OLD.isvalid);
            END');

        DB::unprepared('CREATE TRIGGER trg_bills_delete AFTER DELETE ON `bills` FOR EACH ROW
            BEGIN
                INSERT INTO `xlog_bills`
                    (`opr`, `id_bill`, `bill_no`, `client_id`, `client_role_id`, `bill_title`, `bill_date`, `payment_notice`, `show_ceo`, `notes`, `isvalid`) 
                VALUES 
                    ("D", OLD.id, OLD.bill_no, OLD.client_id, OLD.client_role_id, OLD.bill_title, OLD.bill_date, OLD.payment_notice, OLD.show_ceo, OLD.notes, OLD.isvalid);
            END');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_bills_update');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_bills_delete');

        Schema::dropIfExists('xlog_bills');
        Schema::dropIfExists('bills');
    }
};
