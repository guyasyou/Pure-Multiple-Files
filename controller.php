<?php 
/**
 * Created by Pure/Web for Pure/Multiple files
 * www.pure-web.ru
 * © 2016
 */

namespace Concrete\Package\PureMultipleFiles;
use \Concrete\Core\Package\Package as PackageInstaller;
use Route;
use \Concrete\Core\Attribute\Type as AttributeType;
use File;

defined('C5_EXECUTE') or die("Access Denied.");

class Controller extends PackageInstaller {

	protected $pkgHandle = 'pure_multiple_files';
	protected $appVersionRequired = '8.0.0';
	protected $pkgVersion = '1.0.6';

    public function getPackageName() {
		return t("Multiple files attribute");
	}

	public function getPackageDescription() {
		return t("Multiple file selection attribute");
	}

    public function on_start() {

        //*******************************************
        //Assets
        $al = \Concrete\Core\Asset\AssetList::getInstance();

        //JS
        $al->register(
            'javascript', //asset type
            'jquery/tmpl', //asset name
            'assets/js/jquery.tmpl.min.js', //path
            array(),
            'pure_multiple_files' //from package
        );
        //********************


        //*******************************************
        //Routes
        Route::register('/ccm/multiple_files_attribute/get_file_info/', function() {
            /** @var \Concrete\Core\Validation\CSRF\Token $token */
            $token = \Core::make('token');
            /** @var \Concrete\Core\Error\ErrorList\ErrorList $e */
            $e = \Core::make('error');
            /** @var \Concrete\Core\Http\Service\AJAX $ajax */
            $ajax =  \Core::make('helper/ajax');

            if ($token->validate('get_files_info')) {
                //$fIDs = explode(',', $_GET['fIDs']);
                $fIDs = $_GET['fIDs'];

                if (is_array($fIDs) && count($fIDs) > 0) {
                    foreach ($fIDs as $fID) {
                        /** @var \Concrete\Core\File\File $file */
                        $file = File::getByID($fID);
                        //var_dump($file); exit;
                        if ($file instanceof \Concrete\Core\Entity\File\File) {
                            /** @var \Concrete\Core\Entity\File\Version $fv */
                            $fv = $file->getRecentVersion();
                            $fileInfo = new \StdClass();
                            $fileInfo->fID = $fv->getFileID();
                            $fileInfo->filename = $fv->getTitle();
                            $fileInfo->thumbnailIMG = $fv->getListingThumbnailImage();
                            $files[] = $fileInfo;
                        } else {
                            $e->add(t('File not found'));
                            break;
                        }
                    }
                } else {
                    $e->add(t('File IDs is not valid'));
                }
            } else {
                $e->add($token->getErrorMessage());
            }

            if (!$e->has()) {
                $ajax->sendResult($files);
            } else {
                $ajax->sendError($e);
            }
        });
        //*********************

    }

    public function install() {
        /** @var $pkg \Concrete\Core\Entity\Package() */
        $pkg = parent::install(); //parent is \Concrete\Core\Package\Package
        //\Concrete\Core\Attribute\Type as AttributeType
        AttributeType::add("pure_multiple_files", "Multiple Files Attribute", $pkg);

	}

}