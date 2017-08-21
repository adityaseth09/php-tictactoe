# PHP7 -TicTacToe API

#### About 
A Rest API where in the requester sends in a json having the current boardstate(game), the player symbol of the Computer.

The Response is a similar json but with the next move and/or the status of Game.

Please note that the API is completely Stateless and uses post request.



#### Pre-requisites
You need to have git, PHP7 and composer installed to run the application, also phpunit is required in case you need to run tests


#### Api Documentation
Please check the api documentation at [api-documentation](https://github.com/adityaseth09/php-tictactoe/blob/master/docs/Api.md) 


#### To Run
$ checkout the application

$ cd php-tictactoe (all further commands expect you to be on this folder)

$ composer install

Make sure that the web server(nginx/apache) points to the current folder(php-tictactoe) and is having correct access rights


#### To run tests
$ phpunit src/tests


### How to Play

Its a bit annoying but, You can still play game without FrontEnd, check out how to [here](https://github.com/adityaseth09/php-tictactoe/blob/master/docs/HowToPlay.md)


##### Try IT Online
The API is hosted online at [http://52.59.234.120/api.php](http://52.59.234.120/api.php) the method being used is POST


#### Missing Parts/ Things to do
The Application is not having a UI interface, to play with as of now, but a fully functional API

The Application API is open for use and does not require any keys or tokens to connect with

There is no specific version of this API

The Game is not having any AI

The Integration tests are missing, however the unit test covers the heart of code.

