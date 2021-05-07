# tomato-ddns
A awful implementation of DDNS to be used in a tomato router for dynamic DNS registration on a LAN.   

Inspired by: https://yingtongli.me/blog/2017/04/18/dynamic-dns.html


## The problem:

I tried:

Manual insertion of DNS registries but well... breaks the automation. Same for hosts files as well. 

zeroconf/avahi/bonjour:
- Works only for FQDN ( hostname.local ) due a secure limitation on avahi implementation for linux so hostnames are not solved.
- Containers don't recognise the .local unless the necessary dependencies are present within.


Record all pool of IPs used in advance:
- Set records in advance it's an viable solution when you knows the names in advance. I'm using Rancher with a vmware node driver where the vms are dinamically provisioned and the hostnames are not predictable.




# Implementation:

Tomato by default uses a setting to extend the hosts into the /etc/dnsmasq/hosts directory: the "addn-hosts=/etc/dnsmasq/hosts" where tomato store a hosts file. Every file created inside this directory will be treated as a aditional hosts file. 


1. In order to create a DNS entry a simple curl can be used trigger the process: 
```
curl  "http://192.168.1.1:8080/dns.php?ip=192.168.1.224&hostname=playstation4&fqdn=playstation4.home" 
```
Parameters:
- ip=192.168.1.224
- hostname=playstation4
- fqdn=playstation4.home

![image](https://user-images.githubusercontent.com/14863517/113658584-290c7180-9655-11eb-925e-7b5c81fabb58.png)


2. The dns.php reads the parameters and write as a hosts file inside  /etc/dnsmasq/hosts directory. Another file called reload.dnsmasq is also created in order to trigger the reload of dnsmasq daemon and read the new entry.


4. A schedulled job is set on tomato to run the reload: When the file reload.dnsmasq is found the dnsmasq is reloaded and erased.
```
cp /jffs/www/dhosts/* /tmp/etc/dnsmasq/hosts/
rm -rf /tmp/etc/dnsmasq/hosts/reload.dnsmasq
[ -e "/tmp/reload.dnsmasq" ] && service dnsmasq restart&&rm -rf /tmp/reload.dnsmasq
```
![image](https://user-images.githubusercontent.com/14863517/113658493-011d0e00-9655-11eb-882e-c7bcafa12702.png)

4. The new host and fqdn will be working in the host using the tomato as a DNS server. 





# TO-DO
- Integrate on Advanced Tomato
- Implement a server side IP/scope/netmask restriction
- Implement a server side domain restriction 


# KNOWN BUGS
 - Create a check to the delete the old entry when the old name is reused. ( I'm manually handling that for now )
 - Persistance using a external storage ( I'm managing for the moment by using JFFS - CAUTION WEARS OUT THE INTERNAL FLASH STORAGE ) 
