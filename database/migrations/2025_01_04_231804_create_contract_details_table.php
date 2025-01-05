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
        Schema::create('contract_details', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('contract_id')->comment('契約ID');
            $table->tinyInteger('cont_dtl_order')->default(1)->comment('表示順');
            $table->unsignedBigInteger('service_id')->nullable()->comment('サービスID');
            $table->unsignedBigInteger('client_role_id')->comment('顧客役割ID');
            $table->string('cont_dtl_title', 255)->comment('明細項目名');
            $table->decimal('cont_dtl_unit_price', 12, 4)->default(0)->comment('標準単価');
            $table->decimal('cont_dtl_quantity', 12, 4)->default(1)->comment('標準数量');
            $table->string('cont_dtl_unit', 255)->nullable()->comment('単位');
            $table->tinyInteger('cont_dtl_tax_type')->default(1)->comment('税率区分');
            $table->string('cont_dtl_acc_item', 32)->nullable()->comment('科目');
            $table->string('notes', 255)->nullable()->comment('備考');
            $table->tinyInteger('isvalid')->default(1)->comment('有効');

            $table->dateTime('created_at')->useCurrent()->nullable();
            $table->dateTime('updated_at')->useCurrent()->nullable();
        });

        Schema::create('xlog_contract_details', function (Blueprint $table) {
            $table->id();

            $table->dateTime('logged_at')->useCurrent();
            $table->string('opr', 1);
            $table->bigInteger('contract_detail_id');

            $table->unsignedBigInteger('contract_id')->comment('契約ID');
            $table->tinyInteger('cont_dtl_order')->default(1)->comment('表示順');
            $table->unsignedBigInteger('service_id')->nullable()->comment('サービスID');
            $table->unsignedBigInteger('client_role_id')->comment('顧客役割ID');
            $table->string('cont_dtl_title', 255)->comment('明細項目名');
            $table->decimal('cont_dtl_unit_price', 12, 4)->default(0)->comment('標準単価');
            $table->decimal('cont_dtl_quantity', 12, 4)->default(1)->comment('標準数量');
            $table->string('cont_dtl_unit', 255)->nullable()->comment('単位');
            $table->tinyInteger('cont_dtl_tax_type')->default(1)->comment('税率区分');
            $table->string('cont_dtl_acc_item', 32)->nullable()->comment('科目');
            $table->string('notes', 255)->nullable()->comment('備考');
            $table->tinyInteger('isvalid')->default(1)->comment('有効');
        });

        DB::unprepared('CREATE TRIGGER trg_contract_details_update AFTER UPDATE ON `contract_details` FOR EACH ROW
            BEGIN
                INSERT INTO `xlog_contract_details`
                    (`opr`, `id_contract_detail`, `contract_id`, `cont_dtl_order`, `service_id`, `client_role_id`, `cont_dtl_title`, `cont_dtl_unit_price`, `cont_dtl_quantity`, `cont_dtl_unit`, `cont_dtl_tax_type`, `cont_dtl_acc_item`, `notes`, `isvalid`) 
                VALUES 
                    ("U", OLD.id, OLD.contract_id, OLD.cont_dtl_order, OLD.service_id, OLD.client_role_id, OLD.cont_dtl_title, OLD.cont_dtl_unit_price, OLD.cont_dtl_quantity, OLD.cont_dtl_unit, OLD.cont_dtl_tax_type, OLD.cont_dtl_acc_item, OLD.notes, OLD.isvalid);
            END');

        DB::unprepared('CREATE TRIGGER trg_contract_details_delete AFTER DELETE ON `contract_details` FOR EACH ROW
            BEGIN
                INSERT INTO `id_contract_detail`
                    (`opr`, `id_contract_detail`, `contract_id`, `cont_dtl_order`, `service_id`, `client_role_id`, `cont_dtl_title`, `cont_dtl_unit_price`, `cont_dtl_quantity`, `cont_dtl_unit`, `cont_dtl_tax_type`, `cont_dtl_acc_item`, `notes`, `isvalid`) 
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

        Schema::dropIfExists('xlog_contract_details');
        Schema::dropIfExists('contract_details');
    }
};
