<?php

namespace App\Controllers;

class Test extends BaseController
{
    public function database()
    {
        $db = \Config\Database::connect();
        
        try {
            if ($db->connect()) {
                echo "✅ Database connection: SUCCESS<br><br>";
                
                $query = $db->query("SELECT name FROM regions");
                $regions = $query->getResultArray();
                
                echo "🌍 Regions found: " . count($regions) . "<br>";
                foreach ($regions as $region) {
                    echo "- " . $region['name'] . "<br>";
                }
            }
        } catch (\Exception $e) {
            echo "❌ Error: " . $e->getMessage();
        }
    }
}