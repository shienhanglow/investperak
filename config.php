<?php
/*
 * investperak.com CMS configuration
 * 1. Copy this file to config.php
 * 2. Fill in the MySQL database details you created in cPanel (MySQL® Databases)
 * 3. Visit https://investperak.com/install.php to create the tables and your admin login
 */
return [
    // MySQL connection (cPanel usually prefixes names with your account, e.g. "cpuser_investperak")
    'db_host' => 'localhost',
    'db_name' => 'investperak_website',
    'db_user' => 'investperak_hang',
    'db_pass' => 'Hang@1911',

    // Where new website enquiries are emailed (leave '' to only keep them in the admin inbox)
    'notify_email' => 'low.alphront@gmail.com',
    // "From" address for those emails. Use a mailbox on your own domain for best delivery.
    'mail_from' => 'no-reply@investperak.com',

    // Time zone for enquiry dates
    'timezone' => 'Asia/Kuala_Lumpur',

    // Set to true only while troubleshooting; shows PHP errors on screen
    'debug' => false,

    // For local testing only: use SQLite instead of MySQL (e.g. __DIR__ . '/data/test.sqlite')
    'sqlite_path' => '',
];
