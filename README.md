# tomato-ddns
A awful implementation of DDNS to be used in a tomato router for dynamic DNS registration on a LAN.   


## The problem:

I tried:

Manual insertion of DNS registries but well... breaks the automation. Same for hosts files as well. 

zeroconf/avahi/bonjour
  1 - works only for FQDN ( hostname.local ) due a secure limitation on avahi implementation for linux so hostnames are not solved.
  2 - Containers don't recognise the .local unless the necessary dependencies are present within.


Record all pool of IPs used in advance in my tomato router but I'm using rancher with a vmware node driver where the vms are dinamically provisioned and the hostnames are not predictable.


# TO DO
- Integrate on Advanced Tomato
- Implement a manner to delete entries






