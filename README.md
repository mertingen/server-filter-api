# Server Filtering
The project has a UI that you can filter the server machines according to the resources. You'll see the machine models and their prices. The UI requests the filter API in the same project. When API endpoints are requested their response will be cached into the Redis. The key will be filter query string. If the filter API gets the same request it provides the data from cache. Thus, we don't consume DB a lot and reduce the response time.

### What we have?
- PHP 8.1
- MySQL 8.0
- Redis
- Symfony Framework 6.2

### Warm-up
There is **.env.sample** in the root directory, you should create **.env** file next to it and fulfill these variable to initialize the app.

- APP_ENV=
- APP_SECRET=

- DATABASE_URL=

- REDIS_HOST=
- REDIS_PORT=

First things first, we should have related packages by composer with the following command. (-vvv gives more information as an output in an error case)

```composer instal -vvv```

Maybe there might be issues about PHP extensions, we should have the following ones in the machine that installed PHP.

**php8.1-bcmath**, **php8.1-dom**, **php8.1-redis**, **php8.1-gd**, **php8.1-zip**, **php8.1-pdo**, **php8.1-mysql**

After setting the configuration parameters and installing related packages we need to create DB and run migration file. They'll handle tables and their schema, columns, relations and so on. Let's create the DB with the following command.

```bin/console d:d:c```

You'll see an output regarding created DB successfully. Then, run the following command regarding migrations.

```bin/console doctrine:migrations:migrate```

If everything is ok, let's insert server data into the DB by Symfony command. "servers_filters_assignment" file name and its path an example. You should set them according to your environment. We just give the file path and file headers with "-H".

```bin/console app:save-server /home/mert/filter-api/assets/servers_filters_assignment.csv -H Model,Ram,HDD,Location,Price```

Yey! After inserting server information into the DB we're ready, and you can consume the UI or API endpoints.

### Tests
If you'd like to run the test cases, you can run the following command in the root directory of the project. It requires "dev" environment composer packages to run.

```bin/phpunit```