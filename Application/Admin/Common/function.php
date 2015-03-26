<?php
function remove_directory($dir) {
    if($handle = opendir("$dir")) {
        while(false !== ($item = readdir($handle))) {
            if($item != "." && $item != "..") {
                if(is_dir("$dir/$item")) {
                    remove_directory("$dir/$item");
                } else {
                    unlink("$dir/$item");
                    //echo"removing $dir/$item<br>";
                }
            }
        }
        closedir($handle);
        rmdir($dir);
        //echo"removing $dir<br>";
    }
}

function remove_cache() {
    remove_directory('Application/Runtime');
}
