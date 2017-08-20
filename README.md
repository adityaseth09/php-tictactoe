# PHP7 -TicTacToe API

####About 
A Rest API wherein the requester sends in a json having the current boardstate(game), the player symbol of the Computer.

The Response is a similar json but with the next move and/or the status of Game.

Please note that the API is completely Stateless.

#####Try IT Online
The API is hosted online at  

####Pre-requisites
You need to have git, PHP7 and composer installed to run the application, also phpunit is required in case you need to run tests

####To Run
$ checkout the application

$ cd php-tictactoe (all further commands expect you to be on this folder)

$ composer install


####To run tests
$ phpunit src/tests

#### Missing Parts
The Application is not having a UI interface, to play with as of now, but a fully functional API

The Application API is open for use and does not require any keys or tokens to connect with



