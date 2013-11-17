<?php

/**
 *
 */
class GalerieController extends Zend_Controller_Action {
    /**
     * Attention au chemin : pas de ROOT_TO_PUBLIC pour le moment
     */

    const PATH_TO_PHOTO = "image/galerie_photo/";

    /**
     *  encode ou non le string, en fonction de linux ou windows
     */
    private function encode($str) {
        if (gettype(strpos(php_uname("s"), "Window")) == "integer") {
            return utf8_encode($str);
        } else {
            return $str;
        }
    }

    /**
     * decode ou non le string, en fonction de linux ou windows
     */
    private function decode($str) {
        if (gettype(strpos(php_uname("s"), "Window")) == "integer") {
            return utf8_decode($str);
        } else {
            return $str;
        }
    }

    public function getRepPhoto() {
        $dir = opendir(self::PATH_TO_PHOTO);
        if ($dir) {
            $i = 0;
            while ($file = readdir($dir)) {
                if (is_dir(self::PATH_TO_PHOTO . $file) && !in_array($file, array(".", ".."))) {
                    $liste_rep[$i] = $this->encode($file);
                    $i++;
                }
            }
            closedir($dir);
        }
        if (isset($liste_rep)) {
            return $liste_rep;
        } else {
            return false;
        }
    }

    public function indexAction() {
        if ($this->getRepPhoto()) {
            $this->view->rep_photo = $this->getRepPhoto();
        }
       $elem_photos = $this->getRandomPhoto("");
       $this->view->init_photo = $this->getPathXLFromRandom($elem_photos);
       $this->view->init_photo_small = $this->getPathMiniatureFromRandom($elem_photos);
    }

    // retourne les sous dossiers pr�sents dans l'argument, si c'est un dossier
    // ne prend pas en compte le dossier courant (.) et le dossier du dessus (..).
    public function getSousDossier($rep) {
        $dir = @opendir(self::PATH_TO_PHOTO . $rep);
        $i = 0;
        if ($dir) {
            while ($file = readdir($dir)) {
                if (is_dir(self::PATH_TO_PHOTO . $rep . $file) && $file != "." && $file != "..") {
                    $sousreps[$i] = $this->encode($file);
                    $i++;
                }
            }
            closedir($dir);
        }
        if (!isset($sousreps))
            return false;
        return $sousreps;
    }

    /**
     *
     * @param string $photo  
     * @return true si c'est une miniature, false sinon
     */
    public function isMiniature($photo) {
        $pattern = "/^_small_/";
        return preg_match($pattern, $photo) === 1;
    }

    // retourne les fichiers présents dans le dossier, si c'en est un
    public function getPhotos($rep) {
        $dir = @opendir(self::PATH_TO_PHOTO . $rep);
        if ($dir) {
            while ($photo = readdir($dir)) {
                if (!is_dir(self::PATH_TO_PHOTO . $rep . $photo)) {
                    if ($this->isMiniature($photo)) {
                        $photos[$this->encode($photo)]['content'] = $this->encode(ROOT_TO_PUBLIC . "/" . self::PATH_TO_PHOTO . $rep . $photo);
                        $ret = getimagesize(self::PATH_TO_PHOTO . $rep . $photo);
                        if ($ret)
                            $photos[$this->encode($photo)]['larghaut'] = $ret[3];
                    }
                }
            }
            closedir($dir);
        }
        if (!isset($photos))
            return false;
        return $photos;
    }

    public function dossierAction() {
        // liste des dossiers
        if ($this->getRepPhoto()) {
            $this->view->rep_photo = $this->getRepPhoto();
        }

        $rep = $this->decode($this->_request->getParam("folder")) . "/";
        $sousrep = $this->decode($this->_request->getParam("subfolder")) . "/";

        if ($sousrep != "/") {
            $photos = $this->getPhotos($rep . $sousrep);
            if (!empty($photos)) {
                $this->view->photos = $photos;
            }
        }
        
        if ($rep != "/") {
            $this->view->sousdossier = $this->getSousDossier($rep);
            if ($sousrep == "/") {
                // photo aléatoire
                $elem_photos = $this->getRandomPhoto($this->decode($this->_request->getParam("folder")));
                $this->view->init_photo = $this->getPathXLFromRandom($elem_photos);
                $this->view->init_photo_small = $this->getPathMiniatureFromRandom($elem_photos);
            }
        }
        $this->view->currentfolder = $this->_request->getParam("folder");
        $this->view->currentsubfolder = $this->_request->getParam("subfolder");
    }

