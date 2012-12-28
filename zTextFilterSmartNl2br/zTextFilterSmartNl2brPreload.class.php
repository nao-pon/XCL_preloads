<?php
if (XC_CLASS_EXISTS('Hyp_TextFilter')) {

class TextFilterSmartNl2br extends Hyp_TextFilter {}

} else {

class TextFilterSmartNl2br extends Legacy_TextFilter
{
	function toShowTarea($text, $html = 0, $smiley = 1, $xcode = 1, $image = 1, $br = 1, $x2comat=false) {
		$text = $this->preConvertXCode($text, $xcode);
		if ($html != 1) $text = $this->toShow($text, $x2comat);
		$text = $this->makeClickable($text);
		if ($smiley != 0) $text = $this->smiley($text);
		if ($xcode != 0) $text = $this->convertXCode($text, $image);
		if ($br != 0) $text = $this->nl2Br($text, $html);
		$text = $this->postConvertXCode($text, $xcode, $image);
		return $text;
	}
}

}

class zTextFilterSmartNl2brPreload extends XCube_ActionFilter
{
	function preFilter() {
		$this->mController->mSetupTextFilter->add('zTextFilterSmartNl2br::getInstance', XCUBE_DELEGATE_PRIORITY_NORMAL);
	}
}

class zTextFilterSmartNl2br extends TextFilterSmartNl2br
{
    function getInstance(&$instance) {
        if (empty($instance)) {
            $instance = new zTextFilterSmartNl2br();
        }
    }

	function nl2Br($text, $html = 0)
	{
		static $filter = array();
		if ($html) {
			if (! $filter) {
				$filter['td_nl2br'] = create_function('$m', 'return $m[1].nl2br($m[2]).$m[3];');
				$filter['ignore_part'] = create_function('$m', 'return $m[1].preg_replace(\'/\r\n|[\r\n]/\', "\\x01" , $m[3]).$m[4];');
			}
			$text = preg_replace_callback('#(<td[^>]*>)(?:\r\n|[\r\n])?(.+?)(?:\r\n|[\r\n])?(</td>)#is', $filter['td_nl2br'], $text);
			$text = preg_replace_callback('#(?:\r\n|[\r\n])(<(table|script|object|style)[^>]*>)(.+?)(</\\2>)(?:\r\n|[\r\n])?#is', $filter['ignore_part'], $text);
			return str_replace("\x01", "\n", nl2br($text));
		}
		return nl2br($text);
	}
}

