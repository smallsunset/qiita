<?php
  // おまじない
  defined('C5_EXECUTE') or die(_("Access Denied."));
  // フォームヘルパー読み込み
  $form = Core::make('helper/form');
?>

<script type="text/javascript">
  $(document).ready(function(){
    $(document).on('click', '#OnClickConfirm', function () {
      $('#form_edit').attr('action', '<?php echo($view->action('confirm')); ?>');
      $('#form_edit').submit();
    });
    $(document).on('click', '#OnClickUpdate', function () {
      $('#form_confirm').attr('action', '<?php echo($view->action('update')); ?>');
      $('#form_confirm').submit();
    });
    $(document).on('click', '#OnClickBack', function () {
      $('#form_confirm').attr('action', '<?php echo($view->action('back')); ?>');
      $('#form_confirm').submit();
    });
  });
</script>

<?php
  if ($section == 'edit') {
?>
  <div>
    お問い合わせは、以下の内容フォームから入力してください。
  </div>
  <br>
  <form method="post" id="form_edit" action="#">
    <div class="form-group">
      <label class="control-label"><?php echo t('お名前')?></label>
      <?php echo $form->text('p_name')?>
      <?php echo(isset($error['p_name']) ? '<font color="#ff0000">'.$error['p_name'].'</font>' : ''); ?>
    </div>

    <div class="form-group">
      <label class="control-label"><?php echo t('メールアドレス')?></label>
      <?php echo $form->text('p_email')?>
      <?php echo(isset($error['p_email']) ? '<font color="#ff0000">'.$error['p_email'].'</font>' : ''); ?>
    </div>

    <div class="form-group">
      <label class="control-label"><?php echo t('メッセージ')?></label>
      <?php echo $form->textarea('p_message')?>
      <?php echo(isset($error['p_message']) ? '<font color="#ff0000">'.$error['p_message'].'</font>' : ''); ?>
    </div>

    <div class="form-group">
      <a href="#" id="OnClickConfirm" class="btn btn-primary">確認</a>
    </div>
  </form>
<?php
  }
?>

<?php
  if ($section == 'confirm') {
?>
    <div>
      以下の内容を送信してもよろしいですか？
    </div>
    <br>
    <table class="table table-stripe">
      <tr>
        <th>お名前</th>
        <td><?php echo(h($input['p_name'])); ?></td>
      </tr>
      <tr>
        <th>メールアドレス</th>
        <td><?php echo(h($input['p_email'])); ?></td>
      </tr>
      <tr>
        <th>メッセージ</th>
        <td><?php echo(nl2br(h($input['p_message']))); ?></td>
      </tr>
    </table>

    <form method="post" id="form_confirm" action="#">
      <div class="form-group">
        <?php echo $form->hidden('p_name')?>
        <?php echo $form->hidden('p_email')?>
        <?php echo $form->hidden('p_message')?>
        <a href="#" id="OnClickUpdate" class="btn btn-primary">送信</a>
        <a href="#" id="OnClickBack" class="btn btn-danger">戻る</a>
      </div>
    </form>
<?php
    }
?>

<?php
  if ($section == 'complete') {
?>
    <div>
      お問い合わせ、ありがとうございました。
    </div>
<?php
    }
?>
