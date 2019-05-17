request lib:
https://github.com/WiringPi/WiringPi
https://github.com/technion/lol_dht22 (included as bin)

1. Enable UART, and disable serial login console via 
	raspi-config

2. Update repository
	apt-get update

	3. Install flow packages
	apt-get install mysql-server
	apt-get install apache2
	apt-get install php
	apt-get install phpmyqdmin (if you want)

4. Create DataBase

5. Add in /boot/config.txt lines:
	dtoverlay=w1-gpio,gpiopin=17