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
        Schema::create('estimate_details', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('estimate_id')->comment('見積ID');
            $table->tinyInteger('estm_dtl_order')->default(1)->comment('明細表示順');
            $table->unsignedBigInteger('service_id')->nullable()->comment('サービスID');
            $table->unsignedBigInteger('client_role_id')->comment('顧客役割ID');
            $table->string('estm_dtl_title', 255)->comment('明細項目名');
            $table->decimal('estm_dtl_unit_price', 12, 4)->default(0)->comment('見積単価');
            $table->decimal('estm_dtl_quantity', 12, 4)->default(1)->comment('見積数量');
            $table->string('estm_dtl_unit', 255)->nullable()->comment('単位');
            $table->tinyInteger('estm_dtl_tax_type')->default(1)->comment('税率区分');
            $table->decimal('estm_dtl_tax', 12, 4)->default(0)->comment('税額');
            $table->decimal('estm_dtl_amount', 12, 4)->default(0)->comment('金額');
            $table->string('estm_dtl_acc_item', 32)->nullable()->comment('科目');
            $table->string('notes', 255)->nullable()->comment('備考');
            $table->tinyInteger('isvalid')->default(1)->comment('有効');

            $table->dateTime('created_at')->useCurrent()->nullable();
            $table->dateTime('updated_at')->useCurrent()->nullable();
        });

        Schema::create('xlog_estimate_details', function (Blueprint $table) {
            $table->id();

            $table->dateTime('logged_at')->useCurrent();
            $table->string('opr', 1);
            $table->bigInteger('estm_detail_id');

            $table->unsignedBigInteger('estimate_id')->comment('見積ID');
            $table->tinyInteger('estm_dtl_order')->default(1)->comment('明細表示順');
            $table->unsignedBigInteger('service_id')->nullable()->comment('サービスID');
            $table->unsignedBigInteger('client_role_id')->comment('顧客役割ID');
            $table->string('estm_dtl_title', 255)->comment('明細項目名');
            $table->decimal('estm_dtl_unit_price', 12, 4)->default(0)->comment('見積単価');
            $table->decimal('estm_dtl_quantity', 12, 4)->default(1)->comment('見積数量');
            $table->string('estm_dtl_unit', 255)->nullable()->comment('単位');
            $table->tinyInteger('estm_dtl_tax_type')->default(1)->comment('税率区分');
            $table->decimal('estm_dtl_tax', 12, 4)->default(0)->comment('税額');
            $table->decimal('estm_dtl_amount', 12, 4)->default(0)->comment('金額');
            $table->string('estm_dtl_acc_item', 32)->nullable()->comment('科目');
            $table->string('notes', 255)->nullable()->comment('備考');
            $table->tinyInteger('isvalid')->default(1)->comment('有効');
        });

        DB::unprepared('CREATE TRIGGER trg_estimate_details_update AFTER UPDATE ON `estimate_details` FOR EACH ROW
            BEGIN
                INSERT INTO `xlog_estimate_details`
                    (`opr`, `id_estimate_detail`, `estimate_id`, `estm_dtl_order`, `service_id`, `client_role_id`, `estm_dtl_title`, `estm_dtl_unit_price`, `estm_dtl_quantity`, `estm_dtl_unit`, `estm_dtl_tax_type`, `estm_dtl_tax`, `estm_dtl_amount`, `estm_dtl_acc_item`, `notes`, `isvalid`) 
                VALUES 
                    ("U", OLD.id, OLD.estimate_id, OLD.estm_dtl_order, OLD.service_id, OLD.client_role_id, OLD.estm_dtl_title, OLD.estm_dtl_unit_price, OLD.estm_dtl_quantity, OLD.estm_dtl_unit, OLD.estm_dtl_tax_type, OLD.estm_dtl_tax, OLD.estm_dtl_amount, OLD.estm_dtl_acc_item, OLD.notes, OLD.isvalid);
            END');

        DB::unprepared('CREATE TRIGGER trg_estimate_details_delete AFTER DELETE ON `estimate_details` FOR EACH ROW
            BEGIN
                INSERT INTO `id_estimate_detail`
                    (`opr`, `id_estimate_detail`, `estimate_id`, `estm_dtl_order`, `service_id`, `client_role_id`, `estm_dtl_title`, `estm_dtl_unit_price`, `estm_dtl_quantity`, `estm_dtl_unit`, `estm_dtl_tax_type`, `estm_dtl_tax`, `estm_dtl_amount`, `estm_dtl_acc_item`, `notes`, `isvalid`) 
                VALUES 
                    ("D", OLD.id, OLD.estimate_id, OLD.estm_dtl_order, OLD.service_id, OLD.client_role_id, OLD.estm_dtl_title, OLD.estm_dtl_unit_price, OLD.estm_dtl_quantity, OLD.estm_dtl_unit, OLD.estm_dtl_tax_type, OLD.estm_dtl_tax, OLD.estm_dtl_amount, OLD.estm_dtl_acc_item, OLD.notes, OLD.isvalid);
            END');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_estimate_details_update');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_estimate_details_delete');

        Schema::dropIfExists('xlog_estimate_details');
        Schema::dropIfExists('estimate_details');
    }
};
