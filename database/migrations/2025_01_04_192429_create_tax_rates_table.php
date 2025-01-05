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
        Schema::create('tax_rates', function (Blueprint $table) {
            $table->id();

            $table->datetime('tax_date')->comment('適用日');
            $table->tinyInteger('tax_type')->comment('税率区分');
            $table->decimal('tax_rate1', 12, 4)->comment('消費税率');
            $table->decimal('tax_rate2', 12, 4)->comment('軽減税率');
            $table->string('notes', 255)->nullable()->comment('備考');
            $table->tinyInteger('isvalid')->default(1)->comment('有効');

            $table->dateTime('created_at')->useCurrent()->nullable();
            $table->dateTime('updated_at')->useCurrent()->nullable();

            $table->unique(['tax_date', 'tax_type']);
        });

        Schema::create('xlog_tax_rates', function (Blueprint $table) {
            $table->id();

            $table->dateTime('logged_at')->useCurrent();
            $table->string('opr', 1);
            $table->bigInteger('id_tax_rate');

            $table->datetime('tax_date')->comment('適用日');
            $table->tinyInteger('tax_type')->comment('税率区分');
            $table->decimal('tax_rate1', 12, 4)->comment('消費税率');
            $table->decimal('tax_rate2', 12, 4)->comment('軽減税率');
            $table->string('notes', 255)->nullable()->comment('備考');
            $table->tinyInteger('isvalid')->default(1)->comment('有効');
        });

        DB::unprepared('DROP TRIGGER IF EXISTS trg_tax_rates_update');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_tax_rates_delete');

        DB::unprepared('CREATE TRIGGER trg_tax_rates_update AFTER UPDATE ON `tax_rates` FOR EACH ROW
            BEGIN
                INSERT INTO `xlog_tax_rates`
                    (`opr`, `id_tax_rate`, `tax_date`, `tax_type`, `tax_rate1`, `tax_rate2`, `notes`, `isvalid`) 
                VALUES 
                    ("U", OLD.id, OLD.tax_date, OLD.tax_type, OLD.tax_rate1, OLD.tax_rate2, OLD.notes, OLD.isvalid);
            END');

        DB::unprepared('CREATE TRIGGER trg_tax_rates_delete AFTER DELETE ON `tax_rates` FOR EACH ROW
            BEGIN
                INSERT INTO `xlog_tax_rates`
                    (`opr`, `id_tax_rate`, `tax_date`, `tax_type`, `tax_rate1`, `tax_rate2`, `notes`, `isvalid`) 
                VALUES 
                    ("D", OLD.id, OLD.tax_date, OLD.tax_type, OLD.tax_rate1, OLD.tax_rate2, OLD.notes, OLD.isvalid);
            END');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_tax_rates_update');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_tax_rates_delete');

        Schema::dropIfExists('xlog_tax_rates');
        Schema::dropIfExists('tax_rates');
    }
};
