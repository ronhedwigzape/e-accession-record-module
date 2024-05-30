<?php

require_once __DIR__ . '/../../vendor/autoload.php'; // Adjust the path as needed

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class Accession extends App
{
    // table
    protected $table = 'accession';

    // properties
    protected $id;
    protected $accession_number;
    protected $date_received;
    protected $source_of_fund;
    protected $cost_price;
    protected $remarks;
    protected $isbn;
    protected $dateaccession;
    protected $title;
    protected $author;
    protected $edition;
    protected $volumes;
    protected $pages;
    protected $copyright;
    protected $publisher;
    protected $department;
    protected $copy;
    protected $encoder;
    protected $type;
    protected $publicationPlace;
    protected $call_no;

    /***************************************************************************
     * Accession constructor
     *
     * @param array $data
     */
    public function __construct($data = [])
    {
        parent::__construct();
        $this->fill($data);
    }

    /***************************************************************************
     * Fill the properties with data
     *
     * @param array $data
     * @return void
     */
    public function fill($data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    /***************************************************************************
     * Save or update the record in the database
     *
     * @return bool
     */
    public function save()
    {
        if (empty($this->accession_number)) {
            self::returnError('HTTP/1.1 400', 'Accession number is required.');
        }

        if (isset($this->id) && !empty($this->id)) {
            // Update existing record
            $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM $this->table WHERE accession_number = ? AND id != ?");
            $stmt->bind_param("si", $this->accession_number, $this->id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();

            if ($row['count'] > 0) {
                self::returnError('HTTP/1.1 409', 'Accession number already exists.');
            }

            $stmt = $this->conn->prepare("UPDATE $this->table SET accession_number = ?, date_received = ?, source_of_fund = ?, cost_price = ?, remarks = ?, isbn = ?, dateaccession = ?, title = ?, author = ?, edition = ?, volumes = ?, pages = ?, copyright = ?, publisher = ?, department = ?, copy = ?, encoder = ?, type = ?, publicationPlace = ?, call_no = ? WHERE id = ?");
            $stmt->bind_param("ssssssssssssssssssssi", $this->accession_number, $this->date_received, $this->source_of_fund, $this->cost_price, $this->remarks, $this->isbn, $this->dateaccession, $this->title, $this->author, $this->edition, $this->volumes, $this->pages, $this->copyright, $this->publisher, $this->department, $this->copy, $this->encoder, $this->type, $this->publicationPlace, $this->call_no, $this->id);
            return $stmt->execute();
        } else {
            // Create new record
            $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM $this->table WHERE accession_number = ?");
            $stmt->bind_param("s", $this->accession_number);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();

            if ($row['count'] > 0) {
                self::returnError('HTTP/1.1 409', 'Accession number already exists.');
            }

            $stmt = $this->conn->prepare("INSERT INTO $this->table (accession_number, date_received, source_of_fund, cost_price, remarks, isbn, dateaccession, title, author, edition, volumes, pages, copyright, publisher, department, copy, encoder, type, publicationPlace, call_no) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssssssssssssssss", $this->accession_number, $this->date_received, $this->source_of_fund, $this->cost_price, $this->remarks, $this->isbn, $this->dateaccession, $this->title, $this->author, $this->edition, $this->volumes, $this->pages, $this->copyright, $this->publisher, $this->department, $this->copy, $this->encoder, $this->type, $this->publicationPlace, $this->call_no);
            return $stmt->execute();
        }
    }

    /***************************************************************************
     * Delete the record from the database
     *
     * @return bool
     */
    public function destroy()
    {
        if (empty($this->id)) {
            self::returnError('HTTP/1.1 400', 'ID is required for deletion.');
        }

        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM $this->table WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        if ($row['count'] == 0) {
            self::returnError('HTTP/1.1 404', 'Record not found.');
        }

        $stmt = $this->conn->prepare("DELETE FROM $this->table WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        return $stmt->execute();
    }

    /***************************************************************************
     * Find a record by id
     *
     * @param int $id
     * @return Accession|false
     */
    public static function findById($id)
    {
        $accession = new Accession();
        $stmt = $accession->conn->prepare("SELECT * FROM $accession->table WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return new Accession($row);
        } else {
            return false;
        }
    }

    /***************************************************************************
     * Get all records as array of objects
     *
     * @return Accession[]
     */
    public static function all()
    {
        $accession = new Accession();
        $sql = "SELECT * FROM $accession->table ORDER BY id";
        $stmt = $accession->conn->prepare($sql);
        $stmt->execute();

        $result = $stmt->get_result();
        $accessions = [];
        while ($row = $result->fetch_assoc()) {
            $accessions[] = new Accession($row);
        }
        return $accessions;
    }

    /***************************************************************************
     * Get all records as array of arrays
     *
     * @return array
     */
    public static function rows()
    {
        $accessions = [];
        foreach (self::all() as $accession) {
            $accessions[] = $accession->toArray();
        }
        return $accessions;
    }

    /***************************************************************************
     * Convert object to array
     *
     * @param array $append
     * @return array
     */
    public function toArray($append = [])
    {
        $arr = get_object_vars($this);
        unset($arr['conn'], $arr['table']);

        // append
        foreach ($append as $key => $value) {
            $arr[$key] = $value;
        }

        return $arr;
    }

    /***************************************************************************
     * Generate report based on filters
     *
     * @param string $type
     * @param string $department
     * @param string $startDate
     * @param string $endDate
     * @return string
     */
    public static function generateReport($type, $department, $startDate, $endDate)
    {
        $accession = new Accession();
        $query = "SELECT * FROM $accession->table WHERE 1=1";
        $params = [];
        $types = '';

        if ($type) {
            $query .= " AND type = ?";
            $params[] = $type;
            $types .= 's';
        }

        if ($department) {
            $query .= " AND department = ?";
            $params[] = $department;
            $types .= 's';
        }

        if ($startDate && $endDate) {
            $query .= " AND dateaccession BETWEEN ? AND ?";
            $params[] = $startDate;
            $params[] = $endDate;
            $types .= 'ss';
        }

        $stmt = $accession->conn->prepare($query);
        if ($types) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        $accessions = [];
        while ($row = $result->fetch_assoc()) {
            $accessions[] = $row;
        }

        // Generate Excel file using PhpSpreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Add header image and description
        $drawing = new Drawing();
        $drawing->setName('CSPC Logo');
        $drawing->setDescription('CSPC Logo');
        $drawing->setPath('path/to/cspc-logo.png'); // Path to the logo image
        $drawing->setHeight(100);
        $drawing->setCoordinates('A1');
        $drawing->setWorksheet($sheet);

        $sheet->setCellValue('A3', 'Republic of the Philippines')
            ->setCellValue('A4', 'Camarines Sur Polytechnic Colleges')
            ->setCellValue('A5', 'Nabua, Camarines Sur');

        // Merge cells for the description
        $sheet->mergeCells('A3:H3');
        $sheet->mergeCells('A4:H4');
        $sheet->mergeCells('A5:H5');

        // Add table headers
        $sheet->setCellValue('A7', 'Accession Record');
        $sheet->mergeCells('A7:N7');

        $headers = [
            'Accession Number', 'Date Received', 'Class', 'Author', 'Title of Book', 'Edition', 'Volumes', 'Pages',
            'Source of Fund', 'Cost', 'Publisher', 'Year', 'Remarks'
        ];

        $col = 'A';
        $row = 8;
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $row, $header);
            $col++;
        }

        // Add data rows
        $row = 9;
        foreach ($accessions as $accession) {
            $sheet->setCellValue('A' . $row, $accession['accession_number'])
                ->setCellValue('B' . $row, $accession['date_received'])
                ->setCellValue('C' . $row, $accession['type'])
                ->setCellValue('D' . $row, $accession['author'])
                ->setCellValue('E' . $row, $accession['title'])
                ->setCellValue('F' . $row, $accession['edition'])
                ->setCellValue('G' . $row, $accession['volumes'])
                ->setCellValue('H' . $row, $accession['pages'])
                ->setCellValue('I' . $row, $accession['source_of_fund'])
                ->setCellValue('J' . $row, $accession['cost_price'])
                ->setCellValue('K' . $row, $accession['publisher'])
                ->setCellValue('L' . $row, $accession['dateaccession'])
                ->setCellValue('M' . $row, $accession['remarks']);
            $row++;
        }

        // Set column widths
        foreach (range('A', 'M') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Save Excel file
        $writer = new Xlsx($spreadsheet);
        $filePath = __DIR__ . '/../../reports/accession_report.xlsx'; // Adjust the path as needed
        $writer->save($filePath);

        // Return file path for download
        return $filePath;
    }
}
?>
