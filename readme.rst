###################
myStorage
###################

Description: myStorage is web app for local cloud, single user storage. Easy to use with MAMP server.

Setup: Go to application/controllers/Login.php and application/controllers/DataExplorer.php and set up STORAGE_PATH. Password for site can be found in confFiles/conf.txt. If you need to upload files larger then 8MB to server you need to set up MAMP server php conf file.

SSL: if you have setup https on your server then set up 
$config['base_url']  =  "https://".$_SERVER['HTTP_HOST']; in your 
CI/application/config/config.php and uncomment function in 
CI/application/hooks/ssl.php

no SSL: if you dont wont https then set up 
$config['base_url']  =  "http://".$_SERVER['HTTP_HOST']; in your 
CI/application/config/config.php and comment function in 
CI/application/hooks/ssl.php

Link to more help with setting up SSL on MAMP: https://gist.github.com/jfloff/51538826

Link to server: http://localhost:port/mystorage

MrLaki5