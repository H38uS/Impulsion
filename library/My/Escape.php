<?php

class My_Escape
{
    public function escapeHTML($var) {
        $var = str_replace("&", "&amp;", $var);
        $var = str_replace("<", "&lt;", $var);
        $var = str_replace(">", "&gt;", $var);
        $var = str_replace("\n", "<br/>", $var);       
        return $var;
    }
}

?>
