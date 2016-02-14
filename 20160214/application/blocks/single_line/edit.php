<?php
    // おまじない
    defined('C5_EXECUTE') or die(_("Access Denied."));
    // フォームヘルパー読み込み
    $form = Core::make('helper/form');
?>
<label class="control-label"><?php echo t('１行テキスト')?></label>
<?php echo $form->text('content', $content) ?>
