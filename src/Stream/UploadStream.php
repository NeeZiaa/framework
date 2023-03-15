<?php

namespace NeeZiaa\Utils;

use NeeZiaa\Stream\Exceptions\UploadFileException;

class UploadStream {

    private array $file;

    /**
     * @param $path
     * @param $file
     * @return bool|string
     */
    public static function erase($path, $file): bool|string
    {
        if(file_exists(trim($path, '\/') . DIRECTORY_SEPARATOR . trim($file, '\/'))) return unlink($path . $file);
        return "OpenFile not exist";
    }

    public static function upload(
        mixed $file, string $directory, float|int $maxsize = 10 * 1024 * 1024,
        array $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png")
    ): bool|string
    {
        $file = $file->file;
        if ($file["error"] == 0) {

            $filename = $file->getName();
            $filetype = $file->getType();
            $filesize = $file->getSize();

            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if (!array_key_exists($ext, $allowed))
                return "Error : Veuillez sélectionner un format de fichier valide.";

            $maxsize = 10 * 1024 * 1024;
            if ($filesize > $maxsize)
                return "Error: La taille du fichier est supérieure à la limite autorisée.";

            if (in_array($filetype, $allowed)) {
                if (file_exists($directory . $filename)) {
                    return $filename . " existe déjà.";
                }
                else {
                    try {
                        move_uploaded_file($file["tmp_name"], trim($directory, '\/') . DIRECTORY_SEPARATOR . $filename);
                        return true;
                    } catch(UploadFileException $e) {
                        throw new UploadFileException("Une erreur est survenue lors du téléchargement de votre fichier");
                    }
                }
            }
            else {
                return "Error: Il y a eu un problème de téléchargement de votre fichier. Veuillez réessayer.";
            }
        }
        else {
            return "Error: " . $file["error"];
        }
    }


    /**
     * @param $file
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    public function getContent()
    {
        return file_get_contents($this->file);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return str_replace(' ', '_', $this->file['name']);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->file['type'];
    }

    /**
     * @return string
     */
    public function getSize(): string
    {
        return $this->file['size'];
    }

}