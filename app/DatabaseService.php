<?php
// app/DatabaseService.php

// app/DatabaseService.php

namespace App;

use Illuminate\Support\Facades\DB;
use RuntimeException;

class DatabaseService
{
    protected $connection;

    protected function connect()
    {
        $host = config('database.connections.mysql.host');
        $port = config('database.connections.mysql.port');
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');

        $this->connection = new \mysqli($host, $username, $password, $database);

        if ($this->connection->connect_error) {
            throw new RuntimeException('Database connection failed: ' . $this->connection->connect_error);
        } else {
            echo "Database connected successfully!!!";
        }
    }

    public function query($sql)
    {
        $result = $this->connection->query($sql);

        if (!$result) {
            throw new RuntimeException('Database query error: ' . $this->connection->error);
        }

        // Fetch the results as needed
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }




    public function close()
    {
        $this->connection->close();
    }
}
