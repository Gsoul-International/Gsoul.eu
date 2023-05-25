<?php

class DBimages extends Model {
    public function __construct() {
        $this->setTable('images');
        $this->setPrimaryKey('idi');
    }

    public function AddSizes($filename = '') {
        $sizes = $this->kernel->imagesSizes;
        $paths = array();
        foreach ($sizes as $ks => $vs) {
            $paths[$ks] = str_replace('xxxx', $vs, $filename);
        }
        return $paths;
    }

    public function CreateImage($file, $category = 0, $nazev = '', $popis = '') {
        if ($file["error"] > 0) {
            return false;
        }

        $pripony = explode(',', $this->kernel->settings['povolene_pripony_obrazku']);
        $sizes = $this->kernel->imagesSizes;
        $original_name = explode('.', $file["name"]);
        $suffix = strtolower(end($original_name));

        if (!in_array($suffix, $pripony)) {
            return false;
        }

        if (filesize($file["tmp_name"]) > (str_replace('B', '', ini_get('upload_max_filesize')) * 1048576)) {
            return false;
        }

        $category = (int)$category;
        $nazev = prepare_get_data_safely($nazev);
        $popis = prepare_get_data_safely($popis);
        $idi = $this->store(0, array('vytvoreni_timestamp' => time(), 'id_ic' => $category, 'nazev' => $nazev, 'popis' => $popis));

        if ($idi > 0) {
            @mkdir('userfiles/images/'.$category.'/'.$idi, 0777);
        } else {
            return false;
        }

        $suffix = 'png';

        if (move_uploaded_file($file["tmp_name"], 'userfiles/images/'.$category.'/'.$idi.'/_.'.$suffix)) {
            $this->RegeneratePreviews('userfiles/images/'.$category.'/'.$idi.'/_.'.$suffix);
            $this->store($idi, array('cesta' => 'userfiles/images/'.$category.'/'.$idi.'/xxxx.'.$suffix));
            return $idi;
        } else {
            rmdir('userfiles/images/'.$category.'/'.$idi);
            $this->deleteId($idi);
            return false;
        }
    }

    public function RegeneratePreviews($mainPath = '') {
        $sizes = $this->kernel->imagesSizes;
        foreach ($sizes as $s) {
            $wh = explode('x', $s);
            $mainPath2 = str_replace('_', $s, $mainPath);
            $this->CreateThumb($mainPath, $mainPath2, $wh[0], $wh[1]);
        }
    }

    public function CreateThumb($path1 = '', $path2 = '', $w = 3000, $h = 3000) {
        $phpThumb = new MHMthumb();
        $is = $phpThumb->thumb($path1, $path2, $w, $h, true, 0, 0, 0);  //235,218,245
        if ($is == false) {
            echo 'Image Error.';
            die;
        }
        return true;
    }

    public function DeleteImage($idi = 0) {
        $idi = (int)$idi;
        if ($idi < 1) {
            return false;
        }
        $idi_path = trim($this->getOne('cesta', 'WHERE idi="'.$idi.'"'));
        $idi_cat = trim($this->getOne('id_ic', 'WHERE idi="'.$idi.'"'));
        if ($idi_path != '') {
            $paths = $this->AddSizes($idi_path);
            foreach ($paths as $p) {
                @unlink($p);
            }
            @unlink(str_replace('xxxx', '_', $idi_path));
            @rmdir('userfiles/images/'.$idi_cat.'/'.$idi);
        }
        $this->deleteId($idi);
        return true;
    }
}
