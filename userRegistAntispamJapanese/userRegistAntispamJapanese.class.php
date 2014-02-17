<?php

if (!defined('XOOPS_ROOT_PATH')) exit();

class userRegistAntispamJapanese extends XCube_ActionFilter
{
	// 言語定数オーバーライドシステムを利用する場合には適切にディレクトリ名を指定する
	private $mydirname = 'd3forum';
	
	function postFilter() {
		$this->mRoot->mDelegateManager->add('Legacy.Event.RegistUser.Validate', array($this, 'registUserValidate'));
		$this->mRoot->mDelegateManager->add('Legacy.Event.RegistUser.SetValidators', array($this, 'registUserSetValidator'));
	}
	
	function registUserValidate($actionForm) {
		if (include_once(XOOPS_TRUST_PATH.'/modules/d3forum/class/D3forumAntispamJapanese.class.php')) {
			$this->loadLang();
			$validator = new D3forumAntispamJapanese();
			if ( !$validator->checkValidate() ) {
				$actionForm->addErrorMessage(_MD_D3FORUM_ERR_JAPANESEINCORRECT);
			}
		}
	}
	
	function registUserSetValidator(&$validators) {
		if (include_once(XOOPS_TRUST_PATH.'/modules/d3forum/class/D3forumAntispamJapanese.class.php')) {
			$this->loadLang();
			$validator = new D3forumAntispamJapanese();
			$html = $validator->getHtml4Assign();
			$html = $html['html_in_form'];
			list($caption, $element) = explode('</label>', $html);
			$validators[] = array(
				'caption' => $caption.'</label>',
				'element' => $element
			);
		}
	}
	
	function loadLang() {
		$langmanpath = XOOPS_TRUST_PATH.'/libs/altsys/class/D3LanguageManager.class.php' ;
		if( ! file_exists( $langmanpath ) ) die( 'install the latest altsys' ) ;
		require_once( $langmanpath ) ;
		$langman =& D3LanguageManager::getInstance() ;
		$langman->read( 'main.php' , $this->mydirname , 'd3forum' ) ;
	}
}
