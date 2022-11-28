## Inspyde user listing 

+ Tested with PHP 7.4
+ Latest Wordpress 5.7.2

## Description 

Custom endpoint user listing plugin is implemented to display 3rd party API data in WordPress frontend as HTML table with custom endpoint `userlist-table` ,
 
This enpoint can be customise from the admin endpoint settings page (`http://siteurl.com/wp-admin/admin.php?page=custom_enpoint_settings`), default it set to `userlist-table`.

Please make  sure to  flush permalinks before visiting that endpoint.

To flush permalink Go to WP Admin > Settings > Permalinks > Save.

If you haven't change endpoint you will be able to access user listing table with default `userlist-table` endpoint.

Each row will contain links, once clicked it will show additional details Using asynchronous (AJAX).

For Frontend we have used HTML, css and jQuery as a click event to identify which user additional details need to be displayed, we will get user id and send ajax request to display details for that user.

Use `siteurl/wp-admin/admin.php?page=custom_enpoint_settings` which will display breif info about endpoint

## Cache

Here we have implement server side cache, we are storing json data in file, expiry time is set to 6 hrs after that it will be deleted and create new file.
By using this approach we can reduced API call as it will display data from cache file. 

## Composer Compatible

This plugin is compatible with composer. You will find `composer.json` file in root of plugin folder. We can set plugin name, description, require dependency, 
development dependency and scripts.

After composer run ```composer install```, vendor folder created with installed library files.

*Note:* If you want to install plugin via WP CLI we have to submit plugin to wordpress.org `https://wordpress.org/plugins/` once it approve we can install via wp cli

## Test Case with phpunit

PHPUnit library is for writing test cases in PHP.
We have to installed phpunit via composer in plugin (It is added in `composer.json` so it will install in `composer install` command). This are the steps to run test cases 
inside plugin folder.

1. Create wp-tests-config.php file from wp-config.php to configure test database. Also configure directory path in phpunit.xml file (It is in plugin root dir)

2. Run this command

     ```  bin/install-wp-tests.sh <db-name> <db-user> <db-pass> [db-host] [wp-version] [skip-database-creation] ```

    For Example:  bin/install-wp-tests.sh dbname  username  password  hostname  5.7.2 true


3. After step 2 please run below command to run test cases inside `tests` folder.

    ```  ./vendor/bin/phpunit  ```

4. You will see all test cases result (either passed or failed) with assertion 