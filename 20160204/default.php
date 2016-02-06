<?php
    // おまじない
    defined('C5_EXECUTE') or die("Access Denied.");
?>
<html>
<head>
<?php
    // concrete5 の動作に必要な、<head> タグ内コードを出力
    Loader::element('header_required');
?>
</head>
<body>
<!-- 編集時 concrete5 の上部メニューの表示で、コンテンツが隠れてしまうのを予防します -->
<div class="<?php echo $c->getPageWrapperClass()?>">
<?php
    // ブロックを置けるエリアを作成
    $a = new Area('Area1');
    $a->display();
    // エリアを複数作る場合は、Area(引数); の引数を変更して作成
    $a = new Area('Area2');
    $a->display();
?>
</div>
<?php
    // concrete5 の動作に必要な、<body> タグ内コードを出力 </body> 直前に置くのがお作法らしい
    Loader::element('footer_required');
?>
</body>
</html>
