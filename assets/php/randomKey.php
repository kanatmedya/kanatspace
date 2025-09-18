<?php
function generateRandomKey($length = 32) {
    // Cryptographically secure pseudorandom bytes
    $randomBytes = openssl_random_pseudo_bytes($length);
    
    // Convert bytes to a hex string
    $randomKey = bin2hex($randomBytes);
    
    // Ensure the length of the key is as desired
    if (strlen($randomKey) > $length) {
        $randomKey = substr($randomKey, 0, $length);
    }
    
    return $randomKey;
}

?>