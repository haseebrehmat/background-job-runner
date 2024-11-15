
<a id="readme-top"></a>
<br />
<h1 align="center">Background Job Runner</h1>
<!-- TABLE OF CONTENTS -->
<details>
  <summary>Table of Contents</summary>
  <ol>
    <li>
      <a href="#about-the-project">About The Project</a>
      <ul>
        <li><a href="#built-with">Built With</a></li>
      </ul>
    </li>
    <li>
      <a href="#getting-started">Getting Started</a>
      <ul>
        <li><a href="#prerequisites">Prerequisites</a></li>
        <li><a href="#installation">Installation</a></li>
      </ul>
    </li>
    <li><a href="#usage">Usage</a></li>
    <li><a href="#roadmap">Roadmap</a></li>
    <li><a href="#license">License</a></li>
  </ol>
</details>



<!-- ABOUT THE PROJECT -->
## About The Project

This project implements a custom background job runner for Laravel that executes PHP classes as background jobs independently of Laravel’s built-in queue system. It supports both Windows and Unix-based systems, offering error handling, retry mechanisms, and logging, making it scalable and easy to use within Laravel applications.



### Built With

* [![PHP][php.net]][PHP-Net]
* [![Laravel][Laravel.com]][Laravel-url]




<!-- GETTING STARTED -->
## Getting Started

This is an example of how you may give instructions on setting up your project locally.
To get a local copy up and running follow these simple example steps.

### Prerequisites

This project requires the following to be installed:

#### 1. PHP
Version 8.1 or higher

#### 2. Composer
Dependency manager for PHP. You can install Composer globally as follows:

```bash
# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```
**OR** 
You can install Composer according to your OS by following the instructions at getcomposer.org.

#### 3. Laravel
- Version 10.x or higher
- Installable via Composer `composer global require laravel/installer`

#### 4. Node.js & NPM
Required for frontend dependencies if applicable

#### 5. Database (optional)
A database is optional but recommended for job tracking or logging. Supported databases include MySQL, PostgreSQL, and SQLite.


### Installation

To install and set up this project locally, follow these steps:

1. **Clone the repository**:
   ```bash
   git clone https://github.com/haseebrehmat/background-job-runner.git
   ```
2. **Navigate to the project directory**:
   ```bash
   cd background-job-runner
   ```

3. **Install PHP dependencies using Composer**:
   ```bash
   composer install
   ```

4. **Install Node.js dependencies** (if applicable):
   ```bash
   npm install
   ```

5. **Set up environment variables**:
   - Copy `.env.example` to `.env`.
   - Update the `.env` file with your configuration, such as database credentials and other environment-specific settings.

   ```bash
   cp .env.example .env
   ```

6. **Generate application key**:
   ```bash
   php artisan key:generate
   ```

7. **Run database migrations**:
   ```bash
   php artisan migrate
   ```

8. **Run the application**:
   ```bash
   php artisan serve
   ```

9. **Verify the setup**:
    - Open your browser and navigate to `http://localhost:8000` to check if the application is running correctly.

<p align="right">(<a href="#readme-top">🔝</a>)</p>

<!-- USAGE EXAMPLES -->
### Usage

This project includes commands and helper functions to execute background jobs independently of Laravel's queue system. Below are some example commands and usage instructions.

#### 1. Running a Background Job Directly

You can use the `job:run` Artisan command to execute a background job directly from the command line. Replace `App\Jobs\YourJob` with the actual job class and `methodName` with the method to execute.

```bash
php artisan job:run "App\Jobs\YourJob" "methodName" '{"param1": "value1", "param2": "value2"}'
```

Example:
```bash
php artisan job:run "App\Jobs\CleanupOldRecordsJob" "execute" '{"days":30}'
```

#### 2. Testing Error Scenarios

Use the following command to test error handling when passing incorrect parameters, such as a parameter with a wrong name. This helps ensure that parameter validation and error logging work as expected.

```bash
php artisan job:run "App\Jobs\CleanupOldRecordsJob" "execute" '{"invalidParam":30}'
```

Expected Result:
- Logs an error in `background_jobs_errors.log` indicating a missing or invalid parameter.

#### 3. Running Jobs with Helper Function `runBackgroundJob`

The `runBackgroundJob` helper function allows you to execute a job in the background, compatible with both Windows and Unix-based systems.

