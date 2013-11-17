<?php

/**
 *
 * @author Jordan Mosio
 */
class My_Helper_SmallToBig {
    
    public function smallToBig($photo) {
        return str_replace("_small_", "", $photo);
    }
}

?>
