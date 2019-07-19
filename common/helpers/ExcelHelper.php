<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-22
 * Time: 14:02
 */

namespace common\helpers;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception as ReaderException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Yii;
use yii\helpers\FileHelper;
use yii\helpers\Url;
use yii\web\UploadedFile;

class ExcelHelper
{

    public static $fmime = [
        'CSV' => 'text/csv',
        'HTML' => 'text/html',
        'PDF' => 'application/pdf',
        'OpenDocument' => 'application/vnd.oasis.opendocument.spreadsheet',
        'Excel5' => 'application/vnd.ms-excel',
        'Excel2007' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ];

    public static function readFromFile($instance)
    {
        if (($file = UploadedFile::getInstanceByName($instance)) === null) {
            return [];
        }
        return self::read($file->tempName);
    }

    /**
     * @param $file
     * @return array
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public static function read($file)
    {
        set_time_limit(-1);
        try {
            $reader = IOFactory::createReaderForFile($file);
            $reader->setLoadAllSheets();
            $spreadsheet = $reader->load($file);
            $sheets = [];
            foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
                $rows = $worksheet->toArray();
                $rowTitle = array_shift($rows);
                if (count($rows) === 0) {
                    continue;
                }
                $results = [];
                foreach ($rows as $row) {
                    $results[] = array_combine($rowTitle, $row);
                }
                $sheets[$worksheet->getTitle()] = $results;
            }
            return $sheets;
        } catch (ReaderException $exception) {
            Yii::error($exception);
            return [];
        }
    }

    public static function write($data, $fileName = null)
    {
        FileHelper::createDirectory(Yii::getAlias('@web/excel'), 0777);

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator('Weshop System')
            ->setLastModifiedBy('Weshop System')
            ->setTitle(Yii::$app->formatter->asDatetime('now'));

        if (empty($data)) {
            return;
        }

        $columns = array_keys($data[0]);

        self::setCellValue($spreadsheet, 1, $columns);
        $row = 2;
        foreach ($data as $record) {
            self::setCellValue($spreadsheet, $row, $record);
            $row++;
        }
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        if ($fileName === null) {
            $fileName = 'export_' . gmdate('ymd_His');
        }
        $fileName = "excel/$fileName.xlsx";
        $writer->save($fileName);
        return Url::to('/' . $fileName, true);
    }

    /**
     * @param $spreadsheet Spreadsheet
     * @param $row
     * @param $data
     * @param null $numCol
     * @param string $startCol
     * @return Spreadsheet
     */
    public static function setCellValue(&$spreadsheet, $row, $data, $numCol = null, $startCol = 'A')
    {

        $sheet = $spreadsheet->setActiveSheetIndex(0);
        if ($numCol === null) {
            $numCol = count($data);
        }
        $col = $startCol;
        if ($col === null) {
            $col = 'A';
        }
        $keys = array_keys($data);
        Yii::info($keys, $numCol);
        for ($i = 0; $i < $numCol; $i++) {
            $currentKey = $keys[$i];
            $value = is_array($data[$currentKey]) ? json_encode($data[$currentKey]) : $data[$currentKey];
            $sheet->setCellValue($col . $row, $value);
            $sheet->getColumnDimension($col)->setAutoSize(true);

            $col++;
        }
    }
}