<?php

declare(strict_types=1);

namespace App\RequestValidators;

use App\Contracts\RequestValidatorInterface;
use App\Exception\ValidationException;
use finfo;
use Psr\Http\Message\UploadedFileInterface;

class UploadReceiptRequestValidator implements RequestValidatorInterface
{
    public function validate(array $data): array
    {
        /** @var UploadedFileInterface $uploadedFile */
        $uploadedFile = $data['receipt'] ?? null;

        if (!$uploadedFile) {
            throw new ValidationException(['receipt' => ['Please select a receipt file']]);
        }

        if ($uploadedFile->getError() !== UPLOAD_ERR_OK) {
            throw new ValidationException(['receipt' => ['Failed to upload the receipt file']]);
        }

        $maxFileSize = 5 * 1025 * 1024;

        if ($uploadedFile->getSize() > $maxFileSize) {
            throw new ValidationException(['receipt' => ['Maximum allowed size is 5 MB']]);
        }

        $filename = $uploadedFile->getClientFilename();

        if (!preg_match('/^[a-zA-Z0-9\s._-]+$/', $filename)) {
            throw new ValidationException(['receipt' => ['Invalid filename']]);
        }

        $allowedMimeTypes = ['image/jpeg', 'image/png', 'application/pdf'];
        $allowedExtensions = ['pdf', 'png', 'jpeg', 'jpg'];
        $tmpFilePath = $uploadedFile->getStream()->getMetadata('uri');

        if (!in_array($uploadedFile->getClientMediaType(), $allowedMimeTypes)) {
            throw new ValidationException(['receipt' => ['Receipt has to be either an image or a pdf document']]);
        }

        if (!in_array($this->getExtension($tmpFilePath), $allowedExtensions)) {
            throw new ValidationException(['receipt' => ['Receipt has to be either pdf, png, jpg, or jpeg']]);
        }

        if (!in_array($this->getMimeType($tmpFilePath), $allowedMimeTypes)) {
            throw new ValidationException(['receipt' => ['Invalid file type']]);
        }

        return $data;
    }

    private function getExtension(string $path): string
    {
        return (new finfo(FILEINFO_EXTENSION))->file($path) ?: '';
    }

    private function getMimeType(string $path): string
    {
        return (new finfo(FILEINFO_MIME_TYPE))->file($path) ?: '';
    }
}
