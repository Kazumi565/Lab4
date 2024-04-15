# Setting Up the Web App

This guide will help you set up the project to run locally on your machine.

## Prerequisites

Before you begin, ensure you have the following installed:

- [XAMPP](https://www.apachefriends.org/index.html): XAMPP is a free and open-source cross-platform web server solution stack package developed by Apache Friends, consisting mainly of the Apache HTTP Server, MariaDB database, and interpreters for scripts written in the PHP and Perl programming languages.

## Installation Steps

1. **Install XAMPP**: If you haven't already installed XAMPP, download and install it from the [official website](https://www.apachefriends.org/index.html).

2. **Clone the Repository**: Clone this repository to your local machine.

    ```bash
    git clone https://github.com/Kazumi565/Lab4.git
    ```

3. **Move the Folder to htdocs**: After cloning the repository, move the project folder to the `htdocs` directory of your XAMPP installation. By default, the `htdocs` directory is located in the XAMPP installation directory.

4. **Run the Database Script**: Open phpMyAdmin and import the database script provided with the project. This script will create the necessary database tables and populate them with initial data.

5. **Access the Web App**: Once the setup is complete, open your web browser and navigate to the following URL:

    ```
    http://localhost/DbSite
    ```

    This will take you to the homepage of the web app.

## Support

If you encounter any issues during the setup process, please don't hesitate to [create an issue](https://github.com/Kazumi565/Lab4/issues) on GitHub or reach out to the project contributors for assistance.
