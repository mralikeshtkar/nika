<?php

namespace App\Services\V1\Media\Stream;

use Symfony\Component\HttpFoundation\StreamedResponse;

class MediaStream
{
    /**
     * @param $filePath
     * @param $fileName
     * @return StreamedResponse
     */
    public static function stream($filePath, $fileName): StreamedResponse
    {
        return new StreamedResponse(
            function () use ($filePath, $fileName) {
                if ($file = fopen($filePath, 'rb')) {
                    while (!feof($file) and (connection_status() == 0)) {
                        print(fread($file, 1024 * 8));
                        flush();
                    }
                    fclose($file);
                }
            },
            200,
            [
                'Content-Type' => 'application/octet-stream',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            ]);
    }
}
