Звісно, ось інструкція для вашої адмінки в форматі README.md:

```markdown
# Admin Panel for Access Logs

This project provides an admin panel to view and analyze the access logs of your website. The panel includes login authentication, paginated log entries, country-based visit charts, and the ability to export logs as a PDF. The following sections describe how to set up and use this system.

## Project Structure

```
/admin
    ├── admin.json        # Admin credentials (login and password)
    ├── index.php         # Login page for admin
    ├── admin.php         # Dashboard page (logs & charts)
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
    "password": "adminpassword"
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

### 5. Export to PDF

You can export the logs and charts from the dashboard to a **PDF** document, which includes a table of log entries and a graphical representation of country-based visit data. The export function is integrated into the dashboard page (`admin.php`).

### 6. Logging Out

To log out of the admin panel, click the "Logout" button, which will end your session and redirect you back to the login page.

## Security Considerations

- **HTTPS**: Make sure your admin panel is served over HTTPS to protect sensitive login credentials during transmission.
- **Session Management**: The admin panel uses PHP sessions to track login status. Ensure your server configuration allows session handling securely.
- **Password Storage**: In this implementation, the password is stored as plain text in the `admin.json` file. Consider hashing the password with PHP’s `password_hash()` and `password_verify()` functions for better security in production environments.

## Troubleshooting

- **Unable to Access Admin Panel**: Ensure the `admin.json` file has the correct credentials and that it's protected from public access via your server configuration.
- **Charts Not Displaying**: Make sure the `Chart.js` library is correctly loaded and that the access logs contain country information.
- **Exporting PDF**: If the export functionality doesn't work, check if the necessary libraries for PDF generation are correctly installed.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

```

### Опис інструкції:

- **Структура файлів**: Тут вказано, що міститься в кожному з файлів та їх функціональність.
- **Налаштування**: Описано, як налаштувати облікові дані для адміністратора та як захистити файл `admin.json` від доступу через браузер.
- **Доступ до адмінки**: Як зайти в адмінку та опис функцій, що доступні на дашборді.
- **Безпека**: Рекомендації щодо забезпечення безпеки, зокрема використання HTTPS та захисту паролів.
- **Вивантаження у PDF**: Описано можливість експорту даних у PDF з адмінки.
