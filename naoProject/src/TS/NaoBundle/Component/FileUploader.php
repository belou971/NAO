<?php
/**
 * Created by PhpStorm.
 * User: belou
 * Date: 23/05/18
 * Time: 15:32
 */

namespace TS\NaoBundle\Component;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Class FileUploader
 * @package TS\NaoBundle\Component
 */
class FileUploader
{
    /**
     * @var string
     *
     * The directory path where file will be uploaded
     */
    private $targetDirectory;
    /**
     * @var UploadedFile
     *
     * Represents the instance of the uploaded file
     */
    protected $file;
    /**
     * @var string
     */
    private $tempFileName;
    /**
     * @var string
     */
    private $fileName;

    /**
     * FileUploader constructor.
     * @param $targetDirectory
     */
    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    /**
     * @return string
     */
    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }

    /**
     * @param string $targetDirectory
     */
    public function setTargetDirectory($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    /**
     *
     */


}