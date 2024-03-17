# SSL Manager

SSL Manager is a web application designed to help users manage SSL certificates for their domains. It provides a user-friendly interface for adding, tracking, and monitoring SSL certificates, ensuring the security and validity of domain certificates.

## Table of Contents

- [Introduction](#introduction)
- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
- [Dashboard](#dashboard)
- [Contributing](#contributing)
- [License](#license)

## Introduction

SSL (Secure Sockets Layer) certificates are essential for securing data transfer between a web server and a browser. SSL Manager simplifies the process of managing SSL certificates by offering a centralized platform for domain administrators to add, monitor, and renew SSL certificates effortlessly.

## Features

- **Add Domains**: Users can add domains along with project names, making it easy to categorize and manage SSL certificates for different projects.
- **Track SSL Status**: SSL Manager tracks the SSL status of each domain, indicating whether the SSL certificate is active or expired.
- **Monitor Expiration Dates**: Users can monitor the expiration dates of SSL certificates, ensuring timely renewal to prevent service disruptions.
- **Dashboard Overview**: The dashboard provides a comprehensive overview of all added domains, including their SSL status, days left to expire, and other relevant details.
- **Search Functionality**: SSL Manager offers a search feature to quickly locate specific domains based on domain name or project name.
- **Delete Records**: Users have the option to delete individual domain records or delete all records at once, providing flexibility in managing domain data.

## Installation

1. **Clone the Repository**: Start by cloning the SSL Manager repository to your local machine:

    ```
    git clone https://github.com/yourusername/ssl-manager.git
    ```

2. **Database Setup**: Create a MySQL database named `domaindetails` and import the `ssl_details.sql` and `manual_ssl_details.sql` files to set up the database schema.

3. **Configure Database Connection**: Update the database connection details in the `config.php` file with your MySQL database credentials.

4. **Web Server Setup**: Ensure you have a web server (e.g., Apache, Nginx) installed and running. Make sure PHP is also installed and configured.

5. **Launch the Application**: Access the SSL Manager application through your web browser and start managing SSL certificates for your domains.

## Usage

1. **Login**: Log in to the SSL Manager application using your credentials.

2. **Add Domains**: Use the provided form to add domains, specifying the domain name and associated project name.

3. **Monitor SSL Status**: View the dashboard to monitor the SSL status of added domains, including expiration dates and days left to expire.

4. **Search Domains**: Utilize the search functionality to find specific domains by domain name or project name.

5. **Delete Records**: Delete individual domain records by clicking the delete button in the dashboard table. Alternatively, use the "Delete All Records" link to delete all domain records (exercise caution).

## Dashboard

The dashboard serves as the central hub for managing SSL certificates. It provides an organized view of all added domains, displaying essential information such as domain name, project name, SSL status, days left to expire, and more. Users can perform actions like searching for domains, deleting individual records, or refreshing the dashboard to update SSL status.

## Contributing

Contributions to SSL Manager are welcome! If you have ideas for improvements or new features, feel free to contribute by following these steps:

1. **Fork the Repository**: Fork the SSL Manager repository to your GitHub account.

2. **Create a Branch**: Create a new branch for your feature or improvement (e.g., `feature-new-feature`).

3. **Make Changes**: Implement your changes or add new features to the codebase.

4. **Test**: Ensure that your changes are thoroughly tested and do not introduce any bugs or issues.

5. **Submit a Pull Request**: Once your changes are ready, submit a pull request to the main repository. Provide a clear description of your changes and any related issues.

## License

SSL Manager is licensed under the [MIT License](LICENSE), allowing for open collaboration and usage. Feel free to use, modify, and distribute the software according to the terms of the license.
