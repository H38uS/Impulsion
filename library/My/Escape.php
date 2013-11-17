<?php

class My_Escape
{
    public function escapeHTML($var) {
        $var = str_replace("&", "&amp;", $var);
        $var = str_replace("<", "&lt;", $var);
        $var = str_replace(">", "&gt;", $var);
        return $var;
    }
}

?>
