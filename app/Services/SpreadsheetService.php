<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;

use App\Traits\Utilities;

use App\Models\Estimate;
use App\Models\EstimateDetail;
use App\Models\Bill;
use App\Models\BillDetail;
use App\Models\AppSetting;

/**
 * Excelスプレッドシートの操作
 */
class SpreadsheetService
{
    use Utilities;

    /**
     * テンプレートシート中のタグを置き換える
     * @param $sheet
     * @param $parameters
     * @return void
     */
    protected function replaceTags($sheet, $parameters)
    {
        $rows = range(1, $sheet->getHighestRow());
        $cols = range('A', $sheet->getHighestColumn());
        foreach($rows as $row) {
            foreach($cols as $col) {
                $cell = $sheet->getCell($col . $row);
                $value = $cell->getValue();
                if(substr($value, 0, 2) == '%%') {
                    if(isset($parameters[$value])) {
                        $cell->setValue($parameters[$value]);
                    }
                }
            }
        }
    }

    /**
     * 明細開始セルを検索する
     * @param $sheet
     * @return string
     */
    protected function searchStartCell($sheet)
    {
        $rows = range(1, $sheet->getHighestRow());
        $cols = range('A', $sheet->getHighestColumn());
        foreach($rows as $row) {
            foreach($cols as $col) {
                $cell = $sheet->getCell($col . $row);
                $value = $cell->getValue();
                if($value == '##Details##') {
                    return $col . $row;
                }
            }
        }
        return '';
    }

    /**
     * 見積明細行を挿入する
     * @param $sheet
     * @param $startRow 明細行の開始行
     * @param $estimateDetails 見積明細
     */
    protected function insertEstimateDetailRow($sheet, $startRow, $estimateDetails)
    {
        // 明細行開始マークを削除
        $sheet->getcell('B' . $startRow)->setValue('');

        $no = 1;
        foreach($estimateDetails as $estimateDetail) {
            $nRow = $startRow + $no - 1;
            $sheet->insertNewRowBefore($nRow + 1); // 最初に行を挿入
            $sheet->mergeCells('B' . ($nRow + 1) . ':D' . ($nRow + 1)); // セルを結合

            // 単価 * 数量を計算
            $amount = round(
                $this->UtlStr2Number($estimateDetail->estm_dtl_unit_price, 0)
                * $this->UtlStr2Number($estimateDetail->estm_dtl_quantity, 0),
                0);
            $sheet->getcell('B' . $nRow)->setValue($estimateDetail->estm_dtl_title);
            $sheet->getcell('E' . $nRow)->setValue($estimateDetail->estm_dtl_unit_price);
            $sheet->getcell('F' . $nRow)->setValue($estimateDetail->estm_dtl_quantity);
            $sheet->getcell('G' . $nRow)->setValue($estimateDetail->estm_dtl_unit);
            $sheet->getcell('H' . $nRow)->setValue($amount);
            $sheet->getcell('I' . $nRow)->setValue($this->UtlStr2Number($estimateDetail->estm_dtl_tax));
            $sheet->getcell('J' . $nRow)->setValue($this->UtlStr2Number($estimateDetail->estm_dtl_amount));
            $sheet->getRowDimension($nRow)->setRowHeight(ceil(mb_strlen($estimateDetail->estm_dtl_title) / 16) * 20);
            $no++;
        }
    }

    /**
     * 見積書 Excel ファイルを生成する
     * @param $estimate
     * @param $templateFileName テンプレートファイル名
     * @return Excel ファイルのパス
     */
    protected function createEstimateExcel($estimate, $templateFileName)
    {
        $client = $estimate->client;

        // テンプレートおよびExcelファイルのパスを設定
        $templateFile = storage_path('template') . '/' . $templateFileName;
        $excelFile = storage_path('tmp') . '/' . $estimate->estimate_no . '_' . date('YmdHis') . '_' . Session::getId() . '.xlsx';

        // 見積書情報を取得
        $estimateItems = [
            '%%EstimateDate%%' => date('Y年m月d日', strtotime($estimate->estimate_date)),
            '%%EstimateNo%%' => $estimate->estimate_no,
            '%%Title%%' => $estimate->estimate_title,
            '%%ClientName%%' => $client->cl_full_name,
            '%%Amount%%' => number_format($estimate->getAmount()),
            '%%MyZip%%' => AppSetting::getSetting('my_zip'),
            '%%MyAddr1%%' => AppSetting::getSetting('my_addr1'),
            '%%MyAddr2%%' => AppSetting::getSetting('my_addr2'),
            '%%MyName%%' => AppSetting::getSetting('my_name'),
            '%%MyCEO%%' => AppSetting::getSetting('my_ceo'),
            '%%MyContact%%' => AppSetting::getSetting('my_tel'),
            '%%MyMail%%' => AppSetting::getSetting('my_mail'),
        ];

        // テンプレートファイルを読み込む
        $spreadSheet = (new XlsxReader)->load($templateFile);
        // 最初のシートを取得
        $sheet = $spreadSheet->getSheet(0);
        // 見積書項目を置換する
        $this->replaceTags($sheet, $estimateItems);
        // 明細行の左上セルを検索
        $sStartCell = $this->searchStartCell($sheet);
        // 明細行を挿入
        $nStartRow = substr($sStartCell, 1);
        $this->insertEstimateDetailRow($sheet, $nStartRow, $estimate->estimateDetails);
        // Excel 出力 デバック用
        $writer = new XlsxWriter($spreadSheet);
        $writer->save($excelFile);
        return $excelFile;
    }

