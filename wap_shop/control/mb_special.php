<?php
/**
 * cms专题
 *
 * @copyright
 * @link
 */
defined('InShopNC') or exit('Access Invalid!');
class mb_specialControl extends BaseStoreControl {
   public function indexOp() {
      $model_special = Model('mb_special');
      $data          = $model_special->getMbSpecialItemUsableListByID($_GET['special_id']);
      $html_path     = $model_special->getMbSpecialHtmlPath($special_id);
      Tpl::output('type', $_GET['type']);
      // if (!is_file($html_path)) {
         // ob_start();
         Tpl::output('list', $data);
         Tpl::showpage('mb_special');
         // @file_put_contents($html_path, ob_get_clean());
      // }
      // @header('Location: ' . $model_special->getMbSpecialHtmlUrl($special_id));
   }
}
