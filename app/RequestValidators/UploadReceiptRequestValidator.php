<?php

declare(strict_types=1);

namespace App\RequestValidators;

use App\Contracts\RequestValidatorInterface;
use App\Exception\ValidationException;
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

        if (!preg_match('/^[a-zA-Z0-9._-]+$/', $filename)) {
            throw new ValidationException(['receipt' => ['Invalid filename']]);
        }

        $allowedMimeTypes = ['image/jpeg', 'image/png', 'application/pdf'];

        if (!in_array($uploadedFile->getClientMediaType(), $allowedMimeTypes)) {
            throw new ValidationException(['receipt' => ['Receipt has to be either an image or a pdf document']]);
        }

        return $data;
    }
}
