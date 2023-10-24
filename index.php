<?php

function array_make_first(&$array, $element) {
    if (($ndx = array_search($element, $array)) !== false) {
        unset($array[$ndx]);
        array_unshift($array, $element);
    }
}

$encler = mb_list_encodings();
natcasesort($encler);
array_make_first($encler, 'ISO-8859-9');
array_make_first($encler, 'UTF-8');
$varsayilanEncIn = 'iso-8859-9';
$varsayilanEncOut = 'utf-8';

if (isset($_FILES['dosya']) && $_FILES['dosya']['error'] == UPLOAD_ERR_OK && isset($_GET['y'])) {
    $icerik = file_get_contents($_FILES['dosya']['tmp_name']);
	if (isset($_POST['enc_in']) && $_POST['enc_in'] != '' && $_POST['enc_in'] != '__auto' && in_array($_POST['enc_in'], $encler)) {
		$enc = $_POST['enc_in'];
	} else {
	    $autoenc = $enc = mb_detect_encoding($icerik, 'utf-8, iso-8859-9', true);
	}
    if (strtolower($enc) != 'utf-8') {
        echo $enc;
        $icerik = mb_convert_encoding($icerik, 'utf-8', $enc);
    }
    $icerik = strtr($icerik, array(
		'â€¢' => '•',
		'â€œ' => '“',
		'â€' => '”',
		'â€˜' => '‘',
		'â€™' => '’',
		'Ý¾' => 'İ',
		'Ý' => 'İ',
		'Ä°' => 'İ',
		'Ã' => 'İ',
		'â€¹' => 'İ',
		'&Yacute;' => 'İ',
		'ý' => 'ı',
		'Ä±' => 'ı',
		'Â±' => 'ı',
		'Ã½' => 'ı',
		'Ã›' => 'ı',
		'â€º' => 'ı',
		'&yacute;' => 'ı',
		'Þ' => 'Ş',
		'Åž' => 'Ş',
		'Ã…Å¸' => 'Ş',
		'Ã¥Ã¿' => 'Ş',
		'&THORN;' => 'Ş',
		'þ' => 'ş',
		'Å?' => 'ş',
		'ÅŸ' => 'ş',
		'&thorn;' => 'ş',
		'Ð' => 'Ğ',
		'Äž' => 'Ğ',
		'ð' => 'ğ',
		'Ä?' => 'ğ',
		'ÄŸ' => 'ğ',
		'&eth;' => 'ğ',
		'Ã‡' => 'Ç',
		'Ã?' => 'Ç',
		'&Ccedil;' => 'Ç',
        'Ã§' => 'ç',
		'&ccedil;' => 'ç',
		'Ã–' => 'Ö',
		'&Ouml;' => 'Ö',
		'Ã¶' => 'ö',
		'&ouml;' => 'ö',
		'Ãœ' => 'Ü',
		'&Uuml;' => 'Ü',
		'ÃƒÂ¼' => 'ü',
		'Ã£Â¼' => 'ü',
		'Ã¼' => 'ü',
        '&uuml;' => 'ü',
        '&#39;' => "'",
        'Å' => "ş",
        'Ä' => "ğ",
        
	));
	
	if (isset($_POST['enc_out']) && $_POST['enc_out'] != '' && in_array($_POST['enc_out'], $encler)) {
		$icerik = mb_convert_encoding($icerik, $_POST['enc_out'], 'utf-8');
	}
	
	if ($_POST['tip'] == '2') {
		$lastDot = strrpos($_FILES['dosya']['name'], '.');
		if ($lastDot === false) {
			$ad = $_FILES['dosya']['name'].'_duzeltilmis';
		} else {
			$ad = substr($_FILES['dosya']['name'], 0, $lastDot).'_duzeltilmis'.substr($_FILES['dosya']['name'], $lastDot);
		}
		header("Content-Disposition: attachment; filename=\"".basename($ad)."\";" );
		echo $icerik;
		exit;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style type="text/css">
		h1 a, h1 a:visited{color:#09F;text-decoration:none;}
		h1 a:hover{text-decoration:underline;}
	</style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <title>Bozuk Türkçe Karakterli Dosya Düzelt</title>
</head>
<body>
    <h1><a href=".">Bozuk Türkçe Karakterli Dosya Düzelt (.sql, .html, ...)</a></h1>
    <form action="?y=1" method="post" enctype="multipart/form-data">
        <input type="file" name="dosya" /> &nbsp; &nbsp; 
        <select name="tip">
            <option value="1">Ekranda Göster</option>
            <option value="2">Farklı Kaydet</option>
        </select>
         &nbsp; &nbsp; 
        <label for="enc_in">Kodlama</label> <select id="enc_in" name="enc_in">
        <option value="__auto">Otomatik tespit et</option>
        <?php foreach ($encler as $v) { ?>
        	<option value="<?php echo htmlspecialchars($v)?>"<?php echo ((@$_POST['enc_in']==$v/*||$varsayilanEncIn==strtolower($v)*/)?' selected="selected"':''); ?>><?php echo htmlspecialchars($v)?></option>
        <?php } ?>
        </select>
         &nbsp; &nbsp; 
        <label for="enc_out">Çıktı Kodlaması</label> <select id="enc_out" name="enc_out">
        <?php foreach ($encler as $v) { ?>
        	<option value="<?php echo htmlspecialchars($v)?>"<?php echo ((@$_POST['enc_out']==$v||$varsayilanEncOut==strtolower($v))?' selected="selected"':''); ?>><?php echo htmlspecialchars($v)?></option>
        <?php } ?>
        </select>
         &nbsp; &nbsp; 
        <input type="submit" value="Yükle">
    </form>
    <?if ($icerik){ ?>
   		<br />
   		<?php if (isset($autoenc)) echo 'Tespit edilen girdi kodlaması: '.$autoenc.'<br />' ?>
        Sonuç :<br />
        <textarea id="textarea" cols="" rows="" readonly="readonly" wrap="soft" style="width: 1000px; height: 600px; font-family:monospace;white-space: pre;background:#fffff6"><?php echo htmlspecialchars(@$icerik); ?></textarea>
    <?} ?>
    <button onclick="copy()">Kopyala</button>

    <script>function copy() {
  let textarea = document.getElementById("textarea");
  textarea.select();
  document.execCommand("copy");
}</script>
</body>
</html>