Example:
```php
runBackgroundJob("App\Jobs\SendEmailJob", "execute", ["email" => "test@example.com", "subject" => "Hello", "message" => "This is a test."]);
```

This function automatically detects the OS and runs the job in the background using:
- **Windows**: Uses `popen` to execute the job.
- **Unix**: Executes the job using `shell_exec`.

#### 4. Logs and Results

Each background job logs its status (success, retry, or failure) in the `storage/logs/background_jobs.log` file. The structure of log entries includes:
- **Class**: Name of the job class.
- **Method**: Method executed.
- **Status**: Success, retry, or failed.
- **Error Message**: Any error encountered during execution (if applicable).
- **Timestamp**: The time of the log entry.

#### 5. List of Commnds
Here is the list of commands to see the output on terminal and `background_jobs.log` file in `storage/logs` directory. Below is a categorized list for each job class, including success and error scenarios.

##### 1. SendEmailJob
- *Success*:

    ```bash
    php artisan job:run "App\Jobs\SendEmailJob" "execute" '{"email": "test@example.com", "subject": "Hello", "message": "This is a test."}'
    ```
- *Error (Missing Parameter)*:

    ```bash
    php artisan job:run "App\Jobs\SendEmailJob" "execute" '{"subject": "Hello", "message": "This is a test."}'
    ```

##### 2. GenerateReportJob
- *Success*:

    ```bash

    php artisan job:run "App\Jobs\GenerateReportJob" "execute" '{"reportType": "Sales"}'
    ```

- *Error (Incorrect Method)*:

    ```bash
    php artisan job:run "App\Jobs\GenerateReportJob" "nonExistentMethod" '{"reportType": "Sales"}'
    ```

##### 3. CleanupOldRecordsJob
- *Success*:

    ```bash

    php artisan job:run "App\Jobs\CleanupOldRecordsJob" "execute" '{"days": 30}'
    ```

- *Error (Incorrect Parameter Name)*:

    ```bash

    php artisan job:run "App\Jobs\CleanupOldRecordsJob" "execute" '{"invalidParam": 30}'
    ```

<p align="right">(<a href="#readme-top">🔝</a>)</p>



<!-- ROADMAP -->
## Roadmap
- **Services**
    - `BackgroundJobRunner`
    - `ClassMethodValidator`
    - `JobLogger`
    - `MethodParameterValidator`
- **Jobs**
    - `CleanupOldRecordsJob`
    - `GenerateReportJob`
    - `SendEmailJob`
- **Commands**
    - `RunBackgroundJob` <small>(Artisan Command)</small>
- **Helpers**
    - `runBackgroundJob` <small>(Helper Function)</small>
- **Configurations**
    - `background_jobs` <small>(Config file)</small>
    - `logging` <small>(Config updates)</small>

---

### Feature Details

<details open> 
<summary style="font-size:18px;letter-spacing:2px;font-weight:400;">Services</summary>

**BackgroundJobRunner**

| Description | A service to handle execution of background jobs with retry mechanisms and error handling |
|-------------|------------------------------------------------------------------------------------------|
| Methods     | - `run($className, $method, $parameters = [])`: Executes a specified class and method with parameters.<br>- **Params**: `$className` (string), `$method` (string), `$parameters` (array) |
| Purpose     | Centralized job runner to handle background job execution, retries, logging, and error handling |


**ClassMethodValidator**

| Description | Helper for validating if a specified class and method are allowed and exist |
|-------------|----------------------------------------------------------------------------|
| Methods     | - `validate($className, $method, $allowedClasses)`: Validates if `$className` and `$method` are valid.<br>- **Params**: `$className` (string), `$method` (string), `$allowedClasses` (array) |
| Purpose     | Ensures that only approved classes and methods are executed as background jobs |



**JobLogger**

| Description | A service for standardized logging of job statuses, including success, retry, and failure |
|-------------|-------------------------------------------------------------------------------------------|
| Methods     | - `logStatus($className, $method, $status, $errorMessage = null)`: Logs job status.<br>- **Params**: `$className` (string), `$method` (string), `$status` (string), `$errorMessage` (string, optional) |
| Purpose     | Standardized logging to track job execution status and errors in a dedicated log file |



**MethodParameterValidator**

| Description | Helper for validating parameters required for the specified method in a given class |
|-------------|------------------------------------------------------------------------------------|
| Methods     | - `validate($className, $method, $parameters)`: Validates that `$parameters` match method’s requirements.<br>- **Params**: `$className` (string), `$method` (string), `$parameters` (array) |
| Purpose     | Ensures that the provided parameters match the expected parameters of the method |

