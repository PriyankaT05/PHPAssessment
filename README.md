<p align="center">
  <h3 align="center">PHPAssessment</h3></p>

# Table of contents

- [Overview](#overview)
- [Architecture description](#architecture-description)
- [Routing diagram](#routing-diagram)
- [How to install](#how-to-install)
- [How to use](#how-to-use)

# Overview

This is an assessment to display the most starred public PHP projects in GitHub. It consits of some client code (html, css, javascript) that makes calls to files hosted on a web server (php). 

The server queries the GitHub API's search/repositories endpoint: 'https://api.github.com/search/repositories' for repository information and stores the information in a MySQL database. Then it can retrieve that information to create HTML to send to the client.

The query I used to hit the GitHub API to retreive a list of the most starred PHP projects is 
   **https://api.github.com/search/repositories?q=stars:%3E10000+language:php+is:public**   
(Public repositories with the language tag PHP and more than 10,000 stars is what I considered as the most starred PHP projects)


# Architecture

# Tools Used

<ul>
  <li>XAMPP web server - This is an Open Source Package that provides PHP development environment with MySQL database integrated in it.</li>
  <li><a href="https://materializecss.com/" target="_blank">Materialize CSS</a> - This is a free framework for presentation purpose.</li>
</ul>

Request to refresh database received.
Found git_php_repos database in MySQL server instance.
Found or created repositories table.
curl session initialized.
curl options set.
curl session finished.
curl session closed.
curl successful.
