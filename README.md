<p align="center">
  <h1 align="center">PHP Assessment</h1></p>

# Table of contents

- [Overview](#overview)
- [File Structure](#file-structure)
- [Architecture](#architecture)
- [Tools Used](#tools-used)
- [How to install](#how-to-install)
- [Results](#results)

# Overview

This is an assessment to display the most starred public PHP projects in GitHub. It consits of some client code (html, css, javascript) that makes calls to files hosted on a web server (php). 

The server queries the GitHub API's search/repositories endpoint: 'https://api.github.com/search/repositories' for repository information and stores the information in a MySQL database. Then it can retrieve that information to create HTML to send to the client.

# File Structure

php_assessment file structure

  |_ css
  
    |_ style.css 
    
  |_ includes
  
    |_ create_table.sql
    |_ debug.log
    |_ debug_with_errors.log
    |_ refresh_client.php
    |_ refresh_database.php
    |_ send_message.php    
    
  |_ js
  
    |_ jquery.js    
    |_ main.js 
    
  |_ materialize
  
    |_ css
        |_ materialize.min.css
    |_ js
        |_ materialize.min.js 
        
  |_ index.php

**css/style.css** - Adding our own styles.

**includes/create_table.sql** - Sql query to create 'repositories' table in 'php_assessment' database in localhost's MySQL Server.

**includes/debug.log** - this log file is overwritten everytime refresh_database.php is executed. It contains helpful development information about success/failure of connecting to the MySQL database and cURL execution of the API query and the code-generated insert statements.

**includes/debug_with_errors.log** - this log file has information and failure messages when I used API search query with parameters to retrieve all public php repsotiories starred above 10000 (NOTE: this file is only for the assessment reference purpose and is not part of the working code).

**includes/refresh_database.php** - this file performs two things in sequence
1. Queries the repositories table for repository information.
2. Iterates through the repository data received and outputs HTML to return to the client.

**includes/refresh_database.php** - this file performs three things in sequence
1. Create 'repositories' table and schema in php_assessment database of localhost's MySQL server if it doesn't exist.
2. Query the GitHub API to retreive a list of the most starred PHP projects in serialized JSON format. Public repositories with the language tag PHP and more than 10,000 stars are considered "most starred PHP projects". **(SEE NOTE BELOW)**
3. Creates and executes a SQL query to insert values into our repositories table.
    
**NOTE**

The query I need to use to hit the GitHub API to retreive the list of the most starred PHP projects is **https://api.github.com/search/repositories?q=stars:%3E10000+language:php+is:public**   
(Public repositories with the language tag PHP and more than 10,000 stars is what I considered as the most starred PHP projects)

**But I had to modify the API search parameters to get repositories starred between 15000 and 33000, since there is INSERT ANOMALY caused while getting all starred repositories greater than 10000 and performing the database insert. Hence the query I used in my code is as below: 
https://api.github.com/search/repositories?q=stars:15000..33000+language:php+is:public**

**Error message written to debug.log is below (SEE debug_with_errors.log file for more information)**
INSERT query failed. See error:
You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 's companion. It's a collection of multiple types of lists used during securit...' at line 1
    
**includes/send_message.php** - this file is included in refresh_database.php and refresh_client.php. It creates a Materialize card with a message on what the server encountered.

**js/main.js** - contains the Javascript necessary to make calls to the server and update the client's browser with received information and also initiates the collapsible function.

**index.php** - this page is served to the client initially that allows them to make calls via main.js to refresh the database or the client via two buttons.

# Architecture

![image](https://user-images.githubusercontent.com/7216897/133654717-2a9486a3-2139-46e9-816a-e38fde2edb6a.png)


**Route #1 **

1. Client sends default request to [[SERVER_ADDRESS]]/php_assessment/index.php
2. Client clicks REFRESH DATABASE to send HTTP request to php_assessment/includes/refresh_database.php via main.js function refresh_database()
3. Client clicks REFRESH CLIENT to send HTTP request to php_assessment/includes/refresh_client.php via main.js function refresh_client().
  
**Route #2 **

SERVER returns html generated by index.php to client
OR
SERVER either sends a server information message or "Database refreshed!" message to the client. Javascript on the client loads message into the #mainContent element
OR
SERVER either sends a server information message or HTTP response that presents the repository information retrieved from the MySQL database. Javascript on the client loads repository list items into the #mainContent element.
  
**Route #3 **

SERVER sends a query string to MySQL database to request repository data.
  
**Route #4 **

MySQL returns the database results that we retrieve via mysqli_fetch_array PHP function and create repository list items within collapsible div element. 
  
**Route #5 **

cURL session is created and executed to retrieve JSON encoded string from https://api.github.com/search/repositories API endpoint.
  
**Route #6 **

GitHub responds to route #5 request with JSON encoded repository data.

# Tools Used

<ul>
  <li>XAMPP web server - This is an open-source web server to be installed in your local machine. This package provides PHP development environment with MySQL database integrated in it.</li>
  <li><a href="https://materializecss.com/" target="_blank">Materialize CSS</a> - This is a free framework for presentation purpose.</li>
</ul>

# How to Install

<h5>Install and configure your web server</h5>

1. Install any web server of your choice. I used XAMPP.

2. If you've installed XAMPP, you can open the XAMPP Control Panel as shown below. Apache is the name of the web server bundled with XAMPP.
![image](https://user-images.githubusercontent.com/7216897/133658155-27c78be9-7ad1-4558-b18f-0411cfd5a236.png)

3. Once you've instanciated Apache on your system you can click "Start" and then "Stop" under "Actions" in the Control Panel to start and stop the server respectively.

4. Place the php_assessment folder and its contents into the public access folder of your web server. In XAMPP "htdocs" is the public access folder location where I have my files (php_assessment folder).

5. You should now be able to visit localhost/php_assessment to see the UI interface.

<h5>Create and configure your MySQL server instance</h5>

1. In XAMPP, you can create the server instance by clicking the red X button under "Service" in the Modules section of the XMAPP Control Panel.

2. After you've instanciated your server and started the service associated with it, create a database on the server by going to localhost/phpmyadmin control panel in your favorite browser.

3. At the PHPMyAdmin tool, click "New" near the top of the sidebar. In the "Database name" input field that appears, enter php_assessment and click the "Create" button.

4. The sidebar will automatically refresh and you should see php_assessment listed under "New" in the sidebar. This signifies that your database is created. You do not need to create any tables in the database.

5. Below screenshot shows the php_assessment database and 'repositories' table columns and data.
![image](https://user-images.githubusercontent.com/7216897/133660863-5d18420f-2df7-406d-ab95-a7ffea1cfc7f.png)

# Results

1.Go to your favorite browser and visit "localhost/php_assessment".

2. Below screen appears initially when there is no 'repositories table' created yet in the php_assessment database
![image](https://user-images.githubusercontent.com/7216897/133661390-d20fbd1c-ad4d-4da8-93c2-dea4816ce223.png)

3. Click the "REFRESH CLIENT" button and below screen appears when there is repositories table created but no records in the database
![image](https://user-images.githubusercontent.com/7216897/133661166-5de0d394-347d-423b-b4b8-4f2e5d4291f7.png)

4. Click the "REFRESH DATABASE" button to pull GitHub API repository information into your MySQL server instance.
![image](https://user-images.githubusercontent.com/7216897/133660433-71358a16-c24e-47e0-9ef8-9aa910a351a4.png)

5. Click the "REFRESH CLIENT" button to update the browser with repository information from the MySQL database.
![image](https://user-images.githubusercontent.com/7216897/133660505-4f750753-3a6f-4158-b024-e326952a2928.png)

6. Expand the listed repository list item to view the asscoaited repository data
![image](https://user-images.githubusercontent.com/7216897/133660700-735a3316-0a91-49c1-85e1-f2bb264df379.png)





