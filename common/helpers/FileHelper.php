<?php
/**
 * @author: timelesszhuang [<https://github.com/timelesszhuang>]
 */

namespace common\helpers;

class FileHelper extends \yii\helpers\BaseFileHelper{


    public static function getMimeTypeByExt($ext, $magicFile = null){
        $mimeTypes = static::loadMimeTypes($magicFile);
        $ext=mb_strtolower($ext, 'utf-8');
        if(array_key_exists($ext,$mimeTypes)){
            return $mimeTypes[$ext];
        }
        return null;
    }

}