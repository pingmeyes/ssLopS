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
- **Manual Domain Monitoring:** Ability to manually monitor additional domains using a separate form.
- **Data Refresh:** Automatic data refresh using cron jobs to ensure up-to-date information.
- **Secure Authentication:** User authentication system to protect sensitive data.

## Installation

1. **Clone the Repository:**

2. **Navigate to the Project Directory:**

3. **Set Up the Database:**

   - Create a MySQL database named `domaindetails`.
   - Import the `ssl_details.sql` file into the database.
   - Update database credentials in the `config.php` file with the appropriate values. (Consider using environment variables for sensitive information.)

4. **Configure Cron Jobs:**

   - Set up cron jobs to run the following scripts periodically:
     - `refresh.php` for refreshing records in the database.
     - `decrement_expiry_days.php` for decrementing the days left to expire for the domains added with the second form.

5. **Start the Web Server:**

   - Make sure to have a web server (e.g., Apache, Nginx) and PHP installed.
   - Configure the server to point to the project directory.

6. **Access the Application:**

   - Open the application in a web browser.
   - Log in using your credentials.

## Usage

- **Login to SSL Manager:**

 Access the SSL Manager application in your web browser. Log in using your credentials. If you don't have an account, you can register one.

- **Add Domains:**

 Use the form on the left side of the page to add new domains. Provide the domain name, project name, and other relevant details.

- **Monitor SSL Status:**

 View the SSL status of added domains in the dashboard table. Monitor the number of days left until SSL certificates expire.

- **Search for Domains:**

 Utilize the search bar feature to find specific domains by name or project.

- **Manage Domains:**

 Perform actions such as deleting individual domains or clearing all records as needed. Be cautious when using the "Delete All Records" feature, as it will remove all domains from the database.

