<?php
namespace NeeZiaa\Stream;

use NeeZiaa\Stream\Exceptions\OpenStreamException;

class OpenStream {

    private function __construct()
    {

    }

    /**
     * @param string $file
     * @param string $mode
     * @return false|resource
     * @throws OpenStreamException
     */
    public static function tryFopen(string $file, string $mode)
    {
        try {
            return fopen($file, $mode);
        } catch (OpenStreamException $e) {
            throw new OpenStreamException(
                "Une erreur est survenue lors de l'ouverture de votre fichier | " . $e
            );
        }

    }

}