</details>

<details> 
<summary style="font-size:18px;letter-spacing:2px;font-weight:400;">Job Classes</summary>

**CleanupOldRecordsJob**

| Description | Job that simulates cleaning up records older than a specified number of days |
|-------------|------------------------------------------------------------------------------|
| Methods     | - `execute($days)`: Cleans up records older than `$days`.<br>- **Params**: `$days` (int) |
| Purpose     | Demonstrates error handling and parameter validation for background jobs |


**GenerateReportJob**

| Description | Job that simulates generating a report of a specified type |
|-------------|------------------------------------------------------------|
| Methods     | - `execute($reportType)`: Generates a report based on `$reportType`.<br>- **Params**: `$reportType` (string) |
| Purpose     | Demonstrates successful job execution and logging of results |


**SendEmailJob**

| Description | Job that simulates sending an email with specified subject and message |
|-------------|------------------------------------------------------------------------|
| Methods     | - `execute($email, $subject, $message)`: Sends an email to `$email` with `$subject` and `$message`.<br>- **Params**: `$email` (string), `$subject` (string), `$message` (string) |
| Purpose     | Simulates an email job with logging and parameter validation |
</details>

<details> 
<summary style="font-size:18px;letter-spacing:2px;font-weight:400;">Command</summary>
 

**RunBackgroundJob (Artisan Command)**

| Description | An Artisan command that executes background jobs via the `BackgroundJobRunner` service |
|-------------|---------------------------------------------------------------------------------------|
| Methods     | - `handle()`: Retrieves command arguments and invokes `BackgroundJobRunner`.<br>- **Params**: `$class` (string), `$method` (string), `$parameters` (json string) |
| Purpose     | Allows background job execution from the command line, specifying class, method, and parameters |

</details>

<details> 
<summary style="font-size:18px;letter-spacing:2px;font-weight:400;">Helper</summary>

**runBackgroundJob (Helper Function)**

| Description | Helper function for executing a background job compatible with Windows and Unix-based systems |
|-------------|----------------------------------------------------------------------------------------------|
| Methods     | - `runBackgroundJob($class, $method, $params = [])`: Runs a background job on the appropriate platform.<br>- **Params**: `$class` (string), `$method` (string), `$params` (array) |
| Purpose     | Provides a cross-platform way to initiate background jobs without using Laravel's queue system |
</details>

<details> 
<summary style="font-size:18px;letter-spacing:2px;font-weight:400;">Configurations</summary>

**background_jobs (Config File)**

| Description | Custom configuration file for setting job runner options like retries and allowed classes |
|-------------|------------------------------------------------------------------------------------------|
| Options     | - `retries`: Number of retry attempts (default: 3)<br>- `allowed_classes`: Array of class names allowed to run as background jobs |
| Purpose     | Provides customizable settings for the background job runner |


**logging (Config Updates)**

| Description | Adds a custom log channel for tracking background job statuses in `storage/logs/background_jobs.log` |
|-------------|----------------------------------------------------------------------------------------------------|
| Configuration | In `config/logging.php`, add:<br> ```php<br>'background_jobs' => [ 'driver' => 'single', 'path' => storage_path('logs/background_jobs.log'), 'level' => 'info', ],<br>``` |
| Purpose     | Dedicated logging for background jobs, allowing tracking of job executions, retries, and errors |

</details>
<p align="right">(<a href="#readme-top">🔝</a>)</p>



<!-- CONTRIBUTING -->
## Contributing

Contributions are what make the open source community such an amazing place to learn, inspire, and create. Any contributions you make are **greatly appreciated**.

If you have a suggestion that would make this better, please fork the repo and create a pull request. You can also simply open an issue with the tag "enhancement".
Don't forget to give the project a star! Thanks again!

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

<p align="right">(<a href="#readme-top">🔝</a>)</p>


<!-- LICENSE -->
## License

Distributed under the MIT License.
<p align="right">(<a href="#readme-top">🔝</a>)</p>



<!-- MARKDOWN LINKS & IMAGES -->
[Laravel.com]: https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white
[Laravel-url]: https://laravel.com
[php.net]: https://img.shields.io/badge/PHP-FCFBF4?style=for-the-badge&logo=php&logoColor=blue
[PHP-Net]: https://www.php.net/
