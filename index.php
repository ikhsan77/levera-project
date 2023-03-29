<?php
error_reporting(0);
    function curlon($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
        // $result = curl_exec($ch);
        $result = file_get_contents($url);
        curl_close($ch);

        // Text
        if (preg_match("/smm/i", $url)) $txt = "[ S ] ";
        else if (preg_match("/ppob/i", $url)) $txt = "[ P ] ";
        else if (preg_match("/games/i", $url)) $txt = "[ G ] ";

        // Text Color
        $White  = "\e[0;37m";

        if ($result == "Order Pending not found.") {
            return "";
        } else {

            // Logika Penjumlahan
            $w = 1;
            if (count(explode("<br />", $result)) >= 3) $w = 2;

            if (preg_match("/smm/i", $url)) $jml = count(explode("<br />", $result)) - $w;
            else $jml = (count(explode(" Web: ", $result)) - 1);
            
            if ($jml == 2 && $url !== "https://levera-pay.com/sinc/status/smm") $jml = $jml / 2;
            else if (count(explode("<br />", $result)) == 2 && $url == "https://levera-pay.com/sinc/status/smm") $jml = $jml / 2;
            
            $a = 1;
            $b = 1;
            if ($url == "https://levera-pay.com/sinc/status/smm") $a = 0;

            while ($a <= $jml) {
                // Status Orderan
                if (preg_match("/smm/i", $url)) $st = explode(" status ", explode(" | ", explode("<br />", $result)[$a])[0])[1]; 
                else $st = explode("<br />", explode("Status: ", $result)[$a])[0];

                // Coloring
                if ($st == "Error") $warna = "\e[0;31m";
                else if ($st == "Success") $warna = "\e[0;32m";
                else if ($st == "Pending") $warna = "\e[0;33m";
                else if ($st == "Processing") $warna = "\e[1;34m";
                
                // Exec
                if (preg_match("/smm/i", $url)) {
                    $id = explode(" status ", explode("<br />", $result)[$a])[0];
                } else {
                    $id = explode("<br />", explode(" Web: ", $result)[$a])[0];
                }

                echo $txt.$warna.$id.$White."\n";
                $a++;
                $b++;
            }
        }

    }

    reg:
    echo curlon("https://levera-pay.com/sinc/status/smm");
    sleep(1);

    echo curlon("https://levera-pay.com/sinc/status/ppob");
    sleep(1);
    
    echo curlon("https://levera-pay.com/sinc/status/games");
    sleep(1);

    goto reg;
?>