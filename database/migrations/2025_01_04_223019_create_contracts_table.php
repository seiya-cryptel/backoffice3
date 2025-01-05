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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('client_id')->comment('顧客ID');
            $table->tinyInteger('contract_order')->default(1)->comment('表示順');
            $table->string('contract_title', 255)->comment('契約件名');
            $table->date('contract_start')->nullable()->comment('契約開始日');
            $table->date('contract_end')->nullable()->comment('契約終了日');
            $table->date('contract_service_in')->nullable()->comment('サービス開始日');
            $table->date('contract_first_bill')->nullable()->comment('初回請求日');
            $table->date('contract_end_bill')->nullable()->comment('最終請求日');
            $table->tinyInteger('contract_interval')->default(1)->comment('繰返し月数');
            $table->tinyInteger('contract_month_ofs')->default(0)->comment('請求対象月オフセット');
            $table->tinyInteger('contract_bill_month')->default(0)->comment('請求日月オフセット');
            $table->tinyInteger('contract_bill_day')->default(99)->comment('請求日');
            $table->date('contract_next_date')->nullable()->comment('次回発行日');
            $table->string('notes', 255)->nullable()->comment('備考');
            $table->tinyInteger('isvalid')->default(1)->comment('有効');

            $table->dateTime('created_at')->useCurrent()->nullable();
            $table->dateTime('updated_at')->useCurrent()->nullable();
        });

        Schema::create('xlog_contracts', function (Blueprint $table) {
            $table->id();

            $table->dateTime('logged_at')->useCurrent();
            $table->string('opr', 1);
            $table->bigInteger('contract_id');

            $table->unsignedBigInteger('client_id')->comment('顧客ID');
            $table->tinyInteger('contract_order')->default(1)->comment('表示順');
            $table->string('contract_title', 255)->comment('契約件名');
            $table->date('contract_start')->nullable()->comment('契約開始日');
            $table->date('contract_end')->nullable()->comment('契約終了日');
            $table->date('contract_service_in')->nullable()->comment('サービス開始日');
            $table->date('contract_first_bill')->nullable()->comment('初回請求日');
            $table->date('contract_end_bill')->nullable()->comment('最終請求日');
            $table->tinyInteger('contract_interval')->default(1)->comment('繰返し月数');
            $table->tinyInteger('contract_month_ofs')->default(0)->comment('請求対象月オフセット');
            $table->tinyInteger('contract_bill_month')->default(0)->comment('請求日月オフセット');
            $table->tinyInteger('contract_bill_day')->default(99)->comment('請求日');
            $table->date('contract_next_date')->nullable()->comment('次回発行日');
            $table->string('notes', 255)->nullable()->comment('備考');
            $table->tinyInteger('isvalid')->default(1)->comment('有効');
        });

        DB::unprepared('CREATE TRIGGER trg_contracts_update AFTER UPDATE ON `contracts` FOR EACH ROW
            BEGIN
                INSERT INTO `xlog_contracts`
                    (`opr`, `id_contract`, `client_id`,`contract_order`,`contract_title`,`contract_start`,`contract_end`,`contract_service_in`,`contract_first_bill`,`contract_end_bill`,`contract_interval`,`contract_month_ofs`,`contract_bill_month`,`contract_bill_day`,`contract_next_date`,`notes`,`isvalid`) 
                VALUES 
                    ("U", OLD.id, OLD.client_id,OLD.contract_order,OLD.contract_title,OLD.contract_start,OLD.contract_end,OLD.contract_service_in,OLD.contract_first_bill,OLD.contract_end_bill,OLD.contract_interval,OLD.contract_month_ofs,OLD.contract_bill_month,OLD.contract_bill_day,OLD.contract_next_date,OLD.notes,OLD.isvalid);
            END');

        DB::unprepared('CREATE TRIGGER trg_contracts_delete AFTER DELETE ON `contracts` FOR EACH ROW
            BEGIN
                INSERT INTO `xlog_contracts`
                    (`opr`, `id_contract`, `client_id`,`contract_order`,`contract_title`,`contract_start`,`contract_end`,`contract_service_in`,`contract_first_bill`,`contract_end_bill`,`contract_interval`,`contract_month_ofs`,`contract_bill_month`,`contract_bill_day`,`contract_next_date`,`notes`,`isvalid`) 
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

        Schema::dropIfExists('xlog_contracts');
        Schema::dropIfExists('contracts');
    }
};
