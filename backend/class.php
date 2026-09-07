<?php
class Database
{
    private $conn;

    // ✅ Constructor connects to DB
    public function __construct($databaseName)
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "$databaseName";

        $this->conn = mysqli_connect($servername, $username, $password, $dbname);

        if (!$this->conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    }

    public function selectTable($tablename)
    {
        $sql = "SELECT * FROM `$tablename`";
        $result = mysqli_query($this->conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {

            // ✅ Get all column names dynamically
            $columns = array_keys(mysqli_fetch_assoc($result));
            mysqli_data_seek($result, 0); // Reset pointer to first row

            echo "<tbody>";

            // 🔹 Map of foreign key columns to lookup table & column
            $foreignKeys = [
                "vendor"   => ["users", "name"],
                "customer" => ["users", "name"],
                "item"     => ["items", "name"]
            ];

            // ✅ Loop through all rows dynamically
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                foreach ($columns as $col) {

                    // 🔹 If the column is a foreign key, replace ID with name
                    if (isset($foreignKeys[$col])) {
                        [$table, $colName] = $foreignKeys[$col];
                        $id = $row[$col];
                        $value = $this->selectOneWhere($table, $colName, "id='$id'");
                        echo "<td>" . htmlspecialchars($value ?? $id) . "</td>";
                    } else {
                        // default output
                        echo "<td>" . htmlspecialchars($row[$col]) . "</td>";
                    }
                }

                // ✅ Add Update & Delete buttons automatically
                echo "<td>
                <a href='" . $tablename . "_view.php?editid=" . $row["id"] . "' 
                   class='btn btn-warning btn-sm text-dark btn-update-" . $tablename . "'>
                   ✏️ Update
                </a>
                <a href='delete.php?deleteid=" . $row["id"] . "' 
                   class='btn btn-danger btn-sm text-dark'>
                   🗑️ Delete
                </a>
              </td>";

                echo "</tr>";
            }

            echo "</tbody>";
        } else {
            echo "<p class='text-danger'>No records found in table <b>$tablename</b>.</p>";
        }
    }


    public function selectOneWhere($table, $column, $condition)
    {
        $sql = "SELECT `$column` FROM `$table` WHERE $condition LIMIT 1";
        $result = mysqli_query($this->conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            return $row[$column];   // return value
        }

        return null; // not found
    }

