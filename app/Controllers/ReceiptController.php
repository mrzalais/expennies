<?php

declare(strict_types = 1);

namespace App\Controllers;

use League\Flysystem\Filesystem;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\UploadedFileInterface;

class ReceiptController
{
    public function __construct(private readonly Filesystem $filesystem)
    {
    }

    public function store(Request $request, Response $response, array $args): Response
    {
        /** @var UploadedFileInterface $file */
        $file = $request->getUploadedFiles()['receipt'];

        $fileName = $file->getClientFilename();

        $this->filesystem->write('receipts/' . $fileName, $file->getStream()->getContents());

        return $response;
    }
}
