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
        Schema::create('client_persons', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_client')->comment('顧客ID');
            $table->tinyInteger('psn_order')->default(0)->comment('表示順');
            $table->string('psn_name', 255)->nullable()->comment('氏名');
            $table->string('psn_email', 255)->nullable()->comment('メールアドレス');
            $table->string('psn_zip', 8)->nullable()->comment('郵便番号 123-4567');
            $table->string('psn_addr1', 255)->nullable()->comment('住所1');
            $table->string('psn_addr2', 255)->nullable()->comment('住所2');
            $table->string('psn_tel', 255)->nullable()->comment('電話番号');
            $table->string('psn_fax', 255)->nullable()->comment('FAX番号');
            $table->string('psn_branch', 255)->nullable()->comment('部署名');
            $table->string('psn_title', 255)->nullable()->comment('肩書');
            $table->string('psn_keisho', 8)->nullable()->comment('敬称');
            $table->string('notes', 255)->nullable()->comment('備考');
            $table->tinyInteger('isvalid')->default(1)->comment('有効');

            $table->dateTime('created_at')->useCurrent()->nullable();
            $table->dateTime('updated_at')->useCurrent()->nullable();
        });

        Schema::create('xlog_client_persons', function (Blueprint $table) {
            $table->id();

            $table->dateTime('logged_at')->useCurrent();
            $table->string('opr', 1);
            $table->bigInteger('id_client_persons');

            $table->unsignedBigInteger('id_client')->comment('顧客ID');
            $table->tinyInteger('psn_order')->default(0)->comment('表示順');
            $table->string('psn_name', 255)->nullable()->comment('氏名');
            $table->string('psn_email', 255)->nullable()->comment('メールアドレス');
            $table->string('psn_zip', 8)->nullable()->comment('郵便番号 123-4567');
            $table->string('psn_addr1', 255)->nullable()->comment('住所1');
            $table->string('psn_addr2', 255)->nullable()->comment('住所2');
            $table->string('psn_tel', 255)->nullable()->comment('電話番号');
            $table->string('psn_fax', 255)->nullable()->comment('FAX番号');
            $table->string('psn_branch', 255)->nullable()->comment('部署名');
            $table->string('psn_title', 255)->nullable()->comment('肩書');
            $table->string('psn_keisho', 8)->nullable()->comment('敬称');
            $table->string('notes', 255)->nullable()->comment('備考');
            $table->tinyInteger('isvalid')->default(1)->comment('有効');
        });

        DB::unprepared('CREATE TRIGGER trg_client_persons_update AFTER UPDATE ON `client_persons` FOR EACH ROW
            BEGIN
                INSERT INTO `xlog_client_persons`
                    (`opr`, `id_client_persons`, `id_client`,`psn_order`,`psn_name`,`psn_email`,`psn_zip`,`psn_addr1`,`psn_addr2`,`psn_tel`,`psn_fax`,`psn_branch`,`psn_title`,`psn_keisho`,`notes`,`isvalid`) 
                VALUES 
                    ("U", OLD.id, OLD.id_client,OLD.psn_order,OLD.psn_name,OLD.psn_email,OLD.psn_zip,OLD.psn_addr1,OLD.psn_addr2,OLD.psn_tel,OLD.psn_fax,OLD.psn_branch,OLD.psn_title,OLD.psn_keisho,OLD.notes,OLD.isvalid);
            END');

        DB::unprepared('CREATE TRIGGER trg_client_persons_delete AFTER DELETE ON `client_persons` FOR EACH ROW
            BEGIN
                INSERT INTO `xlog_client_persons`
                    (`opr`, `id_client_persons`, `id_client`,`psn_order`,`psn_name`,`psn_email`,`psn_zip`,`psn_addr1`,`psn_addr2`,`psn_tel`,`psn_fax`,`psn_branch`,`psn_title`,`psn_keisho`,`notes`,`isvalid`) 
                VALUES 
                    ("D", OLD.id, OLD.id_client,OLD.psn_order,OLD.psn_name,OLD.psn_email,OLD.psn_zip,OLD.psn_addr1,OLD.psn_addr2,OLD.psn_tel,OLD.psn_fax,OLD.psn_branch,OLD.psn_title,OLD.psn_keisho,OLD.notes,OLD.isvalid);
            END');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_client_persons_update');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_client_persons_delete');

        Schema::dropIfExists('xlog_client_persons');
        Schema::dropIfExists('client_persons');
    }
};
