Description
------------------
ProspectEye most crucial part of the system is the visitorlist. ProspectEye Customers login to the system and wants to look at the list in order to see visitors/leads that have visited the customers website.

The customers can setup any form of filter that they want to look at (ex. all visitors that have looked at the productpage). Filters have different complexity and we allow our customers to make were difficult ones for the database to query.

We update the data for every filter every 5 minute.

Assignment
------------------
With the help of a cache, we want to cache the data between the 5 minute intervals in order to serve our customers faster and avoid necessary load to the MySQL database. The cache should be written i Redis or Memcached and you will use PHP to for all backend programming.

The schema will be:
User -> Cache <--> MySQL

It will also be unnecassary to cache every filter because not every filter will be queried at all during a day. There have to be logic to update and store cache data depending on heavily used filter/queries

Prequisites
------------------
the database have a table called visit. This table contains all the visits for every customer. The are separeted by accountid and the all have a timestamp.

Structure of visit
(Id, VisitTime, VisitorName, Score, PageviewCount, RefererUrl, CountryCode, CityName)

This is the table that needs a cache. For the sake of the complexity of this assignment we won't take filters into account.

There are some small embryos for the PHP code that can be used for start.

Good luck!
