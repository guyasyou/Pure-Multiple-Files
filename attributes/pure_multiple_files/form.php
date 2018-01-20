<?php
/**
 * Created by Pure/Web for Pure/Multiple files
 * www.pure-web.ru
 * © 2016
 */
 
defined('C5_EXECUTE') or die("Access Denied.");
/** @var Concrete\Core\Attribute\Key\CollectionKey $attributeKey*/
$akID = $attributeKey->getAttributeKeyID();
$akMaxFilesCount = $akMaxFilesCount ? $akMaxFilesCount : 0
/** @var \Concrete\Core\Validation\CSRF\Token $token */
?>
<script id="file-input-template-ak<?php echo $akID?>" type="text/x-jquery-tmpl">
    <div class="selected-file ui-state-default" data-fid="${fID}">
        <div title="${filename}" class="icon">

        </div>
        <input class="fileID" type="hidden" name="akID[<?php echo $akID?>][value][]" value="${fID}"/>
        <span title="<?php echo t('Remove')?>" class="delete"><i class="fa fa-trash-o"></i></span>
    </div>
</script>

<div class="multiple-files-wrapper">
    <span class="help-block"><?php echo t('Drag and drop to sort')?></span>
    <div id="multiple-files-ak<?php echo $akID?>" class="multiple-files-selected-list">

    </div>
    <?php 
    if ($akMaxFilesCount) { ?>
        <span class="help-block"><?php echo t('Maximum')?>: <?php echo $akMaxFilesCount?></span><?php 
    }
    ?>
</div>
<div>
	<button type="button" data-akid="<?php echo $akID?>" data-launch="file-manager" class="btn btn-small btn-primary"><i class="fa fa-plus"></i> <?php  echo t("Add files")?></button>
</div>

<script type="text/javascript">
    $(function() {
        var akID = <?php echo $akID?>;
        var token = "<?php echo $token->generate('get_files_info')?>";
        var max_count = <?php echo $akMaxFilesCount ? $akMaxFilesCount : 0?>;
        var multipleFilesCurrentSelected = [<?php 
        if (is_array($currentFiles)) {
            foreach ($currentFiles as $index => $file) {
                $fv = $file->getRecentVersion();
                echo '{fID:'.$fv->getFileID().',filename:\''.$fv->getTitle().'\',thumbnailIMG:\''.$fv->getListingThumbnailImage().'\'}';
                if ($index < count($currentFiles) - 1) {
                    echo ',';
                }
            }
        }
            ?>];
        MultipleFilesAttribute.init(akID, multipleFilesCurrentSelected, max_count, token);
    });
</script>