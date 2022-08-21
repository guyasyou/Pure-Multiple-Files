<?php
/**
 * Created by Pure/Web for Pure/Multiple files
 * www.pure-web.ru
 * © 2016
 */

namespace Concrete\Package\PureMultipleFiles\Attribute\PureMultipleFiles;

use Concrete\Core\Attribute\FontAwesomeIconFormatter;
use Concrete\Package\PureMultipleFiles\Entity\Attribute\Value\Value\PureMultipleFilesValue;
use Concrete\Package\PureMultipleFiles\Entity\Attribute\Value\Value\PureMultipleFilesSelectedFiles;
use Concrete\Package\PureMultipleFiles\Entity\Attribute\Key\Settings\MultipleFilesSettings;
use Concrete\Core\Error\ErrorList\Error\Error;
use Concrete\Core\Error\ErrorList\Error\FieldNotPresentError;
use Concrete\Core\Error\ErrorList\Field\AttributeField;
use Concrete\Core\File\File;
use Concrete\Core\Attribute\Controller as AttributeTypeController;

class Controller extends AttributeTypeController
{

    public $akMaxFilesCount;

    public function getIconFormatter()
    {
        return new FontAwesomeIconFormatter('files-o');
    }

    public function getSearchIndexValue()
    {
        return false;
    }

    public function getDisplayValue()
    {
        $html = '';
        $filesValue = $this->attributeValue->getValue();
        if (is_object($filesValue)) {
            $filesArray = $filesValue->getFileObjects();
            if (count($filesArray) > 0) {
                $this->addHeaderItem($this->app->make('helper/html')->css('/'.DIRNAME_PACKAGES.'/pure_multiple_files/attributes/pure_multiple_files/view.css'));
                $html .= '<div class="display_multiple_files multiple_files_akID_'.$this->getAttributeKey()->getAttributeKeyID().'">';
                foreach ($filesArray as $file) {
                    /** @var \Concrete\Core\File\File $file */
                    $fv = $file->getRecentVersion();
                    $html .= '<div class="file">';
                        $html .= '<div class="thumb">';
                            $html .= '<a href="'.$fv->getDownloadURL().'" title="'.$fv->getTitle().'">';
                                $html .= $fv->getListingThumbnailImage();
                            $html .= '</a>';
                        $html .= '</div>';
                        $html .= '<div class="filename">';

                            $html .= '<a href="'.$fv->getDownloadURL().'" title="'.$fv->getTitle().'">';
                                $html .= $fv->getTitle();
                            $html .= '</a>';

                            $description = $fv->getDescription();
                            if ($description) {
                                $html .= '<p class="description">';
                                    $html .= $description;
                                $html .= '</p>';
                            }

                        $html .= '</div>';
                    $html .= '<div class="clearfix"></div>';
                    $html .= '</div>';
                }
                $html .= '</div>';
            } else {
                $html .= t('Files not found');
            }
        } else {
            $html .= t('Attribute value not found');
        }
        return $html;
    }

    protected function load()
    {
        $ak = $this->getAttributeKey();
        if (!is_object($ak)) {
            return false;
        }
        $this->set('attributeKey', $ak);
        $this->akMaxFilesCount = $ak->getAttributeKeySettings()->getMaxFilesCount();
        $this->set('akMaxFilesCount', $this->akMaxFilesCount);
    }

    public function form()
    {
        $this->requireAsset('core/file-manager');
        $this->requireAsset('jquery/ui');
        $this->requireAsset('javascript', 'jquery/tmpl');

        $this->set('token', $this->app->make('token'));

        $currentFiles = [];
        if (is_object($this->attributeValue)) {
            $attributeFilesValue = $this->attributeValue->getValue();
            if ($attributeFilesValue) {
                $attributeFiles = $attributeFilesValue->getFileObjects();
                if (is_array($attributeFiles)) {
                    foreach ($attributeFiles as $file) {
                        $fv = $file->getRecentVersion();
                        $currentFiles[] = array(
                            'fID' => $fv->getFileID(),
                            'filename' => $fv->getTitle(),
                            'thumbnailIMG' => $fv->getListingThumbnailImage()
                        );
                    }
                }
            }
        }
        $this->set('currentFiles', $currentFiles);

        $this->load();
    }

    public function type_form()
    {
        $this->set('form', $this->app->make('helper/form'));
        $this->load();
    }

    public function saveKey($data)
    {
        $type = $this->getAttributeKeySettings();

        $data['akMaxFilesCount'] = $data['akMaxFilesCount'] ? $data['akMaxFilesCount'] : NULL;
        $type->setMaxFilesCount($data['akMaxFilesCount']);

        return $type;

    }

    public function validateKey($data = array())
    {
        $e = $this->app->make('error');
        if ($data['akMaxFilesCount'] && !is_numeric($data['akMaxFilesCount'])) {
            $e->add(t('Maximum count of files must be number'), 'akMaxFilesCount', 'Maximum count of files');
        }

        if (!($data['akMaxFilesCount'] >= 0)) {
            $e->add(t('Maximum count of files must be greater than zero'), 'akMaxFilesCount', 'Maximum count of files');
        }
        return $e;
    }

    public function validateForm($data)
    {
        if (!isset($this->akMaxFilesCount) ) {
            $this->load();
        }
        if (!is_array($data['value'])) {
            return new FieldNotPresentError(new AttributeField($this->getAttributeKey()));

        } else if ($this->akMaxFilesCount> 0 && count($data['value']) > $this->akMaxFilesCount) {
            return new Error(t('Limit of files is exceeded for %s', $this->getAttributeKey()->getAttributeKeyDisplayName()),
                new AttributeField($this->getAttributeKey())
            );
        }
        return true;
    }

    public function validateValue()
    {
        $e = true;
        if (!(is_object($this->attributeValue->getValue())
            && count($this->attributeValue->getValue()->getFileObjects()))) {
            $e = $this->app->make('error');
            $e->add(t('You must specify a valid file for %s', $this->attributeKey->getAttributeKeyDisplayName()));
        }
        return $e;
    }

    public function createAttributeValue($value = null)
    {
        $av = new PureMultipleFilesValue();
        if (is_array($value) && count($value)) {
            foreach ($value as $fID) {
                $file = File::getByID($fID);
                if ($file instanceof \Concrete\Core\Entity\File\File) {
                    $avFile = new PureMultipleFilesSelectedFiles();
                    $avFile->setFile($file);
                    $avFile->setAttributeValue($av);
                    $av->getSelectedFiles()->add($avFile);
                }
            }
        }
        return $av;

    }

    public function createAttributeValueFromRequest()
    {
        $data = $this->post();
        if (isset($data['value'])) {
            return $this->createAttributeValue($data['value']);
        }

        return $this->createAttributeValue(null);
    }

    public function getAttributeValueObject()
    {
        return $this->entityManager->find(PureMultipleFilesValue::class, $this->attributeValue->getGenericValue());
    }

    public function createAttributeKeySettings()
    {
        return new MultipleFilesSettings();
    }

    protected function retrieveAttributeKeySettings()
    {
        return $this->entityManager->find(MultipleFilesSettings::class, $this->attributeKey);
    }
}