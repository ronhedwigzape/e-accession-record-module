<?php

require_once 'User.php';
require_once 'Accession.php';

class Admin extends User
{
    /***************************************************************************
     * Admin constructor
     *
     * @param string $username
     * @param string $password
     */
    public function __construct($username = '', $password = '')
    {
        parent::__construct($username, $password, 'admin');
    }

    /***************************************************************************
     * Execute find
     *
     * @param $stmt
     * @return Admin|false
     */
    private static function executeFind($stmt)
    {
        $stmt->execute();
        $result = $stmt->get_result();
        if($row = $result->fetch_assoc())
            return new Admin($row['username'], $row['password']);
        else
            return false;
    }

    /***************************************************************************
     * Find Admin by id
     *
     * @param int $id
     * @return Admin|boolean
     */
    public static function findById($id)
    {
        $admin = new Admin();
        $stmt = $admin->conn->prepare("SELECT username, password FROM $admin->table WHERE id = ?");
        $stmt->bind_param("i", $id);
        return self::executeFind($stmt);
    }

    /***************************************************************************
     * Convert Admin object to array
     *
     * @param array $append
     * @return array
     */
    public function toArray($append = [])
    {
        return parent::toArray($append);
    }

    /***************************************************************************
     * Get all admins as array of objects
     *
     * @return Admin[]
     */
    public static function all()
    {
        $admin = new Admin();
        $sql = "SELECT username, password FROM $admin->table ORDER BY id";
        $stmt = $admin->conn->prepare($sql);
        $stmt->execute();

        $result = $stmt->get_result();
        $admins = [];
        while($row = $result->fetch_assoc()) {
            $admins[] = new Admin($row['username'], $row['password']);
        }
        return $admins;
    }

    /***************************************************************************
     * Get all admins as array of arrays
     *
     * @return array
     */
    public static function rows()
    {
        $admins = [];
        foreach(self::all() as $admin) {
            $admins[] = $admin->toArray();
        }
        return $admins;
    }

    /***************************************************************************
     * Save an accession record
     *
     * @param array $data
     * @return bool
     */
    public function saveAccession($data)
    {
        $accession = new Accession($data);
        return $accession->save();
    }

    /***************************************************************************
     * Load an accession record by id
     *
     * @param int $id
     * @return Accession|false
     */
    public function loadAccession($id)
    {
        return Accession::findById($id);
    }

    /***************************************************************************
     * Load all accession records
     *
     * @return Accession[]
     */
    public function loadAllAccessions()
    {
        return Accession::all();
    }

    /***************************************************************************
     * Load all accession records as rows
     *
     * @return array
     */
    public function loadAllAccessionRows()
    {
        require_once 'Accession.php';

        return Accession::rows();
    }

    /***************************************************************************
     * Delete an accession record by id
     *
     * @param int $id
     * @return bool
     */
    public function deleteAccession($id)
    {
        $accession = Accession::findById($id);
        if ($accession) {
            return $accession->destroy();
        }
        return false;
    }
}
?>