<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class Uploader
{

        public function upload(UploadedFile $file, string $directory, string $name = ""){

            //on renomme le fichier -- uniqid génère un nombre aleatoire basé sur le timestamp
            //guessExtension récupère le type de fichier qqsoit son extension
            $newFileName = $name."-".uniqid().".".$file->guessExtension();
            //on déplace le fichier pour le sauvegarder; 1er param=directory, 2e param=nom
            $file->move($directory, $newFileName);

            return $newFileName;
        }
}