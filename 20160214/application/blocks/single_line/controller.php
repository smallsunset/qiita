<?php
namespace Application\Block\SingleLine;
use \Concrete\Core\Block\BlockController;

class Controller extends BlockController {

	// db.xml で指定したテーブル名を記述します。
	protected $btTable = 'SingleLine';

  // キャッシュに関する設定を行います。
  // キャッシュするには true を、しない場合は false を指定。
  protected $btCacheBlockRecord = true;
  protected $btCacheBlockOutput = true;
  protected $btCacheBlockOutputOnPost = true;
  protected $btCacheBlockOutputForRegisteredUsers = true;

  // ブロックタイプの説明を出力
  public function getBlockTypeDescription() {
    return t("１行のテキストを表示します。");
  }

  // ブロックタイプのタイトルを出力
  public function getBlockTypeName() {
    return t("１行テキスト");
  }

}