    public function getnextimageAction() {
        $this->_helper->layout->disableLayout();
        $last = $this->_request->getParam("last");
        $folder = str_replace("\\", "", $this->decode(trim($this->_request->getParam("folder"))));
        $photo = $this->getRandomPhoto($folder);//str_replace("\\", "", ROOT_TO_PUBLIC . "/" . $this->getRandomPhoto($folder));
        $counter = 0;
        while ($last == ROOT_TO_PUBLIC . "/" . $photo) {
            $counter++;
            if ($counter == 500)
            // au cas ou une seule photo est présente
                break;
            $photo = $this->getRandomPhoto($folder);
        }
        if ($counter != 500) {
            $small = $this->getPathMiniatureFromRandom($photo);
            $big = $this->getPathXLFromRandom($photo);
            echo $small . "PATTERNACAUSEJSONDESARACE" . $big;
        }
    }
    
    public function getPathXLFromRandom($params) {
        return ROOT_TO_PUBLIC . "/" . $params[0] . "/" . $params[1] . "/" . $params[2];
    }
    
    public function getPathMiniatureFromRandom($params) {
        return ROOT_TO_PUBLIC . "/" . $params[0] . "/" . $params[1] . "/_small_" . $params[2];
    }
    
    /**
     *
     * @param  String           $dossier   Le dossier dans lequel il faut chercher
     * @return Array/Boolean               False s'il n'existe pas de photos, ou un array avec 0 => dossier,
     *                                     1 => sous dossier, 2 => photo
     */
    public function getRandomPhoto($dossier) {  
        
        $dir = opendir(self::PATH_TO_PHOTO);
        if ($dir) {
            // listing of possible directories
            $indice = 1;
            while ($folder = readdir($dir)) {
                if (is_dir(self::PATH_TO_PHOTO . $folder) && $folder != "." && $folder != "..") {
                    if ($dossier != "" && $folder != $dossier)
                        continue;
                    $subfolders = opendir(self::PATH_TO_PHOTO . $folder);
                    if ($subfolders) {
                        while ($subfolder = readdir($subfolders)) {
                            if (is_dir(self::PATH_TO_PHOTO . $folder . "/" . $subfolder)
                                    && $subfolder != "." && $subfolder != "..") {
                                $liste_rep[$indice] = self::PATH_TO_PHOTO . $folder . "/" . $subfolder . "/";
                                $indice++;
                            }
                        }
                        closedir($subfolders);
                    }
                }
            }

            $indice = 1;
            if (isset($liste_rep)) {
                foreach ($liste_rep as $rep) {
                    $photo_dir = opendir($rep);
                    if ($photo_dir) {
                        while ($photo = readdir($photo_dir)) {
                            if (!is_dir($photo) && !$this->isMiniature($photo)) {
                                $photos[$indice] = $this->encode($rep . $photo);
                                $indice++;
                            }
                        }
                        closedir($photo_dir);
                    }
                }
            }
            closedir($dir);
        } else {
            echo "not dir";
        }
        
        if (isset($photos)) {
            $selected_photo_nb = rand(1, count($photos));
            $dirs = explode("/", $photos[$selected_photo_nb]);
            $nb = count($dirs);
            if ($nb < 3) return false;            
            $selected_dir = $dirs[0];
            for ($i=1; $i<$nb-2; $i++)
                $selected_dir = $selected_dir . "/" . $dirs[$i];
            $selected_subdir = $dirs[$nb-2];
            $selected_photo = $dirs[$nb-1];
            return array($selected_dir, $selected_subdir, $selected_photo);
        }
        return false;
    }

}