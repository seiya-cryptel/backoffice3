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
        Schema::create('estimates', function (Blueprint $table) {
            $table->id();

            $table->string('estimate_no', 32)->comment('見積番号');
            $table->unsignedBigInteger('client_id')->comment('顧客ID');
            $table->string('estimate_title', 255)->comment('見積件名');
            $table->date('estimate_date')->nullable()->comment('見積日');
            $table->string('deliverly_date', 255)->default('別途ご相談')->comment('納品日');
            $table->string('deliverly_place', 255)->default('お客様ご指定場所')->comment('納品場所');
            $table->string('payment_notice', 255)->default('お客様支払い条件による')->comment('支払条件');
            $table->string('valid_until', 255)->default('見積日から1ヶ月')->comment('見積有効期限');
            $table->tinyInteger('show_ceo')->default(0)->comment('代表者表示フラグ');
            $table->string('notes', 255)->nullable()->comment('備考');
            $table->tinyInteger('isvalid')->default(1)->comment('有効');

            $table->dateTime('created_at')->useCurrent()->nullable();
            $table->dateTime('updated_at')->useCurrent()->nullable();

            $table->unique(['estimate_no']);
        });

        Schema::create('xlog_estimates', function (Blueprint $table) {
            $table->id();

            $table->dateTime('logged_at')->useCurrent();
            $table->string('opr', 1);
            $table->bigInteger('estimate_id');

            $table->string('estimate_no', 32)->comment('見積番号');
            $table->unsignedBigInteger('client_id')->comment('顧客ID');
            $table->string('estimate_title', 255)->comment('見積件名');
            $table->date('estimate_date')->nullable()->comment('見積日');
            $table->string('deliverly_date', 255)->default('別途ご相談')->comment('納品日');
            $table->string('deliverly_place', 255)->default('お客様ご指定場所')->comment('納品場所');
            $table->string('payment_notice', 255)->default('お客様支払い条件による')->comment('支払条件');
            $table->string('valid_until', 255)->default('見積日から1ヶ月')->comment('見積有効期限');
            $table->tinyInteger('show_ceo')->default(0)->comment('代表者表示フラグ');
            $table->string('notes', 255)->nullable()->comment('備考');
            $table->tinyInteger('isvalid')->default(1)->comment('有効');
        });

        DB::unprepared('CREATE TRIGGER trg_estimates_update AFTER UPDATE ON `estimates` FOR EACH ROW
            BEGIN
                INSERT INTO `xlog_estimates`
                    (`opr`, `id_estimate`, `client_id`, `estimate_no`, `estimate_title`, `estimate_date`, `deliverly_date`, `deliverly_place`, `payment_notice`, `valid_until`, `show_ceo`, `notes`, `isvalid`) 
                VALUES 
                    ("U", OLD.id, OLD.client_id, OLD.estimate_no, OLD.estimate_title, OLD.estimate_date, OLD.deliverly_date, OLD.deliverly_place, OLD.payment_notice, OLD.valid_until, OLD.show_ceo, OLD.notes, OLD.isvalid);
            END');

        DB::unprepared('CREATE TRIGGER trg_estimates_delete AFTER DELETE ON `estimates` FOR EACH ROW
            BEGIN
                INSERT INTO `xlog_estimates`
                    (`opr`, `id_estimate`, `client_id`, `estimate_no`, `estimate_title`, `estimate_date`, `deliverly_date`, `deliverly_place`, `payment_notice`, `valid_until`, `show_ceo`, `notes`, `isvalid`) 
                VALUES 
                    ("D", OLD.id, OLD.client_id, OLD.estimate_no, OLD.estimate_title, OLD.estimate_date, OLD.deliverly_date, OLD.deliverly_place, OLD.payment_notice, OLD.valid_until, OLD.show_ceo, OLD.notes, OLD.isvalid);
            END');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_estimates_update');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_estimates_delete');

        Schema::dropIfExists('xlog_estimates');
        Schema::dropIfExists('estimates');
    }
};
