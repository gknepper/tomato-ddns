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

                echo ('<form action="dns.php" method="post">');        
                echo ('<label class="heading">Select a entry to be deleted:</label><br>');
                                                       
                                                                                           
                foreach(scandir($directory) as $item){                                     
                        if (!($item == '.')) {                                             
                                if (!($item == '..')) {                                    
                                         if (!($item == 'reload.dnsmasq')) {                 
                                                //echo ("<p><span>&#8226;</span>     ".$item."<br>");                                 
                                                if (!($item == 'hosts')) {                                                                                             
                                                        echo ("<input type='checkbox' name='check_list[]' value=".$item."><label>".$item."</label> &nbsp;&nbsp;&nbsp;&nbsp; ");
                                                } else {                                                                                     
                                                        echo ("<p><span>&#8226;</span>     ".$item."<br>");                               
                                                }                                                                                         
                                                                       
                                                $file = "$directory/$item";
                                                $myfile = fopen($file, "r");
                                                                       
                                                while(!feof($myfile)) {                       
                                                  echo  "<small>"  .  fgets($myfile) . "</small><br>";
                                                }                      
                                                        
                                                        
                                                fclose($myfile);
                                                        
                }}}}                                                         
                echo ('<input type="submit" name="submit" Value="Delete"/>');
                               
                echo ('</h2>');
        }
                                             
        echo ('<h2>');                                    
                                                                         
        if(isset($_POST['submit'])){                                         
                        if(!empty($_POST['check_list'])) {                                             
                                                                                                       
                                // Counting number of checked checkboxes.                              
                                $checked_count = count($_POST['check_list']);                                       
                                echo "Following ".$checked_count." entries were deleted: <br/>";   
                                                                                                                                                     
                                // Loop to store and display values of individual checked checkbox.                                                  
                                foreach($_POST['check_list'] as $selected) {                                                                         
                                        echo "<p><span>&#8226;</span>".$selected."</p>";                                                             
                                        unlink( $directory . '/' . $selected  );                                                                                           
                                }                                                                                                                                          
                                                                                                                                                                           
                                //create reload.dnsmasq file                                                                                                               
                                $runReload = fopen("$directory/reload.dnsmasq",'w');                                                                                       
                                fclose($runReload);                                                                                                                        
                                                                                                                                                                           
                                echo "<br/><b>Note :</b> <span>In a few moments the dnsmasq will be reloaded and the entries above will not be responding anymore.</span>";
                                                                                             
                                echo "<meta http-equiv='refresh' content='3' />";            
                                                                                             
                                } else {                                                     
                                echo "<b>Please select atleast one entry to be deleted.</b>";
                                }
                        }
                       
        echo ('</h2>');
  
?>
