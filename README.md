# PHPAssessment
This is an assessment to display the most starred public PHP projects in GitHub. It consits of some client code (html, css, javascript) that makes calls to files hosted on a web server (php).

The server queries the GitHub API's search/repositories endpoint: 'https://api.github.com/search/repositories' for repository information and stores the information in a MySQL database. Then it can retrieve that information to create HTML to send to the client.

The query I used to hit the GitHub API to retreive a list of the most starred PHP projects is below.
**https://api.github.com/search/repositories?q=stars:%3E10000+language:php+is:public**   
(Public repositories with the language tag PHP and more than 10,000 stars is what I considered as the most starred PHP projects)

