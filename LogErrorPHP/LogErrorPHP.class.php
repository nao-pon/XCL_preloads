<?php
if (!defined('XOOPS_ROOT_PATH')) exit();

require_once XOOPS_ROOT_PATH.'/modules/legacy/class/Legacy_Debugger.class.php';

class LogErrorPHP extends XCube_ActionFilter
{
  function preFilter()
  {
    ini_set('log_errors', true);
    ini_set('error_log', XOOPS_TRUST_PATH.'/cache/'.rawurlencode(substr(XOOPS_URL, 7)).'_php_errors.log');
    $this->mController->mSetupDebugger->add('LogErrorPHP::createInstance');
  }
  
  public static function createInstance(&$instance, $debug_mode)
  {
    switch ($debug_mode) {
      case XOOPS_DEBUG_PHP:
        $instance = new PHPDebuggerWrite();
        break;
      case XOOPS_DEBUG_MYSQL:
        $instance = new Legacy_MysqlDebugger();
        break;
      case XOOPS_DEBUG_SMARTY:
        $instance = new Legacy_SmartyDebugger();
        break;
      case XOOPS_DEBUG_OFF:
      default:
        $instance = new PHPDebuggerNone();
        break;
    }
  }
}

class PHPDebuggerWrite extends Legacy_AbstractDebugger
{
  function prepare()
  {
    error_reporting(E_ALL ^ E_STRICT);
    ini_set('display_errors', true);
  }
}

class PHPDebuggerNone extends Legacy_AbstractDebugger
{
  function prepare()
  {
    error_reporting(E_ALL ^ E_STRICT);
    ini_set('display_errors', false);
  }
}