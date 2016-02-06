<?php
namespace Application\Block\ExternalForm\Form\Controller;
use Concrete\Core\Controller\AbstractController;
use Core;
use Page;

class TForm1 extends AbstractController {

  // action を指定しなかった場合、view が実行されると思われる。初期表示。
  public function view() {
    // 最初にフォームを開いた時の処理です。初期値を view に渡して、編集画面の表示を指定します。
    $input['p_name'] = '';
    $input['p_email'] = '';
    $input['p_message'] = '';

    $this->set('isvalid', true);

    $this->set('input', array());
    $this->set('error', array());

    $this->set('section', 'edit');
  }

  // 入力値の検証処理
  private function validate() {
    // バリデーション結果を成功に設定
    $isvalid = true;
    // 入力値を取得
    $input['p_name'] = trim($this->post('p_name'));
    $input['p_email'] = trim($this->post('p_email'));
    $input['p_message'] = trim($this->post('p_message'));

    // 検証実行
    if ($input['p_name'] == '') {
      $isvalid = false;
      $error['p_name'] = 'お名前は、必ず入力してください。';
    }

    if ($input['p_email'] == '') {
      $isvalid = false;
      $error['p_email'] = 'メールアドレスは、必ず入力してください。';
    }

    if ($input['p_message'] == '') {
      $isvalid = false;
      $error['p_message'] = 'メッセージは、必ず入力してください。';
    }

    // View に値を渡す
    $this->set('isvalid', $isvald);

    $this->set('input', $input);
    $this->set('error', $error);

    return $isvalid;
  }

  public function action_confirm() {
    // 検証結果によって、確認(confirm) を、編集(edit) セクションを出し分け。
    if ($this->validate()) {
      $section = 'confirm';
    } else {
      $section = 'edit';
    }
    $this->set('section', $section);
  }

  public function action_update() {
    // 検証結果によって、完了(complete) を、編集(edit) セクションを出し分け。
    if ($this->validate()) {
      // *****
      // ***** ここにメール送信や更新処理を書く。 *****
      // *****
      $c = Page::getCurrentPage();
      header('location: '.Core::make('helper/navigation')->getLinkToCollection($c, true).'/complete');
      exit;
    } else {
      $this->set('section', 'edit');
    }
  }

  public function action_complete() {
    $this->set('section','complete');
  }

  public function action_back() {
    // 戻るボタンで、編集(edit)セクションを表示します。
    $section = 'edit';
    $this->set('section', $section);
  }

}
