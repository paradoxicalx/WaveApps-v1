Pengembangan dan dukungan untuk aplikasi ini telah dihentikan. Untuk mendapatkan informasi terkait versi terbaru dari aplikasi ini dapat menghubungi telegram https://t.me/DhedhyPoetra

# 1. Tentang WaveApps

Aplikasi yang digunakan untuk management jaringan RT/RW Net dengan beberapa fungsi diantaranya :
- Member Management
- Billing System
- Radius Server
- Network Management
- Maps
- Product
- Ticket
- Telnet/SSH Remote
- Hotspot

Aplikasi ini dibuat untuk digunakan pada jaringan yang menggunakan perangkat mikrotik dengan ROS versi <= 6.42. Karena sebagian besar pengaplikasian nya menggunakan ROS API dan snmp.

# 2. Pemasangan

### Web Server
> Install apache2, Mysql server, phpMyAdmin.

```bash
•	 apt-get update
•	 apt-get upgrade
•	 apt-get install apache2 mysql-server phpmyadmin git -y
```
> Buat Mysql user
```bash
•	 mysql -u root -p
•	 GRANT ALL PRIVILEGES ON *.* TO 'username'@'localhost' IDENTIFIED BY 'password';
```
> Install php 7.3 and modul yang dibutuhkan
```bash
•	 sudo apt install ca-certificates apt-transport-https
•	 wget -q https://packages.sury.org/php/apt.gpg -O- | sudo apt-key add -
•	 echo "deb https://packages.sury.org/php/ stretch main" | tee /etc/apt/sources.list.d/php.list
•	 apt-get update
•	 apt install php7.3
•	 apt install php7.3-cli php7.3-common php7.3-curl php7.3-mbstring php7.3-mysql php7.3-xml snmpd php7.3-snmp
•	 apt install php7.3-mysqlnd
```
> Matikan/disable PHP lama jika ada. Disini saya misalkan php versi 7.0
```bash
•	 sudo a2dismod php7.0
•	 sudo a2dismod mpm_prefork
•	 sudo a2dismod mpm_worker
•	 sudo a2dismod mpm_event
•	 sudo a2enmod php7.3
```
> Enable snmp servis dengan mengubah file : **/etc/php/7.3/apache2/php.ini**
```bash
...
extension=snmp
...
```
> Masuk ke direktori html dan download clone repo
```bash
•	cd /var/www/htlm
•	git clone https://github.com/paradoxicalx/WaveApps-old.git
```

### Let's Encrypt
> Tambahkan certbot repository 
```bash
•	 echo " deb http://download.webmin.com/download/repository sarge contrib" | tee /etc/apt/sources.list.d/php.list
•	 apt-get update
```
> Install certbot
```bash
•	 apt-get install certbot python-certbot-apache
•	 certbot --apache
```

### Shellinabox
> Install shellinabox
```bash
•	 apt-get install shellinabox
```
> jalankan saat booting dengan mengubah file : **/etc/default/shellinabox**
```bash
...
SHELLINABOX_DAEMON_START=0
...
```
> Buat folder dan file wrapper baru
```bash
•	sudo mkdir /home/shellinabox
•	cd /home/shellinabox
•	sudo nano sshwrapper.sh
```
> Paste-kan config berikut dan simpan
```bash
#!/bin/bash
url=$1;
token=${url#*\?};

# Prepare variables
TABLE="tb_loginlog"
SQL_IS_EMPTY=$(printf 'SELECT 1 FROM %s WHERE `fingerprint`="'$token'" AND `stat`=1' "$TABLE")

# Credentials
USERNAME=username
PASSWORD=password
DATABASE=wavenet

# Check if table exists
if [[ $(mysql -u $USERNAME -p$PASSWORD -e "$SQL_IS_EMPTY" $DATABASE) ]]
then
  clear;
else
  echo "=============================="
  echo "User not allowed. Login first!"
  echo "=============================="
  exit;
fi

read -p "remote mode [telnet] : " mode;
if [ -z "$mode" ]; then
  mode="telnet";
fi
if [ "$mode" = "telnet" ]; then
  clear;
  echo "=============================="
  echo "Telnet"
  echo "=============================="
  read -p "hostname or ip address : " host;
  if [ -z "$host" ]; then
    echo ""
    echo ""
    echo "A hostname or ip address of the remote host is required."
    echo ""
    echo ""
    exit
  fi
  clear
  echo "=============================="
  echo "Telnet $host"
  echo "=============================="
  read -p "port [23] : " port;
  if [ -z "$port" ]; then
    port=23;
  fi
  if [[ -n ${port//[0-9]/} ]]; then
    echo ""
    echo ""
    echo "Port must be a number between 0 and 65535."
    echo ""
    echo ""
    exit
  fi
  clear
  echo "=============================="
  echo "Telnet $host:$port"
  echo "=============================="
  exec telnet $host $port;
fi
if [ "$mode" = "ssh" ]; then
  clear;
  echo "=============================="
  echo "SSH"
  echo "=============================="
  read -p "hostname or ip address : " host;
  if [ -z "$host" ]; then
    echo ""
    echo ""
    echo "A hostname or ip address of the remote host is required."
    echo ""
    echo ""
    exit
  fi
  clear;
  echo "=============================="
  echo "SSH $host"
  echo "=============================="
  read -p "port [22] : " port;
  if [ -z "$port" ]; then
    port=22;
  fi
  if [[ -n ${port//[0-9]/} ]]; then
    echo ""
    echo ""
    echo "Port must be a number between 0 and 65535."
    echo ""
    echo ""
    exit
  fi
  clear;
  echo "=============================="
  echo "SSH $host:$port"
  echo "=============================="
  read -p "username : " username;
  if [ -z "$username" ]; then
    echo ""
    echo ""
    echo "A username of the remote host is required."
    echo ""
    echo ""
    exit
  fi
  echo ""
  echo ""
  exec ssh -p $port $username@$host;
fi
```
> Buat file start.sh dengan perintah **sudo nano start.sh** , paste dan simpan script berikut :
```bash
shellinaboxd -p 4200 -m '*'  \
--no-beep \
--disable-ssl \
--background \
-s /:root:root:HOME:'/home/shellinabox/sshwrapper.sh ${url}' \
--user-css Normal:+/var/www/html/assets/css/shellinabox.css
```
> Pada file **/etc/apache2/sites-enabled/apps.wavenet.id-le-ssl.conf ** tambahkan baris berikut :
```bash
</VirtualHost>
<Location /shell>
ProxyPass http://localhost:4200/
</Location>
</IfModule>
```
> Enable proxy module pada apache
```bash
•	 a2enmod proxy
•	 a2enmod proxy_http
```
> Reboot server/komputer
```bash
•	 reboot
```


## Usage

```python

```

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](https://choosealicense.com/licenses/mit/)
