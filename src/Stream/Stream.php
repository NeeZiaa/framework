<?php
namespace NeeZiaa\Stream;

use NeeZiaa\Stream\Exceptions\StreamException;

class Stream {

    private string $directory;

    private string $file;

    public function __construct(string $directory, string $file)
    {
        $this->directory = trim($directory, '\/');
        $this->file = trim($file, '\/');
    }

    public function getData()
    {
        // dd($this->directory . DIRECTORY_SEPARATOR . $this->file);
        if(file_exists($this->directory . DIRECTORY_SEPARATOR . $this->file))
        {
            return file_get_contents($this->directory . DIRECTORY_SEPARATOR . $this->file);
        } else {
            throw new StreamException("File not found");
        }
    }

    public function putData(string $data, bool $overwritte = false, bool $createIfNotExist = false) 
    {            
        if(is_dir($this->directory)) {
            if(!file_exists($this->directory . DIRECTORY_SEPARATOR . $this->file)){
                if(!$createIfNotExist) {
                    throw new StreamException("File not found");
                }
            }
            $stream = fopen($this->directory . DIRECTORY_SEPARATOR . $this->file, "a+");
            if($overwritte) {
                file_put_contents($this->directory . DIRECTORY_SEPARATOR . $this->file, $data);
            } else {
                fwrite($stream, $data);
            }
        } else {
            throw new StreamException("Directory not found");
        }
    }

    public function upload(mixed $file): bool|string
    {
        $file = $file->file;
        if ($file["error"] == 0) {

            $filename = $file->getName();
            $filetype = $file->getType();
            $filesize = $file->getSize();

            $ext = pathinfo($filename, PATHINFO_EXTENSION);

            if (file_exists($this->directory . $this->file)) {
                return $this->file . " existe déjà.";
            } else {
                try {
                    move_uploaded_file($file["tmp_name"], $this->directory . DIRECTORY_SEPARATOR . $this->file);
                    return true;
                } catch(StreamException $e) {
                    throw new StreamException("Une erreur est survenue lors du téléchargement de votre fichier");
                }
            }
        } else {
            return "Error: " . $file["error"];
        }
    }

}