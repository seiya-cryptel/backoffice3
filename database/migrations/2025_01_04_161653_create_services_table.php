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
        Schema::create('services', function (Blueprint $table) {
            $table->id();

            $table->string('svc_cd', 32)->comment('サービス コード');
            $table->string('svc_name', 255)->comment('サービス名');
            $table->unsignedBigInteger('id_person_role')->comment('顧客担当者役割');
            $table->string('svc_title_cover', 255)->nullable()->comment('送付状出力名称');
            $table->string('svc_title_bill', 255)->nullable()->comment('請求書出力名称');
            $table->decimal('svc_unit_price', 12, 4)->default(0)->comment('標準単価');
            $table->decimal('svc_quantity', 12, 4)->default(1)->comment('標準数量');
            $table->string('svc_unit', 255)->nullable()->comment('単位');
            $table->tinyInteger('svc_tax_type')->default(1)->comment('税率区分');
            $table->string('svc_acc_item', 32)->nullable()->comment('科目');            
            $table->string('notes', 255)->nullable()->comment('備考');
            $table->tinyInteger('isvalid')->default(1)->comment('有効');

            $table->dateTime('created_at')->useCurrent()->nullable();
            $table->dateTime('updated_at')->useCurrent()->nullable();

            $table->unique(['svc_cd']);
        });

        Schema::create('xlog_services', function (Blueprint $table) {
            $table->id();

            $table->dateTime('logged_at')->useCurrent();
            $table->string('opr', 1);
            $table->bigInteger('id_service');

            $table->string('svc_cd', 32)->comment('サービス コード');
            $table->string('svc_name', 255)->comment('サービス名');
            $table->unsignedBigInteger('id_person_role')->comment('顧客担当者役割');
            $table->string('svc_title_cover', 255)->nullable()->comment('送付状出力名称');
            $table->string('svc_title_bill', 255)->nullable()->comment('請求書出力名称');
            $table->decimal('svc_unit_price', 12, 4)->default(0)->comment('標準単価');
            $table->decimal('svc_quantity', 12, 4)->default(1)->comment('標準数量');
            $table->string('svc_unit', 255)->nullable()->comment('単位');
            $table->tinyInteger('svc_tax_type')->default(1)->comment('税率区分');
            $table->string('svc_acc_item', 32)->nullable()->comment('科目');            
            $table->string('notes', 255)->nullable()->comment('備考');
            $table->tinyInteger('isvalid')->default(1)->comment('有効');
        });

        DB::unprepared('CREATE TRIGGER trg_services_update AFTER UPDATE ON `services` FOR EACH ROW
            BEGIN
                INSERT INTO `xlog_services`
                    (`opr`, `id_service`, `svc_cd`,`svc_name`,`id_person_role`,`svc_title_cover`,`svc_title_bill`,`svc_unit_price`,`svc_quantity`,`svc_unit`,`svc_tax_type`,`svc_acc_item`,`notes`,`isvalid`) 
                VALUES 
                    ("U", OLD.id, OLD.svc_cd,OLD.svc_name,OLD.id_person_role,OLD.svc_title_cover,OLD.svc_title_bill,OLD.svc_unit_price,OLD.svc_quantity,OLD.svc_unit,OLD.svc_tax_type,OLD.svc_acc_item,OLD.notes,OLD.isvalid);
            END');

        DB::unprepared('CREATE TRIGGER trg_services_delete AFTER DELETE ON `services` FOR EACH ROW
            BEGIN
                INSERT INTO `xlog_services`
                    (`opr`, `id_service`, `svc_cd`,`svc_name`,`id_person_role`,`svc_title_cover`,`svc_title_bill`,`svc_unit_price`,`svc_quantity`,`svc_unit`,`svc_tax_type`,`svc_acc_item`,`notes`,`isvalid`) 
                VALUES 
                    ("D", OLD.id, OLD.svc_cd,OLD.svc_name,OLD.id_person_role,OLD.svc_title_cover,OLD.svc_title_bill,OLD.svc_unit_price,OLD.svc_quantity,OLD.svc_unit,OLD.svc_tax_type,OLD.svc_acc_item,OLD.notes,OLD.isvalid);
            END');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_services_update');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_services_delete');

        Schema::dropIfExists('services');
        Schema::dropIfExists('slog_services');
    }
};
