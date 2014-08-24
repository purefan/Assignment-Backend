# Notes to the Solution

* The structure for the table `visit` does not seem to have a reference to a client or an account, but the prototype for the function getVisits in the file FetchVisits.php takes as a parameter $accountId which made me think that perhaps a column in the table was missing.
* Because of the previous point, I edited the table structure and added a column for the accountId and for simplicity assumed there were only 20 accounts.
* Since I needed a way to make sure the script worked I made a function to populate the database with random information. You can find it in the Database class.
* The Log class could have been implemented as a trait but I wasn't sure of which PHP version ProspectEye runs and did not want to risk it.

## Alternative Solution 
Another option for solving this assignment is to implement caching directly into the Database class, converting the query into the key for Memcached and automatically storing the results. This converts the Database class into a "Data Store" object and for the developers its usually simpler to think in terms of "a magical data store" than to worry about cache vs database info. Since Im not familiar with the data retention policy this approach could be complicated to implement, for example if cache needs to be intentionally flushed before the expiration time.