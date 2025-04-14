Sure! Here is a `README.md` file in English for your project:


# Multi-language Localization System

This project implements a multi-language localization system based on user IP. It determines the user's country based on their IP address and loads the corresponding language files. The system supports rotation of log files once they reach a specified size.

## Features

- **Automatic Localization**: Based on the user's country, the system automatically selects the appropriate language files (`en_`, `ua_`, `ru_`, `fr_`, etc.).
- **Language Fallback**: If a language for a specific country is not found, it defaults to English.
- **Logging**: Access logs are recorded in a `log.txt` file. The log includes the user's IP address, country code, and the date/time of the request.
- **Log Rotation**: Once the `log.txt` file exceeds 10 MB, it is cleared and overwritten.

## Requirements

- PHP 7.0+ (for PHP functions like `file_get_contents`, `scandir`, `json_decode`).
- The project directory must include the `localisation` folder, containing localization files (`*.txt`) for each supported language.

## File Structure


/localisation 
    /en_main.txt
    /ua_main.txt
    /ru_main.txt
    /fr_main.txt
    ...
/logs
    /log.txt
index.php
README.md


## Installation

1. **Clone the Repository**:
    - Clone or download the project to your server or local machine.

2. **Setup the `localisation` Folder**:
    - Place all localization files inside the `localisation` folder. Each file should be prefixed with the country language code (e.g., `en_main.txt` for English, `ua_main.txt` for Ukrainian, etc.).

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

