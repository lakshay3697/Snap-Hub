Procedure to set up the project locally :-

1. Unzip the compressed folder inside the directory where your web server looks for files to serve. ('htdocs' directory in case of XAMPP server local setup)
2. Import the .sql file 'snaphub.sql' inside the unzipped folder in your administration tool for MySQL & MariaDB. For ex - PHP Myadmin
3. Make sure your db credentials are :-
    Username - root
    Password - ""

    If in case these credentials are different for your local setup then change the credentials in 'con-pdo.php' connection file 
3. Browse to 'localhost/Snaphub' and you will see the application running.

Following are credentials of some of the registered users for this application with data for testing purposes :-

User credentials :- 

1. Email - lakshay3697@gmail.com
   Password - stanley36

2. Email - luckyracer074@gmail.com
   Password - marshall

3. Email - anupam1109@gmail.com
   Password - nothingissafe

4. Email - lakshaygbpec@gmail.com
   Password - Singh@bling

Some suggestions :-

1. Make sure curl.dll extension is enabled in your php.ini file (For curl is used in this project)
2. Make sure the 'upload_max_filesize' is large enough than size of file being uploaded inside your php.ini while validating the image upload forms with larger files.