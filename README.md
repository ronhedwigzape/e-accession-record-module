# e-accession-record-module

CSPC Library Learning Resources and Development Services: E-Accession Record Module

---

## 💿 Development Setup
Here are the steps to set up the development environment for this project:

1. Download and install
   [XAMPP](https://www.apachefriends.org/download.html)
   and [NodeJS](https://nodejs.org/en/),
   if you haven't already.

2. Start Apache and MySQL through XAMPP if not already running.

3. Clone or download this repository to your XAMPP **htdocs** folder.
   The final path should be `path_to/xampp/htdocs/e-accession-record-module`.

4. Copy [**`app/config/database.example.php`**](app/config/database.example.php)
   to **`app/config/database.php`**, then modify the database connection settings in the new file.

5. Inside [phpMyAdmin](http://localhost/phpmyadmin),
   create a MySQL database named `e-accession-record-module` and import [e-accession-record-module.sql](e-accession-record-module.sql) into it.

6. Open the terminal and navigate to the project directory **e-accession-record-module**.

7. Execute the following commands to install the required dependencies:
   ```sh
   npm install
   ```

8. Install Composer dependencies:
   ```sh
   composer install
   ```
   If this command does not work, try running `composer update` instead.

9. Compile and run the development server with hot reloading:
   ```sh
   npm run dev
   ```

10. Open your web browser and access <http://localhost:5005/e-accession-record-module/> to view the application.

---

## 🛠️ Production Deployment
Here's how to compile the project for production deployment:

1. Generate the public folder by running the following command:
   ```sh
   npm run build
   ```

2. Access the application by visiting `http://[host_name]/e-accession-record-module`,
   where `host_name` is the **IP address** or **host name** of the server in the network.
   For example:
   - <http://localhost/e-accession-record-module>
   - <http://192.168.1.99/e-accession-record-module>

#### Admin Dashboard
The default ***username*** and ***password*** is `jonie`.

## 📑 License
[MIT](http://opensource.org/licenses/MIT)

Copyright (c) 2024-present ronhedwigzape