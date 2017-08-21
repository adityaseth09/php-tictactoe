## API Docummentation

### Request /api (Post)

```
{
  "player": "X",
  "boardState": [
    ["X", "O", ""],
    ["O", "", ""],
    ["X", "", "O"]
  ]
}
```

#### player : String (possible values X, O)
The Unit/Player that would be making next Move(Computer/System) 


#### boardState : (possible values of elements X, O or "")
The Current state of Board as a json Array containing a 2 dimensional Array representing the sate of Board

 
### Response

```
{
  "player": "X",
  "boardState": [
    ["X", "O", "X"],
    ["O", "", ""],
    ["X", "", "O"]
  ],
  "state": ""
  "canMove":"True"
  
}
```

#### player : String (possible values X, O)
The Unit/Player that would be making next Move(Computer/System) 


#### boardState : (possible values of elements X, O or "")
The Updated state of Board as a json Array containing a 2 dimensional Array representing the sate of Board
 
### canMove:
True if The move can be made else its false

### state:
Give the current state of the game, In case the api has some erroneous input error text is shown here.


### Response with Result
```
{
    "player":"X",
    "boardState":[
        ["X","O","X"],
        ["O","O","X"],
        ["X","O","O"]
    ],
    "state":"O Wins The Game !!!",
    "canMove":"False"
}
```

