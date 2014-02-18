<?php

if (!defined('XOOPS_ROOT_PATH')) exit();

class userRegistAntispamJapanese extends XCube_ActionFilter
{
	// 言語定数オーバーライドシステムを利用する場合には適切にディレクトリ名を指定する
	private $mydirname = 'd3forum';
	
	// 使用する辞書ファイル (空文字は d3forum 標準) d3forum 0.88.3 以降で有効
	// 絶対パス、またはファイル名のみ。ファイル名のみの場合は XOOPS_TRUST_PATH/modules/d3forum/class をディレクトリとする
	private $dicFile = '';
	
	// キャプション (空文字は d3forum 標準)
	private $caption = '';
	
	// 認証エラー時のメッセージ (空文字は d3forum 標準)
	private $error = '';
	
	function postFilter() {
		$this->mRoot->mDelegateManager->add('Legacy.Event.RegistUser.Validate', array($this, 'registUserValidate'));
		$this->mRoot->mDelegateManager->add('Legacy.Event.RegistUser.SetValidators', array($this, 'registUserSetValidator'));
	}
	
	function registUserValidate($actionForm) {
		if (include_once(XOOPS_TRUST_PATH.'/modules/d3forum/class/D3forumAntispamJapanese.class.php')) {
			$this->loadLang();
			$validator = new D3forumAntispamJapanese();
			$validator->setVar('dictionary_file', $this->dicFile);
			if ( !$validator->checkValidate() ) {
				$actionForm->addErrorMessage( $this->error? $this->error : _MD_D3FORUM_ERR_JAPANESEINCORRECT );
			}
		}
	}
	
	function registUserSetValidator(&$validators) {
		if (include_once(XOOPS_TRUST_PATH.'/modules/d3forum/class/D3forumAntispamJapanese.class.php')) {
			$this->loadLang();
			$validator = new D3forumAntispamJapanese();
			$validator->setVar('dictionary_file', $this->dicFile);
			$html = $validator->getHtml4Assign();
			$html = $html['html_in_form'];
			list($caption, $element) = explode('</label>', $html);
			if ($this->caption) {
				$caption = preg_replace('/(<label for="antispam_yomigana">).*:/s', '$1'.$this->caption.':', $caption);
			}
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
