<?php
class reservation
{
    private $conn;
    private $table = 'reservations';

    //Reservation properties
    public $fname;
    public $lname;
    public $date;
    public $time;
    public $Guest_Number;
    public $email;
    public $table_type;
    public $note;
    public $placement;

    //Get Reservation data
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function read()
    {
        $query = 'SELECT
            Guest_Number,
            fname,
            lname,
            date,
            time,
            email,
            table_type,
            note,
            placement
        FROM
            ' . $this->table;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    // Get Single reservation data
    public function read_single()
    {
        $query = 'SELECT
            Guest_Number,
            fname,
            lname,
            date,
            time,
            email,
            table_type,
            note,
            placement
        FROM
            ' . $this->table . ' r
        WHERE
            Guest_Number=?
        LIMIT 0,1';

        // Prepare Statement
        $stmt = $this->conn->prepare($query);

        // Bind Guest Number parameter
        $stmt->bindParam(1, $this->Guest_Number);

        // Execute query
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->fname = $row['fname'];
        $this->lname = $row['lname'];
        $this->date = $row['date'];
        $this->time = $row['time'];
        $this->email = $row['email'];
        $this->table_type = $row['table_type'];
        $this->note = $row['note'];
        $this->placement = $row['placement'];
    }

    public function create()
    {
    // Create query
    $query = 'INSERT INTO ' . $this->table . '
        SET
            Guest_Number = ?,
            fname = ?,
            lname = ?,
            date = ?,
            time = ?,
            email = ?,
            table_type = ?,
            note = ?,
            placement = ?';

    // Prepare Statement
    $stmt = $this->conn->prepare($query);

    // Clean data
    $this->Guest_Number = htmlspecialchars(strip_tags($this->Guest_Number));
    $this->fname = htmlspecialchars(strip_tags($this->fname));
    $this->lname = htmlspecialchars(strip_tags($this->lname));
    $this->date = htmlspecialchars(strip_tags($this->date));
    $this->time = htmlspecialchars(strip_tags($this->time));
    $this->email = htmlspecialchars(strip_tags($this->email));
    $this->table_type = htmlspecialchars(strip_tags($this->table_type));
    $this->note = htmlspecialchars(strip_tags($this->note));
    $this->placement = htmlspecialchars(strip_tags($this->placement));

    // Bind reservation data
    $stmt->bindParam(1, $this->Guest_Number);
    $stmt->bindParam(2, $this->fname);
    $stmt->bindParam(3, $this->lname);
    $stmt->bindParam(4, $this->date);
    $stmt->bindParam(5, $this->time);
    $stmt->bindParam(6, $this->email);
    $stmt->bindParam(7, $this->table_type);
    $stmt->bindParam(8, $this->note);
    $stmt->bindParam(9, $this->placement);

    // Execute query
    if ($stmt->execute()) {
        return true;
    }

    // Print error if something went wrong
    printf("Error: %s.\n", $stmt->error);

    return false;
    }

    
    public function update()
    {
    // Create query
    $query = 'UPDATE ' . $this->table . '
        SET
            Guest_Number = ?,
            fname = ?,
            lname = ?,
            date = ?,
            time = ?,
            email = ?,
            table_type = ?,
            note = ?,
            placement = ?
        WHERE
            Guest_Number = ?';

    // Prepare Statement
    $stmt = $this->conn->prepare($query);

    // Clean data
    $this->Guest_Number = htmlspecialchars(strip_tags($this->Guest_Number));
    $this->fname = htmlspecialchars(strip_tags($this->fname));
    $this->lname = htmlspecialchars(strip_tags($this->lname));
    $this->date = htmlspecialchars(strip_tags($this->date));
    $this->time = htmlspecialchars(strip_tags($this->time));
    $this->email = htmlspecialchars(strip_tags($this->email));
    $this->table_type = htmlspecialchars(strip_tags($this->table_type));
    $this->note = htmlspecialchars(strip_tags($this->note));
    $this->placement = htmlspecialchars(strip_tags($this->placement));

    // Bind reservation data
    $stmt->bindParam(1, $this->Guest_Number);
    $stmt->bindParam(2, $this->fname);
    $stmt->bindParam(3, $this->lname);
    $stmt->bindParam(4, $this->date);
    $stmt->bindParam(5, $this->time);
    $stmt->bindParam(6, $this->email);
    $stmt->bindParam(7, $this->table_type);
    $stmt->bindParam(8, $this->note);
    $stmt->bindParam(9, $this->placement);
    $stmt->bindParam(10, $this->Guest_Number); // Add this line to bind the Guest_Number for the WHERE clause

    // Execute query
    if ($stmt->execute()) {
        return true;
    }

    // Print error if something went wrong
    printf("Error: %s.\n", $stmt->error);

    return false;
    }

    //Delete reservation
    public function delete(){
        //Create query
        $query = 'DELETE FROM'.$this->table.'WHERE Guest_Number = :Guest_Number';

        //Prepare statement
        $stmt= $this->conn->prepare($query);

        //Clean data
        $this->Guest_Number = htmlspecialchars(strip_tags($this->Guest_Number));

        //Bind data
        $stmt->bindParam(':Guest_Number', $this->Guest_Number);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }

        // Print error if something went wrong
        printf("Error: %s.\n", $stmt->error);

        return false;
    }

}

