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
use Yii;
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
        $file = UploadedFile::getInstanceByName($instance);
        return self::read($file->tempName);
    }

    /**
     * @param $file
     * @return array|bool
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
            return false;
        }
    }

    public static function write($fileName, $data)
    {

    }
}