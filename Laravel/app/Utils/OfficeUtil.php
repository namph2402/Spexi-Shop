<?php

namespace App\Utils;

use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class OfficeUtil
{

    /**
     * @param string $filePath
     * @return string
     * @throws \Exception
     */
    public static function convertDoc2Html(string $filePath)
    {
        exec(sprintf('%s %s 2>&1', env('MAMMOTH_CMD', 'mammoth'), $filePath), $output, $status);
        if ($status == 1) {
            Log::error($output);
            throw new \Exception('Convert Failed');
        }
        return $output;
    }

    /**
     * @param string $filename
     * @param string|int|array|null $selectedSheets
     * @param int $fromRow
     * @param string $fromColumn
     * @param int $toRow
     * @param string $toColumn
     * @return array
     */
    public static function readXLSX(string $filename, $selectedSheets = null, int $fromRow = 1, string $fromColumn = 'A', int $toRow = -1, string $toColumn = '')
    {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($filename);
        $sheets = [];
        $switcher = gettype($selectedSheets);
        if ($switcher == 'NULL') {
            $sheets = $spreadsheet->getAllSheets();
        }
        if ($switcher == 'string') {
            try {
                $selectedSheet = $spreadsheet->getSheetByName($selectedSheets);
                if (empty($selectedSheet)) {
                    throw new \Exception('Sheet Not Found');
                }
                array_push($sheets, $selectedSheet);
            } catch (\Exception $e) {
                return [];
            }
        }
        if ($switcher == 'integer') {
            try {
                $selectedSheet = $spreadsheet->getSheet($selectedSheets);
                if (empty($selectedSheet)) {
                    throw new \Exception('Sheet Not Found');
                }
                array_push($sheets, $selectedSheet);
            } catch (\Exception $e) {
                return [];
            }
        }
        if ($switcher == 'NULL') {
            $sheets = $spreadsheet->getAllSheets();
        }
        if ($switcher == 'array') {
            foreach ($selectedSheets as $selectedSheetElement) {
                if (is_string($selectedSheetElement)) {
                    $func = 'getSheet';
                }
                if (is_int($selectedSheetElement)) {
                    $func = 'getSheetByName';
                }
                if (isset($func)) {
                    try {
                        $selectedSheet = $spreadsheet->{$func}($selectedSheetElement);
                        if (empty($selectedSheet)) {
                            throw new \Exception('Sheet not found');
                        }
                        array_push($sheets, $selectedSheet);
                    } catch (\Exception $e) {
                        continue;
                    }
                }
            }
        }
        $isEmptyRow = function ($row) {
            foreach ($row as $cell) {
                if (null !== $cell) return false;
            }
            return true;
        };
        $data = [];
        foreach ($sheets as $sheet) {
            $highestRow = $toRow;
            if ($highestRow < 0) {
                $highestRow = $sheet->getHighestDataRow();
            }

            $highestColumn = $toColumn;
            if ($highestColumn == '') {
                $highestColumn = $sheet->getHighestDataColumn();
            }

            for ($row = $fromRow; $row <= $highestRow; $row++) {
                $rowData = $sheet->rangeToArray(sprintf('%s%d:%s%d', $fromColumn, $row, $highestColumn, $row), null, true, false);
                if ($isEmptyRow(reset($rowData))) {
                    continue;
                }
                array_push($data, reset($rowData));
            }
        }
        return $data;
    }


    /**
     * @param array $data
     * @param string|null $borderRange
     * @return Xlsx
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public static function writeXLSX(array $data, string $borderRange = null)
    {
        $spreadsheet = new Spreadsheet();
        $sheetIndex = 0;
        foreach ($data as $sheetName => $rows) {
            $sheet = new Worksheet($spreadsheet, $sheetName);
            $i = 1;
            foreach ($rows as $row) {
                $j = 1;
                foreach ($row as $cell) {
                    $sheet->setCellValueByColumnAndRow($j, $i, $cell);
                    $j++;
                }
                $i++;
            }

            $sheet = $spreadsheet->addSheet($sheet, $sheetIndex);
            if ($borderRange) {
                $sheet->getStyle($borderRange)->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            }
            $sheetIndex++;
        }
        return new Xlsx($spreadsheet);
    }


}
