# gcmwetter

This project contains the complete code from our wheater station. there are some education materials, too.

## Structure
The system has severeal layers. The sensors are sending their values to the receiver, which is connected via usb with a raspberry pi. the pi stores the records as json and, if he reaches the api will send each record to the api. The api pipes everything to a database, using a database-manager, which is not part of this code. The users will visit the frontend, which mixes the last records with some education material. The database is documented although.

| subdir        | content |
| ------------- |------------:| 
| api | the PHP-Api as an abstraction layer for the database |
| backend | documentation of the database |
| frontend | the website |
| raspi | the code for reading, storing and sending records |
