<?php
        header('Content-Type: text/plain');
        $ip = $_GET['ip'];
        $hostname = $_GET['hostname'];
        $fqdn = $_GET['fqdn'];


        $directory = "/tmp/etc/dnsmasq/hosts/";


        if (isset($ip,$hostname,$fqdn)) {
                $file = "$directory/$ip";
                $current = "$ip     $hostname     $fqdn";


                 //create the hosts file
                 $nhost = fopen($file,'w');
                 fwrite($nhost,$current);
                 fclose($nhost);


                 //create reload.dnsmasq file
                 $runReload = fopen("$directory/reload.dnsmasq",'w');
                 fclose($runReload);

        } else {
                header('Content-Type: text/html; charset=utf-8');

                echo ('<h2>');

                foreach(scandir($directory) as $item){
                        if (!($item == '.')) {
                                if (!($item == '..')) {
                                        echo ("<p><span>&#8226;</span>     ".$item."<br>");


                                        $file = "$directory/$item";
                                        $myfile = fopen($file, "r");

                                        while(!feof($myfile)) {
                                          echo fgets($myfile) . "<br>";
                                        }


                                        fclose($myfile);

                }}}
                echo ('</h2>');
        }


?>
