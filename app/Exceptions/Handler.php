<?php

namespace App\Exceptions;

use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class Handler
{
    public function __invoke(Exceptions $exceptions): void
    {
        $exceptions->report(function (\Throwable $exception) {
            $this->log($exception);
        })->stop();

        $exceptions->render(function (\Throwable $exception) {
            $this->log($exception);
        });
    }

    private function log(\Throwable $exception)
    {
        $strError = PHP_EOL.'IP: '.request()->ip().PHP_EOL;
        $strError .= 'User: '.auth()->id().PHP_EOL;
        $strError .= 'URL: '.request()->fullUrl().PHP_EOL;
        $strError .= 'Class: '.get_class($exception).PHP_EOL;
        $strError .= 'Message: '.$exception->getMessage().PHP_EOL;
        $strError .= 'Code: '.$exception->getCode().PHP_EOL;
        $strError .= 'File: '.$exception->getFile().' '.$exception->getLine().PHP_EOL;
        if($exception instanceof ValidationException) {
            $strError .= 'Validations: '.json_encode($exception->validator->errors(), JSON_UNESCAPED_UNICODE).PHP_EOL;
        }
        $strError .= 'Params: '.json_encode(request()->all(), JSON_UNESCAPED_UNICODE).PHP_EOL;
        if(!empty(request()->allFiles())) {
            foreach(request()->allFiles() as $fileField) {
                /** @var UploadedFile $file */
                foreach($fileField as $file) {
                    $strError .= 'File: '.$file->getClientOriginalName().' '.
                        'MimeType '.$file->getMimeType().'|'.$file->getClientMimeType().' '.
                        'Size '.bytes_to_human($file->getSize()).' '.
                        'FileErr '.$file->getError().' '.$file->getErrorMessage()."\r\n";
                }
            }
        }

        try {
            Log::error($strError);
        } catch(\Exception) {}
    }
}
