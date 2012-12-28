# zTextFilterSmartNl2br

Legacy_TextFilter の自動改行をちょっとだけ賢くする XCL プリロード

html/preload ディレクトリに [zTextFilterSmartNl2brPreload.class.php](https://github.com/nao-pon/XCL_preloads/blob/master/zTextFilterSmartNl2br/zTextFilterSmartNl2brPreload.class.php) を配置すると、`<table>`(`<td>`内を除く), `<object>`, `<script>`, `<style>` 内では 改行を `<br />` に置換しないようにしないようになります。
ただし、入れ子<tabel> は考慮していません。

FaceBook の次の話題から作成しました。

https://www.facebook.com/groups/xoops.creators/permalink/506974502676293/
