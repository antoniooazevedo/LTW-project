<?php
    declare(strict_types=1);
    class Upload {
        static public function uploadFile(string $username, bool $pfp) : ?array{
            $path_to_save = '/../uploads/';
            $upload_dir = __DIR__ . $path_to_save;
            $image_extentions = [ "ase", "art", "bmp", "blp", "cd5", "cit", "cpt", "cr2", "cut", "dds", "dib", "djvu", "egt", "exif", "gif", "gpl", "grf", "icns", "ico", "iff", "jng", "jpeg", "jpg", "jfif", "jp2", "jps", "lbm", "max", "miff", "mng", "msp", "nef", "nitf", "ota", "pbm", "pc1", "pc2", "pc3", "pcf", "pcx", "pdn", "pgm", "PI1", "PI2", "PI3", "pict", "pct", "pnm", "pns", "ppm", "psb", "psd", "pdd", "psp", "px", "pxm", "pxr", "qfx", "raw", "rle", "sct", "sgi", "rgb", "int", "bw", "tga", "tiff", "tif", "vtf", "xbm", "xcf", "xpm", "3dv", "amf", "ai", "awg", "cgm", "cdr", "cmx", "dxf", "e2d", "egt", "eps", "fs", "gbr", "odg", "svg", "stl", "vrml", "x3d", "sxd", "v2d", "vnd", "wmf", "emf", "art", "xar", "png", "webp", "jxr", "hdp", "wdp", "cur", "ecw", "iff", "lbm", "liff", "nrrd", "pam", "pcx", "pgf", "sgi", "rgb", "rgba", "bw", "int", "inta", "sid", "ras", "sun", "tga", "heic", "heif"];
            $text_file_extentions = ["txt", "doc", "docx", "odt", "pdf", "rtf", "tex", "wks", "wps", "wpd"];
            $accept = [];
            
            if ($pfp){
                $upload_dir  = $upload_dir . 'pfp/';
                $path_to_save = $path_to_save . 'pfp/';
                $files_array = $_FILES['pfp'];
                $accept = $image_extentions;
            }
            else{
                $files_array = $_FILES['documents'];
                $accept = array_merge($image_extentions, $text_file_extentions);
            }

            
            $error = $files_array['error'];
            if ($error == UPLOAD_ERR_NO_FILE) exit('No file provided');
            
            $user_dir = $upload_dir . $username . '_'; 
            
            $file_paths = array();
            
            if ($pfp){
                if ($error == UPLOAD_ERR_OK){
                    if (in_array(pathinfo($files_array['name'], PATHINFO_EXTENSION), $accept) === false) die(header('Location: /../pages/profile.php?error=invalid_file_type'));
                    $upload_file = $user_dir . basename($files_array['name']);
                    
                    $upload_file = Upload::newName($upload_file);

                    $tmp_name =$files_array['tmp_name'];
                    move_uploaded_file($tmp_name, $upload_file);
                    $file_paths[0] = $path_to_save . $username . '_' . basename($files_array['name']);
                }
            }
            else{
                $error = '';
                foreach ($files_array['error'] as $key => $error){
                    if (in_array(pathinfo($files_array['name'][$key], PATHINFO_EXTENSION), $accept) === false){
                        continue;
                    }
                    if ($error == UPLOAD_ERR_OK){
                        $upload_file = $user_dir . basename($files_array['name'][$key]);
                        
                        $upload_file = Upload::newName($upload_file);
                        
                        $tmp_name =$files_array['tmp_name'][$key];
                        move_uploaded_file($tmp_name, $upload_file);
                        $file_paths[$key] = $path_to_save . $username . '_' . basename($files_array['name'][$key]);
                    }
                }
            }
            return $file_paths;
        }

        static function newName($fullpath) {
            $path = dirname($fullpath);
            if (!file_exists($fullpath)) return $fullpath;
            $fnameNoExt = pathinfo($fullpath,PATHINFO_FILENAME);
            $ext = pathinfo($fullpath, PATHINFO_EXTENSION);
          
            $i = 1;
            while(file_exists("$path/$fnameNoExt($i).$ext")) $i++;
            return "$path/$fnameNoExt($i).$ext";
          }
    }
?>