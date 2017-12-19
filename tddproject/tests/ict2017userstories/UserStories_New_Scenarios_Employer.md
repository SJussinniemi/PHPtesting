# USER STORY 01

`As an employer I want to get summary of totals by workshift by employee.`

* Shift breaks total (time one workshift can have multiple breaks)
* Travel total time (one workshift can have multiple travels)
* Workshift total time (workshift - breaks total - travels total)
* Workshift total time minus lunchbreak (workshift - breaks total - travels total - lunchbreak) Lunchbreak is not workshift property but just a number that need to be taken account sometimes.
* Total price by product rows.
* Total price of all products.
* Total costs by allowance rows.
* Total costs of all allowances.

After this user story, it could be good place to check the software design and if needed, make some refactoring. Everything else is calculated from these values.

This could be also good phase to start thing how to store and retrieve information from database.


# USER STORY 02

`As an employer I want to get summary of totals by workday by employee`

Above story is similar to workshift totals but now we want calculate everythin by date. If one day include multiple workshifts, everything should be sum up. In scenario where workshift end on next day, workshift date can be the date when it was started.

Totals should include same calculations than in single workshift and implement a few more calculations.

* Ylityö (overtime). If total hours for day are greater than normal working hours limit, it should be mark as overtime hours.
* Vuorokautinen ylityö. (yhteensä, 50%, 100%)
* Iltatyö, yötyö

More information: http://www.tyosuojelu.fi/documents/14660/338901/Ty%C3%B6ajan+seuranta/9ccc4141-d479-444f-ae92-501e5218b2a8
http://www.tyosuojelu.fi/tyosuhde/tyoaika/lisa-jaylityot
https://www.erto.fi/tyosuhdeopas/tyoaika/lisaetyoe-ja-ylityoe

# USER STORY 03

`As an employer I want to get summary of totals by week by employee`

Same totals as above and followings:

* Viikottainen ylityö. (yhteensä, 50%, 100%)

Combinations by month and year should be possible to do with same logic. 