<?php

namespace backend\models;

use ErrorException;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class FormUploadRating extends Model
{
    const ONE_MB = 1048576;
    /**
     * @var UploadedFile
     */
    public $file;


    public function rules()
    {
        return [
            [['file'], 'file',
             'extensions'               => ['csv'],
             'checkExtensionByMimeType' => false,
             'maxSize'                  => 50 * self::ONE_MB,
             'tooBig'                   => __('The file {file} is too big. Its size cannot exceed 50 Mb.'),
            ],
        ];
    }

    public function uploadData()
    {

        $this->file = UploadedFile::getInstance($this, 'file');

        if ($this->validate()) {
            try {
                $cols   = false;
                $handle = fopen($this->file->tempName, 'r');
                $i      = 0;
                $data   = [];
                while ($row = fgetcsv($handle)) {
                    $i++;
                    if ($i == 1) {
                        $cols = array_flip($row);
                        continue;
                    }
                    $attributes = [];

                    foreach ($cols as $name => $index) {
                        $cols[trim($name)]       = $index;
                        $attributes[trim($name)] = trim(trim($row[$index], " \t\n\r\"\0\x20"));
                    }

                    $data[] = $attributes;
                }
                fclose($handle);
                flush();
                $result = [
                    'cols' => array_keys($cols),
                    'rows' => $data,
                ];
                return $result;
            } catch (ErrorException $e) {
                $this->addError('file', $e->getMessage());
            }
        }

        return false;
    }

}