    /**
     * Excel ファイルから PDF ファイルを生成する
     * @param $excelFile Excel ファイルのパス
     * @return PDF ファイルのパス
     */
    protected function createPdfFromExcel($excelFile)
    {
        $cmd = 'export HOME=/tmp; /usr/bin/soffice --headless --convert-to pdf --outdir ' . storage_path('tmp') . ' ' . $excelFile;
        $output = [];
        $status = 0;
        $ret = exec($cmd, $output, $status);
        $pdfFile = storage_path('tmp') . '/' . basename($excelFile, '.xlsx') . '.pdf';
        return $pdfFile;
    }

    /**
     * 見積書 Excel ファイルをダウンロード
     * @param $estimate_id
     * @return download response
     */
    public function exportEstimateExcel($estimate_id)
    {
        $estimate = Estimate::with('client')
            ->with('estimateDetails')
            ->find($estimate_id);
        if(! $estimate) {
            throw new \Exception('見積(' . $estimate_id . ')が見つかりません');
        }
        $excelFile = $this->createEstimateExcel($estimate, config('app.template.estimate'));
        $fileName = 'estm' . $estimate->estimate_no . '.xlsx';
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
         ];
        return response()->download($excelFile, $fileName, $headers)->deleteFileAfterSend(true);
    }

    /**
     * 見積書 PDF ファイルをダウンロード
     * @param $estimate_id
     * @return download response
     */
    public function exportEstimatePdf($estimate_id)
    {
        $estimate = Estimate::with('client')
            ->with('estimateDetails')
            ->find($estimate_id);
        if(! $estimate) {
            throw new \Exception('見積(' . $estimate_id . ')が見つかりません');
        }
        $excelFile = $this->createEstimateExcel($estimate, config('app.template.estimate_pdf'));
        $pdfFile = $this->createPdfFromExcel($excelFile);
        unlink($excelFile);
        $fileName = 'estm' . $estimate->estimate_no . '.pdf';
        $headers = [
            'Content-Type' => 'application/pdf'
         ];
        return response()->download($pdfFile, $fileName, $headers)->deleteFileAfterSend(true);
    }

    /**
     * 請求明細行を挿入する
     * @param $sheet
     * @param $startRow 明細行の開始行
     * @param $billDetails 請求明細
     */
    protected function insertBillDetailRow($sheet, $startRow, $billDetails)
    {
        // 明細行開始マークを削除
        $sheet->getcell('B' . $startRow)->setValue('');

        $no = 1;
        foreach($billDetails as $billDetail) {
            $nRow = $startRow + $no - 1;
            $sheet->insertNewRowBefore($nRow + 1); // 最初に行を挿入
            $sheet->mergeCells('B' . ($nRow + 1) . ':D' . ($nRow + 1)); // セルを結合

            // 単価 * 数量を計算
            $amount = round(
                $this->UtlStr2Number($billDetail->bill_dtl_unit_price, 0)
                * $this->UtlStr2Number($billDetail->bill_dtl_quantity, 0),
                0);
            $sheet->getcell('B' . $nRow)->setValue($billDetail->bill_dtl_title);
            $sheet->getcell('E' . $nRow)->setValue($billDetail->bill_dtl_unit_price);
            $sheet->getcell('F' . $nRow)->setValue($billDetail->bill_dtl_quantity);
            $sheet->getcell('G' . $nRow)->setValue($billDetail->bill_dtl_unit);
            $sheet->getcell('H' . $nRow)->setValue($amount);
            $sheet->getcell('I' . $nRow)->setValue($this->UtlStr2Number($billDetail->bill_dtl_tax));
            $sheet->getcell('J' . $nRow)->setValue($this->UtlStr2Number($billDetail->bill_dtl_amount));
            $sheet->getRowDimension($nRow)->setRowHeight(ceil(mb_strlen($billDetail->bill_dtl_title) / 16) * 20);
            $no++;
        }
    }

    /**
     * 請求書 Excel ファイルを生成する
     * @param $bill
     * @param $templateFileName テンプレートファイル名
     * @return Excel ファイルのパス
     */
    protected function createBillExcel($bill, $templateFileName)
    {
        $client = $bill->client;

        // テンプレートおよびExcelファイルのパスを設定
        $templateFile = storage_path('template') . '/' . $templateFileName;
        $excelFile = storage_path('tmp') . '/' . $bill->bill_no . '_' . date('YmdHis') . '_' . Session::getId() . '.xlsx';

        // 見積書情報を取得
        $billItems = [
            '%%BillDate%%' => date('Y年m月d日', strtotime($bill->bill_date)),
            '%%BillNo%%' => $bill->bill_no,
            '%%Title%%' => $bill->bill_title,
            '%%ClientName%%' => $client->cl_full_name,
            '%%Amount%%' => number_format($bill->getAmount()),
            '%%MyZip%%' => AppSetting::getSetting('my_zip'),
            '%%MyAddr1%%' => AppSetting::getSetting('my_addr1'),
            '%%MyAddr2%%' => AppSetting::getSetting('my_addr2'),
            '%%MyName%%' => AppSetting::getSetting('my_name'),
            '%%MyCEO%%' => AppSetting::getSetting('my_ceo'),
            '%%MyContact%%' => AppSetting::getSetting('my_tel'),
            '%%MyMail%%' => AppSetting::getSetting('my_mail'),

            '%%MyBank%%' => AppSetting::getSetting('my_bank_name') . ' ' . AppSetting::getSetting('my_bank_branch_name'),
            '%%MyAccountType%%' => AppSetting::getSetting('my_bank_account_type'),
            '%%MyAccountNo%%' => '口座番号 ' . AppSetting::getSetting('my_bank_account_no'),
            '%%MyAccountName%%' => AppSetting::getSetting('my_bank_account_name'),
            '%%MyInvoice%%' => AppSetting::getSetting('my_invoice_reg_no'),
        ];

        // テンプレートファイルを読み込む
        $spreadSheet = (new XlsxReader)->load($templateFile);
        // 最初のシートを取得
        $sheet = $spreadSheet->getSheet(0);
        // 見積書項目を置換する
        $this->replaceTags($sheet, $billItems);
        // 明細行の左上セルを検索
        $sStartCell = $this->searchStartCell($sheet);
        // 明細行を挿入
        $nStartRow = substr($sStartCell, 1);
        $this->insertBillDetailRow($sheet, $nStartRow, $bill->billDetails->sortBy('bill_dtl_order'));
        // Excel 出力 デバック用
        $writer = new XlsxWriter($spreadSheet);
        $writer->save($excelFile);
        return $excelFile;
    }

    /**
     * 請求書 Excel ファイルをダウンロード
     * @param $bill_id
     * @return download response
     */
    public function exportBillExcel($bill_id)
    {
        $bill = Bill::with('client')
            ->with('billDetails')
            ->find($bill_id);
        if(! $bill) {
            throw new \Exception('請求(' . $bill_id . ')が見つかりません');
        }
        $excelFile = $this->createBillExcel($bill, config('app.template.bill'));
        $fileName = 'bill' . $bill->bill_no . '.xlsx';
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
         ];
        return response()->download($excelFile, $fileName, $headers)->deleteFileAfterSend(true);
    }

    /**
     * 請求書 PDF ファイルをダウンロード
     * @param $bill_id
     * @return download response
     */
    public function exportBillPdf($bill_id)
    {
        $bill = Bill::with('client')
            ->with('billDetails')
            ->find($bill_id);
        if(! $bill) {
            throw new \Exception('請求(' . $bill_id . ')が見つかりません');
        }
        $excelFile = $this->createBillExcel($bill, config('app.template.bill_pdf'));
        $pdfFile = $this->createPdfFromExcel($excelFile);
        unlink($excelFile);
        $fileName = 'bill' . $bill->bill_no . '.pdf';
        $headers = [
            'Content-Type' => 'application/pdf'
         ];
        return response()->download($pdfFile, $fileName, $headers)->deleteFileAfterSend(true);
    }
}