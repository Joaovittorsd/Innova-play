<?php

declare(strict_types=1);

namespace Innova\Mvc\Service;

use finfo;
use Psr\Http\Message\UploadedFileInterface;

class FileUploader
{
    private string $uploadDir;
    private UploadedFileInterface $file;

    public function __construct(UploadedFileInterface $file, string $uploadDir = __DIR__ . '/../../public/img/uploads/')
    {
        $this->file = $file;
        $this->uploadDir = $uploadDir;
    }

   public function upload(): ?string
    {
        if ($this->file->getError() === UPLOAD_ERR_OK) {
            $info = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $info->file($this->file->getStream()->getMetadata('uri'));
        
            if (str_starts_with($mimeType, 'image/')) {
                $fileName = $this->sanitizeFileName($this->file->getClientFilename());
                $filePath = $this->uploadDir . $fileName;
                
                if (move_uploaded_file($this->file->getStream()->getMetadata('uri'), $filePath)) {
                    return $fileName; 
                }
            }
        }
        return null;
    }

    private function sanitizeFileName(string $fileName): string
    {
        $fileName = preg_replace('/[^a-zA-Z0-9]+/', '-', $fileName);
        $fileName = trim($fileName, '-');
        return uniqid('upload_') . '_' . $fileName;
    }
}
