<?php
function convertRichText($text) {
    // URL'leri tıklanabilir hale getiren düzenli ifade
    $pattern = '/(https?:\/\/[^\s]+)/';
    
    // URL'yi <a> etiketi içine sarar
    $replacement = '<a class="richLink" href="$1" target="_blank">$1</a>';
    
    // Düzenli ifade ile metin içindeki URL'leri dönüştür
    $text = preg_replace($pattern, $replacement, $text);
    
    return $text;
}
?>
