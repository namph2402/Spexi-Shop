<?php

namespace App\Utils;


use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileStorageUtil
{
    /**
     * @param string $path
     * @param UploadedFile $file
     * @return bool
     */
    public static function putFile($path, $file)
    {
        $savedPath = Storage::disk('public')->put($path, $file);
        if (!$savedPath) {
            return false;
        }
        return Storage::disk('public')->url($savedPath);
    }


    /**
     * @param array|string|null $urls
     * @return bool
     */
    public static function deleteFiles($urls)
    {
        if (is_array($urls)) {
            foreach ($urls as $url) {
                if (!FileStorageUtil::deleteFiles($url)) {
                    return false;
                }
            }
            return true;
        } elseif (is_string($urls)) {
            $root = Storage::disk('public')->url('');
            if (Str::startsWith($urls, $root)) {
                $path = Str::replaceFirst($root, '', $urls);
                return Storage::disk('public')->delete($path);
            }
        }
        return false;
    }


}
