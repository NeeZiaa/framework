<?php

namespace NeeZiaa\Utils;

use function PHPUnit\Framework\isNull;

class File {

    private array $file;

    public static function erase($path, $file): bool|string
    {
        if(file_exists(trim($path, '/') . '/' . trim($file, '/'))) return unlink($path . $file);
        return "File not exist";
    }

    public function __construct($file)
    {
        $this->file = $file;
    }

    public function getName(): string
    {
        return str_replace(' ', '_', $this->file['name']);
    }

    public function getType(): string
    {
        return $this->file['type'];
    }

    public function getSize(): string
    {
        return $this->file['size'];
    }

    public function upload(
        $directory, $maxsize = 10 * 1024 * 1024,
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png")
    ): bool|string
    {
        $file = $this->file;
        if ($file["error"] == 0) {

            $filename = $this->getName();
            $filetype = $this->getType();
            $filesize = $this->getSize();

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
                    move_uploaded_file($file["tmp_name"], trim($directory, '/') . '/' . $filename);
                    return true;
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

}