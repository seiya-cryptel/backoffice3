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
        Schema::create('bill_details', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('bill_id')->comment('請求ID');
            $table->tinyInteger('bill_dtl_order')->default(1)->comment('明細表示順');
            $table->unsignedBigInteger('service_id')->nullable()->comment('サービスID');
            $table->unsignedBigInteger('client_role_id')->comment('顧客役割ID');
            $table->string('bill_dtl_title', 255)->comment('明細項目名');
            $table->decimal('bill_dtl_unit_price', 12, 4)->default(0)->comment('請求単価');
            $table->decimal('bill_dtl_quantity', 12, 4)->default(1)->comment('請求数量');
            $table->string('bill_dtl_unit', 255)->nullable()->comment('単位');
            $table->tinyInteger('bill_dtl_tax_type')->default(1)->comment('税率区分');
            $table->decimal('bill_dtl_tax', 12, 4)->default(0)->comment('税額');
            $table->decimal('bill_dtl_amount', 12, 4)->default(0)->comment('金額');
            $table->string('bill_dtl_acc_item', 32)->nullable()->comment('科目');
            $table->string('notes', 255)->nullable()->comment('備考');
            $table->tinyInteger('isvalid')->default(1)->comment('有効');

            $table->dateTime('created_at')->useCurrent()->nullable();
            $table->dateTime('updated_at')->useCurrent()->nullable();
        });

        Schema::create('xlog_bill_details', function (Blueprint $table) {
            $table->id();

            $table->dateTime('logged_at')->useCurrent();
            $table->string('opr', 1);
            $table->bigInteger('bill_detail_id');

            $table->unsignedBigInteger('bill_id')->comment('請求ID');
            $table->tinyInteger('bill_dtl_order')->default(1)->comment('明細表示順');
            $table->unsignedBigInteger('service_id')->nullable()->comment('サービスID');
            $table->unsignedBigInteger('client_role_id')->comment('顧客役割ID');
            $table->string('bill_dtl_title', 255)->comment('明細項目名');
            $table->decimal('bill_dtl_unit_price', 12, 4)->default(0)->comment('請求単価');
            $table->decimal('bill_dtl_quantity', 12, 4)->default(1)->comment('請求数量');
            $table->string('bill_dtl_unit', 255)->nullable()->comment('単位');
            $table->tinyInteger('bill_dtl_tax_type')->default(1)->comment('税率区分');
            $table->decimal('bill_dtl_tax', 12, 4)->default(0)->comment('税額');
            $table->decimal('bill_dtl_amount', 12, 4)->default(0)->comment('金額');
            $table->string('bill_dtl_acc_item', 32)->nullable()->comment('科目');
            $table->string('notes', 255)->nullable()->comment('備考');
            $table->tinyInteger('isvalid')->default(1)->comment('有効');
        });

        DB::unprepared('CREATE TRIGGER trg_bill_details_update AFTER UPDATE ON `bill_details` FOR EACH ROW
            BEGIN
                INSERT INTO `xlog_bill_details`
                    (`opr`, `id_bill_detail`, `bill_id`, `bill_dtl_order`, `service_id`, `client_role_id`, `bill_dtl_title`, `bill_dtl_unit_price`, `bill_dtl_quantity`, `bill_dtl_unit`, `bill_dtl_tax_type`, `bill_dtl_tax`, `bill_dtl_amount`, `bill_dtl_acc_item`, `notes`, `isvalid`) 
                VALUES 
                    ("U", OLD.id, OLD.bill_id, OLD.bill_dtl_order, OLD.service_id, OLD.client_role_id, OLD.bill_dtl_title, OLD.bill_dtl_unit_price, OLD.bill_dtl_quantity, OLD.bill_dtl_unit, OLD.bill_dtl_tax_type, OLD.bill_dtl_tax, OLD.bill_dtl_amount, OLD.bill_dtl_acc_item, OLD.notes, OLD.isvalid);
            END');

        DB::unprepared('CREATE TRIGGER trg_bill_details_delete AFTER DELETE ON `bill_details` FOR EACH ROW
            BEGIN
                INSERT INTO `id_bill_detail`
                    (`opr`, `id_bill_detail`, `bill_id`, `bill_dtl_order`, `service_id`, `client_role_id`, `bill_dtl_title`, `bill_dtl_unit_price`, `bill_dtl_quantity`, `bill_dtl_unit`, `bill_dtl_tax_type`, `bill_dtl_tax`, `bill_dtl_amount`, `bill_dtl_acc_item`, `notes`, `isvalid`) 
                VALUES 
                    ("D", OLD.id, OLD.bill_id, OLD.bill_dtl_order, OLD.service_id, OLD.client_role_id, OLD.bill_dtl_title, OLD.bill_dtl_unit_price, OLD.bill_dtl_quantity, OLD.bill_dtl_unit, OLD.bill_dtl_tax_type, OLD.bill_dtl_tax, OLD.bill_dtl_amount, OLD.bill_dtl_acc_item, OLD.notes, OLD.isvalid);
            END');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_bill_details_update');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_bill_details_delete');

        Schema::dropIfExists('xlog_bill_details');
        Schema::dropIfExists('bill_details');
    }
};
