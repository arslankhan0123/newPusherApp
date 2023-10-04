<!-- For removing node modules -->
rm -rf node_modules
rm node_modules

<!-- For removing cache of node modules -->
npm cache clean --force

<!-- To install node -->
nmp install
npm run dev

<!-- To install express -->
npm install express

<!-- To install cors -->
npm install cors

<!-- To start server.js file  -->
node server.js 


<!-- To install Redis server -->
npm install ioredis
npm install ioredis --save
composer require predis/predis

<!-- env change -->
QUEUE_CONNECTION=sync to QUEUE_CONNECTION=database 
BROADCAST_DRIVER=log to BROADCAST_DRIVER=redis

<!-- config change -->
    'redis' => [
        'client' => env('REDIS_CLIENT', 'phpredis'),
        'cluster' => false,
        'default' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'database' => 0,
            'port' => env('REDIS_PORT', '6379'),
        ]
    ]


<!-- Download zip file of Redis from this link -->
https://github.com/microsoftarchive/redis/releases

<!-- After download file make a folder and extract in a folder and open the folder in CMD like this -->
E:\xampp\htdocs\Redis

<!-- When open CMD then run this command to start the redis -->
redis-server.exe

<!-- then run this command -->
node server.js




php artisan queue:table
php artisan migrate
php artisan queue:restart
php artisan queue:work