    // select from
    public function selectAllWhere($table, $condition)
    {

        $sql = "SELECT * FROM `$table` WHERE $condition";
        $result = mysqli_query($this->conn, $sql);

        $data = [];
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
        }
        return $data;
    }



    /**
     * Execute a raw SQL query
     * @param string $sql The raw SQL string
     * @return mixed Array of rows for SELECT, boolean/ID for others
     */
    public function rawQuery($sql)
    {
        $result = mysqli_query($this->conn, $sql);

        // If query failed
        if (!$result) {
            die("Raw Query Error: " . mysqli_error($this->conn) . " | SQL: " . $sql);
        }

        // If it's a SELECT, SHOW, DESCRIBE or EXPLAIN query, return the data
        if ($result instanceof mysqli_result) {
            $data = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
            mysqli_free_result($result);
            return $data;
        }

        // For INSERT, UPDATE, DELETE, etc., return true
        return true;
    }


    public function safeQuery($sql, $params = [], $types = "")
    {
        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            die("Statement Error: " . mysqli_error($this->conn));
        }

        if (!empty($params)) {
            // If types are not provided, assume they are all strings
            $types = $types ?: str_repeat("s", count($params));
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result) {
            $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
            mysqli_free_result($result);
            return $data;
        }

        return mysqli_stmt_affected_rows($stmt);
    }

    public function getTotalQuantity($table, $columnToSum, $whereColumn, $whereValue)
    {
        // Escape values to prevent SQL injection
        $whereValue = mysqli_real_escape_string($this->conn, $whereValue);

        $sql = "SELECT SUM(`$columnToSum`) AS total 
            FROM `$table` 
            WHERE `$whereColumn` = '$whereValue' 
            GROUP BY `$whereColumn`";

        $result = mysqli_query($this->conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            return (float)$row['total']; // return sum
        }

        return 0; // return 0 if no rows found
    }

    public function selectColumn($table, $column)
    {
        $sql = "SELECT `$column` FROM `$table`";
        $result = mysqli_query($this->conn, $sql);

        $data = [];
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row[$column];
            }
        }
        return $data;
    }
    public function selectAll($table)
    {
        $sql = "SELECT * FROM `$table`";
        $result = mysqli_query($this->conn, $sql);

        $data = [];
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;   // return complete row
            }
        }
        return $data;
    }
        
    public function selectColumnWhere($table, $column, $condition)
    {
        $sql = "SELECT `$column` FROM `$table` WHERE $condition";
        $result = mysqli_query($this->conn, $sql);

        $data = [];
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row[$column];
            }
        }
        return $data;
    }



    function update($Id, $arr, $table)
    {
        // Get column names
        // $cols = implode(",", array_keys($arr));
        // $val = array();

        $vals = array();

        foreach ($arr as $keys => $values) {
            $vals[] = "`$keys`='" . addslashes($values) . "'";
        }




        $cols = implode(",", $vals);
        // echo $cols;


        $sql = "UPDATE $table SET $cols WHERE ID = '$Id'";


        if (mysqli_query($this->conn, $sql)) {
            return true;
        } else {
            echo "Error: " . mysqli_error($this->conn);
            return false;
        }
    }

    public function updateWhere($table, $data, $condition)
{
    // Build the "column = value" part of the query
    $updateParts = [];
    foreach ($data as $column => $value) {
        $updateParts[] = "`$column` = '$value'";
    }
    $updateString = implode(', ', $updateParts);

    $sql = "UPDATE `$table` SET $updateString WHERE $condition";
    
    return mysqli_query($this->conn, $sql);
}
    // ✅ Insert method
    function insert($arr, $table)
    {
        // Get column names
        $cols = implode(",", array_keys($arr));

        // Get values and escape them properly
        $vals = array();
        foreach ($arr as $key => $value) {
            $vals[] = "'" . addslashes($value) . "'";
        }

        // Join all values with commas
        $vals_str = implode(",", $vals);

        // Build SQL query
        $sql = "INSERT INTO $table ($cols) VALUES ($vals_str)";

        // (Optional) Run the query if you have a connection, example:
        // global $conn;
        // mysqli_query($conn, $sql);


        if ($this->conn->query($sql) === TRUE) {
            // ✅ You can now access insert_id
            return $this->conn->insert_id;
        } else {
            die("Error: " . $this->conn->error);
        }
    }

    function delete($Id, $table)
    {
        // Added 'intval' for safety to prevent SQL injection
        $safeId = intval($Id);
        $sql = "DELETE FROM `$table` WHERE `id` = $safeId";

        if (mysqli_query($this->conn, $sql)) {
            return true; // Just return true so the script can continue
        } else {
            echo "Error: " . mysqli_error($this->conn);
            return false;
        }
    }

    public function deleteWhere($table, $condition)
    {
        $sql = "DELETE FROM `$table` WHERE $condition";
        return mysqli_query($this->conn, $sql);
    }

    function autogencode($table, $col, $cod)
    {
        $sql = "SELECT $col FROM $table ORDER BY id DESC LIMIT 1";
        $result = mysqli_query($this->conn, $sql);

        if (!$result) {
            die("Query failed: " . mysqli_error($this->conn));
        }

        if (mysqli_num_rows($result) > 0) {
            // mysqli_fetch_assoc() is a PHP function used to fetch one row from a MySQL query result as an associative array.
            $row = mysqli_fetch_assoc($result);

            $lastCode = $row[$col];

            if (!empty($lastCode)) {
                // ✅ Remove prefix safely

                // str_replace(search, replace, string);
                $numericPart = str_replace($cod, '', $lastCode);

                // ✅ Convert to integer and increment
                $newNumericPart = (int)$numericPart + 1;

                // ✅ Return new formatted code
                return $cod . str_pad($newNumericPart, 3, '0', STR_PAD_LEFT);
            }
        }

        // ✅ If no previous record exists, start from prefix + 001
        return $cod . '001';
    }
}
