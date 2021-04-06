# tomato-ddns
A awful implementation of DDNS to be used in a tomato router for dynamic DNS registration on a LAN.   

Inspired by: https://yingtongli.me/blog/2017/04/18/dynamic-dns.html


## The problem:

I tried:

Manual insertion of DNS registries but well... breaks the automation. Same for hosts files as well. 

zeroconf/avahi/bonjour:
- works only for FQDN ( hostname.local ) due a secure limitation on avahi implementation for linux so hostnames are not solved.
- Containers don't recognise the .local unless the necessary dependencies are present within.


Record all pool of IPs used in advance:
- In my tomato router but I'm using rancher with a vmware node driver where the vms are dinamically provisioned and the hostnames are not predictable.




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

2. The dns.php reads the parameters and write as a hosts file inside  /etc/dnsmasq/hosts directory. Another file called reload.dnsmasq is also created in order to trigger the reload of dnsmasq daemon and read the new entry.
3. A schedulled job is set on tomato to run the reload: When the file reload.dnsmasq is found the dnsmasq is reloaded and erased.
```
[ -e "/tmp/etc/dnsmasq/hosts/reload.dnsmasq" ] && service dnsmasq restart&&rm -rf /tmp/etc/dnsmasq/hosts/reload.dnsmasq
```
4. The new host and fqdn will be working in the host using the tomato as a DNS server. 


# TO-DO
- Integrate on Advanced Tomato
- Implement a manner to delete entries
- Implement a server side IP/scope/netmask restriction
- Implement a server side domain restriction 


