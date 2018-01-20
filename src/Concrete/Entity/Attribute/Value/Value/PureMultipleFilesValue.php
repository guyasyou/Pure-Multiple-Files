<?php
/**
 * Created by Pure/Web for Pure/Multiple files
 * www.pure-web.ru
 * © 2016
 */

namespace Concrete\Package\PureMultipleFiles\Entity\Attribute\Value\Value;

use Concrete\Core\File\FileProviderInterface;
use Concrete\Core\Entity\Attribute\Value\Value\AbstractValue;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="PureMultipleFilesValue")
 */
class PureMultipleFilesValue extends AbstractValue implements FileProviderInterface
{
    /**
     * @ORM\OneToMany(targetEntity="\Concrete\Package\PureMultipleFiles\Entity\Attribute\Value\Value\PureMultipleFilesSelectedFiles",
     *     cascade={"persist", "remove"}, mappedBy="value")
     * @ORM\JoinColumn(name="avID", referencedColumnName="avID")
     */
    protected $files;

    public function __construct()
    {
        $this->files = new ArrayCollection();
    }


    public function getSelectedFiles()
    {
        return $this->files;
    }

    public function setSelectedFiles($files)
    {
        $this->files = $files;
    }

    public function getFileObjects()
    {
        $files = array();
        $values = $this->getSelectedFiles();
        if ($values->count()) {
            foreach ($values as $f) {
                $files[] = $f->getFile();
            }
        }

        return $files;
    }

    public function __toString()
    {
        $string = '';
        foreach ($this->getFileObjects() as $file) {
            /** @var \Concrete\Core\Entity\File\File $file */
            $string .= \URL::to('/download_file', $file->getFileID()) . ' ';
        }
        return $string;
    }
}
