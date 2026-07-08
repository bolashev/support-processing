<?php

namespace App\Logging;

use Monolog\Handler\RotatingFileHandler;

class CustomFilenames
{
    /**
     * Customize the given logger instance.
     *
     * @param \Illuminate\Log\Logger $logger
     * @return void
     */
    public function __invoke($logger)
    {
        foreach ($logger->getHandlers() as $handler) {
            if ($handler instanceof RotatingFileHandler) {
                $user = posix_getpwuid(posix_getuid());
                $handler->setFilenameFormat("{filename}-{$user['name']}-{date}", 'Y-m-d');
            }
        }
    }
}
