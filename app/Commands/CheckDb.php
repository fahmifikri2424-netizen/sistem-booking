<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CheckDb extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:query';
    protected $description = 'Runs a DB query';
    protected $usage       = 'db:query <sql>';
    protected $arguments   = [
        'sql' => 'The SQL query to run'
    ];

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        $sql = $params[0] ?? '';
        if (!$sql) {
            CLI::error('Please provide a query.');
            return;
        }
        $query = $db->query($sql);
        if (is_bool($query)) {
            CLI::write($query ? 'Success' : 'Failed');
        } else {
            $results = $query->getResultArray();
            foreach($results as $row) {
                CLI::write(print_r($row, true));
            }
        }
    }
}
