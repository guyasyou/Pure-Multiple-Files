<?php
/**
 * Created by Pure/Web for Pure/Multiple files
 * www.pure-web.ru
 * © 2016
 */

defined('C5_EXECUTE') or die("Access Denied.");

$akMaxFilesCount = isset($akMaxFilesCount) ? $akMaxFilesCount : 0;
?>
<fieldset>
    <legend><?php  echo t('Multiply Files Options')?></legend>
    <div class="form-group">
        <label class="control-label"><?php  echo t("Maximum count of files")?></label>
        <div class="controls">
            <?php  echo $form->number('akMaxFilesCount', $akMaxFilesCount, array('min' => 0))?>
        </div>
        <span class="help-block"><?php echo t('0 for infinity')?></span>
    </div>
</fieldset>