<?php
namespace NeeZiaa\Stream;

use NeeZiaa\Stream\Exceptions\OpenStreamException;

class Stream {

    private string $directory;

    private string $file;

    public function __construct(string $directory, string $file)
    {
        $this->directory = $directory;
        $this->file = $file;
    }

    public function getContents() 
    {
        
    }

    public function putContents(string $content, bool $erase = false) 
    {

    }

    private static function createFile()
    {

    }

    public function upload(mixed $file): bool|string
    {
        $file = $file->file;
        if ($file["error"] == 0) {

            $filename = $file->getName();
            $filetype = $file->getType();
            $filesize = $file->getSize();

            $ext = pathinfo($filename, PATHINFO_EXTENSION);

            if (file_exists($this->directory . $this->filename)) {
                return $this->filename . " existe déjà.";
            } else {
                try {
                    move_uploaded_file($file["tmp_name"], trim($this->directory, '\/') . DIRECTORY_SEPARATOR . $this->filename);
                    return true;
                } catch(UploadFileException $e) {
                    throw new UploadFileException("Une erreur est survenue lors du téléchargement de votre fichier");
                }
            }
        } else {
            return "Error: " . $file["error"];
        }
    }

}