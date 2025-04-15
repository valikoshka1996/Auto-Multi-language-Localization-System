Sure! Here is a `README.md` file in English for your project:


# Multi-language Localization System

This project implements a multi-language localization system based on user IP. It determines the user's country based on their IP address and loads the corresponding language files. The system supports rotation of log files once they reach a specified size.

## Features

- **Automatic Localization**: Based on the user's country, the system automatically selects the appropriate language files (`en`, `ua`, `ru`, `fr`, etc.).
- **Language Fallback**: If a language for a specific country is not found, it defaults to English.
- **Logging**: Access logs are recorded in a `log.txt` file. The log includes the user's IP address, country code, and the date/time of the request.
- **Log Rotation**: Once the `log.txt` file exceeds 10 MB, it is cleared and overwritten.

## Requirements

- PHP 7.0+ (for PHP functions like `file_get_contents`, `scandir`, `json_decode`).
- The project directory must include the `localisation` folder, containing localization files (`*.txt`) for each supported language.

## File Structure


/localisation 
    /en.txt
    /ua.txt
    /ru.txt
    /fr.txt
    ...
/logs
    /log.txt
/flags
    en.svg
    ua.svg
    ...
/adminpanel
    /assets
        admin.json
        nav.php
    index.php
    admin.php
    api.php
    dashboard.php
    logout.php
    manage.php
    
index.php
README.md


## Installation

1. **Clone the Repository**:
    - Clone or download the project to your server or local machine.

2. **Setup the `localisation` Folder**:
    - Place all localization files inside the `localisation` folder. Each file should be prefixed with the country language code (e.g., `en.txt` for English, `ua.txt` for Ukrainian, etc.).
    - Write key - value par in files, like title=Hello World! in en.txt, and title=Привіт Світ! for ua.txt

3. **Configure Server**:
    - Ensure your server has PHP installed and configured to run PHP scripts.
    - Set up file permissions so that the web server can read files in the `localisation` folder and write to the `logs` folder.

4. **Accessing the Application**:
    - Navigate to `index.php` in your browser. The page will automatically determine the user's language based on their IP address.

## How It Works

1. **IP Address Detection**: The user's real IP address is detected using `$_SERVER` variables, considering headers such as `X-Forwarded-For` for proxy environments.
   
2. **Country Determination**: The IP address is used to query the [GeoJS API](https://get.geojs.io/) to determine the country code.

3. **File Selection**: Based on the country code (e.g., `UA` for Ukraine, `RU` for Russia), the appropriate language files are loaded. If no matching language file is found, English (`en_`) is used by default.

4. **Log Rotation**: The access logs are written into `logs/log.txt`. Once the file size exceeds 10 MB, it is cleared and rewritten.

## Example Output

When accessing the site, the appropriate content from the localization files will be displayed. For example, if the user is from Ukraine, the content from the `ua_main.txt` and `ua_div.txt` files will be shown.

Example output:

```html
<h1>Welcome to our website!</h1>
<div>Here is some content specific to Ukrainian users.</div>
```

If the user's country is not recognized or there is no specific file for the country, the content will default to English.



# Admin Panel for Access Logs

This project provides an admin panel to view and analyze the access logs of your website. The panel includes login authentication, paginated log entries, country-based visit charts, and the ability to export logs as a PDF. The following sections describe how to set up and use this system.

## Project Structure

```
/admin
    ├── assets
        ├──nav.php        # navigation menu
        ├──admin.json     # Admin credentials (login and password)
    ├── index.php         # Login page for admin
    ├── admin.php         # Dashboard page (logs & charts)
    ├── api.php           # change request endpoint
    ├── dashboard.php     # Statistic dashboard
    ├── logout.php        #logout endpoint
    ├── manage.php        # change admin password
    └── style.css         # Custom styles for the admin panel
```

### Files:

- **admin.json**: Contains the admin credentials (login and password).
- **index.php**: The login page where the admin can enter their credentials.
- **admin.php**: The main admin dashboard that displays access logs and a chart for visit statistics.
- **style.css**: Styles for the admin panel pages.

## Setup

### 1. Configure Admin Credentials

Before using the admin panel, you need to set the admin credentials in the `admin/admin.json` file. You can edit this file to set your desired login and password.

The format is as follows:

```json
{
    "login": "admin",
    "password": "hash_adminpassword"
}
```

Make sure to keep these credentials secure and avoid exposing the `admin.json` file.

### 2. Restrict Access to `admin.json`

To prevent unauthorized access to the `admin.json` file via the browser, ensure you have configured your web server properly. 

For Apache servers, add the following rules to your `.htaccess` file:

```apache
<Files "admin.json">
    Order Allow,Deny
    Deny from all
</Files>
```

For NGINX servers, add the following to the server block in the NGINX config:

```nginx
location ~* /admin.json {
    deny all;
}
```

This will prevent external access to the `admin.json` file.

### 3. Access the Admin Panel

- Open your browser and navigate to the **login page** of the admin panel, typically located at:

  ```
  http://yourdomain.com/admin/index.php
  ```

- Enter the admin credentials (as specified in `admin.json`) on the login page.

### 4. Dashboard Features

Once logged in, you will be redirected to the **dashboard** (`admin.php`), where the following features are available:

- **Pagination**: Logs are paginated, displaying 20 entries per page. You can navigate through the logs using the "Previous" and "Next" buttons.
  
- **Logs Table**: Displays a table with the following columns:
  - **Timestamp**: Date and time of the access.
  - **IP Address**: The IP address of the visitor.
  - **Country**: The country code (two-letter) derived from the visitor's IP.

- **Visit Analytics**: A bar chart visualizing the number of visits per country. The chart uses the **Chart.js** library to generate a dynamic representation of your access logs.

### 5. Logging Out

To log out of the admin panel, click the "Logout" button, which will end your session and redirect you back to the login page.

## Security Considerations

- **HTTPS**: Make sure your admin panel is served over HTTPS to protect sensitive login credentials during transmission.
- **Session Management**: The admin panel uses PHP sessions to track login status. Ensure your server configuration allows session handling securely.
- **Password Storage**: In this implementation, the password is stored as plain text in the `admin.json` file. Consider hashing the password with PHP’s `password_hash()` and `password_verify()` functions for better security in production environments.

## Troubleshooting

- **Unable to Access Admin Panel**: Ensure the `admin.json` file has the correct credentials and that it's protected from public access via your server configuration.
- **Charts Not Displaying**: Make sure the `Chart.js` library is correctly loaded and that the access logs contain country information.
- **Exporting PDF**: If the export functionality doesn't work, check if the necessary libraries for PDF generation are correctly installed.



## Log File Format

The log file (`logs/log.txt`) records the following details for each request:

```
[YYYY-MM-DD HH:MM:SS] IP: <IP_ADDRESS>, Country: <COUNTRY_CODE>
```

### Log Rotation

The log file will be cleared automatically if its size exceeds 10 MB. This ensures the log file doesn't grow too large and consume excessive disk space.

## Customization

- **Adding New Languages**: To add support for a new language, create new localization files (e.g., `de_main.txt` for German) in the `localisation` folder. The system will automatically detect and serve content based on the user's country.
- **Changing the Max Log Size**: You can change the max log size by modifying the `$max_log_size` variable in the `logAccess()` function (currently set to 10 MB).

## Troubleshooting

- **Localization Files Not Found**: Ensure that the files in the `localisation` folder are named correctly with the appropriate language prefix (e.g., `en_main.txt` for English).
- **GeoJS API Issues**: If the GeoJS service is unavailable or experiencing issues, the system will fall back to English as the default language.

## License

This project is licensed under the MIT License.


