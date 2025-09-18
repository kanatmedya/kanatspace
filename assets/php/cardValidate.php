<?php
    function validatecard($cardnumber) {
        $cardnumber=preg_replace("/\D|\s/", "", $cardnumber);  # strip any non-digits
        $cardlength=strlen($cardnumber);
        $parity=$cardlength % 2;
        $sum=0;
        for ($i=0; $i<$cardlength; $i++) {
          $digit=$cardnumber[$i];
          if ($i%2==$parity) $digit=$digit*2;
          if ($digit>9) $digit=$digit-9;
          $sum=$sum+$digit;
        }
        $valid=($sum%10==0);
        return $valid;
    }
     
    function check_cc($cc, $extra_check = false){
        $cards = array(
            "visa" => "(4\d{12}(?:\d{3})?)",
            "amex" => "(3[47]\d{13})",
            "jcb" => "(35[2-8][89]\d\d\d{10})",
            "maestro" => "((?:5020|5038|6304|6579|6761)\d{12}(?:\d\d)?)",
            "solo" => "((?:6334|6767)\d{12}(?:\d\d)?\d?)",
            "mastercard" => "(5[1-5]\d{14})",
            "switch" => "(?:(?:(?:4903|4905|4911|4936|6333|6759)\d{12})|(?:(?:564182|633110)\d{10})(\d\d)?\d?)",
        );
        $names = array("Visa", "American Express", "JCB", "Maestro", "Solo", "Mastercard", "Switch");
        $matches = array();
        $pattern = "#^(?:".implode("|", $cards).")$#";
        $result = preg_match($pattern, str_replace(" ", "", $cc), $matches);
        if($extra_check && $result > 0){
            $result = (validatecard($cc))?1:0;
        }
        return ($result>0)?$names[sizeof($matches)-2]:false;
    }

?>