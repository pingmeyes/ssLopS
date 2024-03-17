# SSL Manager

SSL Manager is a web application designed to simplify SSL certificate management for domains. It provides an intuitive interface for adding domains, tracking SSL status, and monitoring expiration dates. With SSL Manager, users can efficiently manage their SSL certificates and ensure the security of their websites.

## Table of Contents

- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)


## Features

- **Domain Management:** Add new domains to track their SSL status and expiration dates.
- **SSL Status Tracking:** Monitor the SSL status of domains, including whether they are active or expired.
- **Expiration Monitoring:** Keep track of the number of days left until SSL certificates expire for each domain.
- **Search Functionality:** Quickly find specific domains using the search bar feature.
- **Dashboard Overview:** View a summary of all domains with their SSL status and expiration details.
- **Custom SSL Monitoring:** Ability to monitor additional domains using a separate form [Monitoring MFn1].
- **Data Refresh:** Automatic data refresh using cron jobs to ensure up-to-date information.

## Installation
To set up the SSL Manager project, follow these steps:

1. **Clone the project repository from GitHub.**

2. **Ensure that PHP, PHP-FPM, and Nginx are properly installed on your server.**

3. **Set Up the Database:**

   - Create a MySQL database named `domaindetails`.
   - Import the `ssl_details.sql` file into the database.
   - Update database credentials in the `config.php` file with the appropriate values. (Consider using environment variables for sensitive information.)

4. **Configure Cron Jobs:**

   - Set up cron jobs to run the following scripts periodically:
     - `refresh.php` for refreshing records in the database.
     - `updatemandb.php` for decrementing the days left to expire for the domains added with the second form.

6. **Configure the Nginx server block to properly handle PHP files and route requests to the correct location.**

## Nginx Configuration
If you're using Nginx with a Debian instance, ensure that your Nginx server block configuration includes directives to handle PHP files properly. Here's a basic example:

nginx

```server {
    listen 80;
    server_name your_domain.com;

    root /var/www/html;
    index index.php index.html index.htm;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }

    # Additional configuration directives as needed
}
```
7. **Test the project to ensure that domains can be added, monitored successfully.**

## Usage

- **Login to SSL Manager:**

 Access the SSL Manager application in your web browser. Log in using your credentials. If you don't have an account, you can register one.

- **Add Domains:**

 Use the forms on the left side of the page to add new domains. Provide the domain name, project name, and other relevant details.

- **Monitor SSL Status:**

 View the SSL status of added domains in the dashboard table. Monitor the number of days left until SSL certificates expire.

- **Search for Domains:**

 Utilize the search bar feature to find specific domains by name or project.

- **Manage Domains:**

 Perform actions such as deleting individual domains or clearing all records as needed. Be cautious when using the "Delete All Records" feature, as it will remove all domains from the database.

## Contributing

Contributions are welcome! If you'd like to contribute to this project, Contact: sanjai.s.error@gmail.com

