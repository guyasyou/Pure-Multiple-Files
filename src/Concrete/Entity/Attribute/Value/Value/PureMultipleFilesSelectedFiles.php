<?php
/**
 * Created by Pure/Web for Pure/Multiple files
 * www.pure-web.ru
 * © 2016
 */

namespace Concrete\Package\PureMultipleFiles\Entity\Attribute\Value\Value;

use File;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="PureMultipleFilesSelectedFiles")
 */
class PureMultipleFilesSelectedFiles
{
    /**
     * @ORM\Id @ORM\Column(type="integer", options={"unsigned":true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $avsID;

    /**
     * @ORM\ManyToOne(targetEntity="\Concrete\Package\PureMultipleFiles\Entity\Attribute\Value\Value\PureMultipleFilesValue")
     * @ORM\JoinColumn(name="avID", referencedColumnName="avID", onDelete="CASCADE")
     */
    public $value;

    /**
     * @ORM\Column(type="integer", nullable=false, options={"unsigned": true})
     */
    public $fID;

    /**
     * @ORM\ManyToOne(targetEntity="\Concrete\Core\Entity\File\File")
     * @ORM\JoinColumn(name="fID", referencedColumnName="fID", onDelete="CASCADE")
     */
    protected $file;

    /**
     * @return \Concrete\Core\Entity\File\File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param \Concrete\Core\Entity\File\File $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return mixed
     */
    public function getAttributeValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setAttributeValue($value)
    {
        $this->value = $value;
    }
}
