<?php
/**
 * Created by Pure/Web for Pure/Multiple files
 * www.pure-web.ru
 * © 2016
 */

namespace Concrete\Package\PureMultipleFiles\Entity\Attribute\Key\Settings;
use Concrete\Core\Entity\Attribute\Key\Settings\Settings;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="PureMultipleFilesSettings")
 */
class MultipleFilesSettings extends Settings
{
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $akMaxFilesCount;

    public function getAttributeTypeHandle()
    {
        return 'pure_multiple_files';
    }

    /**
     * @return integer
     */
    public function getMaxFilesCount()
    {
        return $this->akMaxFilesCount;
    }

    /**
     * @param integer $maxFilesCount
     */
    public function setMaxFilesCount($maxFilesCount)
    {
        $this->akMaxFilesCount = $maxFilesCount;
    }

}
