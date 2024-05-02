# Technical Task

## Brief

The task is left fairly vague as we like to see how each person approaches the problem unguided, though feel free to ask questions.

The task is as follows, create a stand-alone PHP app that has:

* Console command to download and import UK postcodes (e.g. http://parlvid.mysociety.org/os/ ) into some kind of database
* A controller action to add a new store/shop to the database with:
  * Name
  * Geo coordinates
  * open/closed status
  * store type (takeaway, shop, restaurant)
  * a max delivery distance.
* Controller action to return stores near to a postcode (latitude, longitude) as a JSON API response, that would be suitable for use by a mobile app
* Controller action to return stores can deliver to a certain postcode as a JSON API response, that would be suitable for use by a mobile app

We're using Laravel framework but feel free to use whatever modern tools you're comfortable with.

The output of task will be reviewed on different things, such as:
* Simplicity
* Maintainability
* Correctness
* Understanding and use of suitable technology
* Performance
* Documentation
* Unit tests
* Security

The task should be submitted as source files (via BitBucket, GitHub).

Please don't spend too long on the task. We're not expecting it to be perfect, so let us know what improvements you might make if you had more time.
