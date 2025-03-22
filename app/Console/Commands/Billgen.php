<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Traits\Utilities;

use App\Models\contract;
use App\Models\contractDetail;
use App\Models\bill;
use App\Models\billDetail;
use App\Models\taxRate;

/**
 * 契約から顧客の請求書を生成する
 * php artisan app:billgen {target_date}
 * target_date: 請求書を生成する対象の日付 (YYYY-MM-DD)　省略時は当日
 */
class Billgen extends Command
{
    use Utilities;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:billgen {target_date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate bills for the customers from contracts';

    /**
     * target_date
     */
    public $target_date;

    /**
     * 請求対象年月を計算する
     * @param monthOfs 請求対象月のオフセット
     * @param billDate 請求日
     * @return string 請求対象年月文字列
     */
    protected function calcTargetYM($monthOfs, $billDate)
    {
        $dtBill1st = strtotime(date('Y/m/1', strtotime($billDate)));    // 請求日の月初
        if($monthOfs > 0)
        {
            $dtTarget = strtotime('+' . $monthOfs . ' month', $dtBill1st);
        }
        elseif($monthOfs < 0)
        {
            $dtTarget = strtotime($monthOfs . ' month', $dtBill1st);
        }
        else
        {
            $dtTarget = $dtBill1st;
        }
        return Date('Y年 m月', $dtTarget);
    }

    /**
     * 請求明細を生成する
     * 
     * @param contract $contract
     * @param bill $bill
     */
    protected function createBillDetails($contract, $bill)
    {
        // 税率を取得
        $taxRates = taxRate::getRate($bill->bill_date);
        // 請求対象年月を計算する
        $targetYM = $this->calcTargetYM($contract->contract_month_ofs, $bill->bill_date);
        // 契約明細を取得
        $contractDetails = contractDetail::getBillgenContractDetails($contract->id);

        foreach ($contractDetails as $contractDetail) {
            // 適用税率
            $taxRate = $taxRates[$contractDetail->cont_dtl_tax_type];
            // 税別金額を計算
            $amount = round(
                $this->UtlStr2Number($contractDetail->cont_dtl_unit_price) 
                * $this->UtlStr2Number($contractDetail->cont_dtl_quantity)
            );
            // 税額を計算
            $tax = round($amount * $taxRate);

            // 請求明細を生成
            $billDetail = billDetail::create([
                'bill_id' => $bill->id,
                'bill_dtl_order' => $contractDetail->cont_dtl_order,
                'service_id' => $contractDetail->service_id,
                'person_role_id' => $contractDetail->person_role_id,
                'bill_dtl_title' => str_replace('%yyyymm%', $targetYM . '分', $contractDetail->cont_dtl_title),
                'bill_dtl_unit_price' => $contractDetail->cont_dtl_unit_price,
                'bill_dtl_quantity' => $contractDetail->cont_dtl_quantity,
                'bill_dtl_unit' => $contractDetail->cont_dtl_unit,
                'bill_dtl_tax_type' => $contractDetail->cont_dtl_tax_type,
                'bill_dtl_tax' => $tax,
                'bill_dtl_amount' => $amount + $tax,
                'bill_dt_acc_item' => $contractDetail->cont_dtl_acc_item,
            ]);
        }
    }

    /**
     * 契約から請求を作成する
     */
    protected function createBill()
    {
        // 対象の契約を取得
        $contracts = contract::getBillgenContracts($this->target_date);
        foreach ($contracts as $contract) {
            // 請求書を生成
            $bill = bill::create([
                'bill_no' => bill::getNextBillNo(date('Ym', strtotime($contract->contract_next_date))),
                'client_id' => $contract->client_id,
                'person_role_id' => 1,
                'bill_title' => $contract->contract_title,
                'bill_date' => $contract->contract_next_date,
            ]);
            // 請求明細を生成
            $this->createBillDetails($contract, $bill);
            // 次回請求日を更新
            $contract->updateNextBillDate();
        }
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->target_date = $this->argument('target_date') ? $this->argument('target_date') : date('Y-m-d');
        // $this->info('Billgen command is working ' . $this->target_date);
        // Log::info('Billgen command is working ' . $this->target_date);

        // トランザクションあり
        \DB::beginTransaction();
        try {
            $this->createBill();
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            $this->error('Failed to generate bills: ' . $e->getMessage());
            Log::error('Failed to generate bills: ' . $e->getMessage());
        }
    }
}
