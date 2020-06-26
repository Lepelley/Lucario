<?php

namespace Lucario\Upload;

class Upload
{
    const IMAGE = [
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/jpeg' => 'jpg'
    ];

    /**
     * @var array<string,string> [Type => Extension]. Example : ['image/png' => 'png'] Default = UPLOAD::IMAGE
     */
    private array $allowedExtensions;

    public function __construct()
    {
        $this->allowedExtensions = self::IMAGE;
    }

    public function setAllowedExtensions(array $extensions): self
    {
        $this->allowedExtensions = $extensions;

        return $this;
    }

    /**
     * @param array<string,string> $response   $_FILES['your_field']
     * @param string               $uploadPath
     *
     * @return string|bool
     *
     * @throws UploadException
     */
    public function save(array $response, string $uploadPath)
    {
        $file = $this->controlUpload($response, $this->allowedExtensions);
        $filenameParts = explode('.', $file['name']);
        $filename = sha1(uniqid()).'.'.$filenameParts[count($filenameParts) - 1];

        if (false === move_uploaded_file($file['tmp_name'], $uploadPath.DIRECTORY_SEPARATOR.$filename)) {
            return false;
        }

        return $filename;
    }

    /**
     * @param array          $file
     * @param array<string,string> $typeAllowed
     *
     * @return array
     *
     * @throws UploadException
     */
    protected function controlUpload(array $file, array $typeAllowed = ['application/pdf' => 'pdf', 'application/x-pdf' => 'pdf', 'image/png'=> 'png'])
    {
        if(false === isset($file["error"]) || $file["error"] !== 0) {
            throw new UploadException(sprintf("L'upload du fichier a échoué, erreur : %s", json_encode(($file['error']))));
        }
        if (empty($file["name"])) {
            throw new UploadException("Le fichier envoyé n'a pas un nom correct.");
        }
        if (empty($file["tmp_name"])) {
            throw new UploadException("L'upload du fichier a échoué.");
        }
        if (empty($file["type"])) {
            throw new UploadException("Le format du fichier ne convient pas.");
        }

        // En récupérant uniquement le basename on évite la faille directory Traversal
        $file["name"] = basename($file["name"]);
        // On vérifie correctement que l'upload a réussi
        // (le code error 0 nous indique déjà que oui)
        // mais ce double controle
        /**
         * @codeCov
         */
        if(!$this->isUploadedFile($file['tmp_name'])) {
            throw new UploadException("Le fichier est introuvable");
        }
        // On controle le type soumis par le client (mais cela ne s'écurise pas le contenu)
        if(!array_key_exists($file['type'], $typeAllowed)) {
            throw new UploadException("Le format du fichier ne convient pas.");
        }
        // controle mime-type du fichier
        if(!array_key_exists((string) mime_content_type($file['tmp_name']), $typeAllowed)) {
            throw new UploadException("Le format du fichier ne convient pas.");
        }

        // on vérifie que l'extension correspond au format
        if($typeAllowed[$file['type']] !== pathinfo($file["name"], PATHINFO_EXTENSION)) {
            throw new UploadException("Le fichier envoyé n'a pas un nom correct.");
        }

        return $file;
    }

    public function isUploadedFile($file)
    {
        return is_uploaded_file($file);
    }
}
