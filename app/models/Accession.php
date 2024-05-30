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
     * @return void
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
            // Convert date strings to MySQL date format
            $startDate = date('Y-m-d', strtotime($startDate));
            $endDate = date('Y-m-d', strtotime($endDate));
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
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('CSPC Logo');
        $drawing->setDescription('CSPC Logo');
        $drawing->setPath('../assets/cspc_logo.png');
        $drawing->setHeight(100);
        $drawing->setCoordinates('A1');
        $drawing->setWorksheet($sheet);

        $sheet->setCellValue('B2', 'Republic of the Philippines')
            ->setCellValue('B3', 'CAMARINES SUR POLYTECHNIC COLLEGES')
            ->setCellValue('B4', 'Nabua, Camarines Sur');

        // Merge cells for the description
        $sheet->mergeCells('B2:H2');
        $sheet->mergeCells('B3:H3');
        $sheet->mergeCells('B4:H4');

        // Add table headers
        $sheet->setCellValue('A6', 'ACCESSION RECORD');
        $sheet->mergeCells('A6:M6');

        $headers = [
            'Accession Number', 'DATE RECEIVED', 'CLASS', 'AUTHOR', 'TITLE OF BOOK', 'EDITION', 'VOLUMES', 'PAGES',
            'SOURCE OF FUND', 'COST', 'PUBLISHER', 'YEAR', 'REMARKS'
        ];

        $columnIndex = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($columnIndex . '7', $header);
            $columnIndex++;
        }

        // Add data rows
        $rowIndex = 8;
        foreach ($accessions as $accession) {
            $columnIndex = 'A';
            $sheet->setCellValue($columnIndex++ . $rowIndex, $accession['accession_number']);
            $sheet->setCellValue($columnIndex++ . $rowIndex, $accession['date_received']);
            $sheet->setCellValue($columnIndex++ . $rowIndex, $accession['type']);
            $sheet->setCellValue($columnIndex++ . $rowIndex, $accession['author']);
            $sheet->setCellValue($columnIndex++ . $rowIndex, $accession['title']);
            $sheet->setCellValue($columnIndex++ . $rowIndex, $accession['edition']);
            $sheet->setCellValue($columnIndex++ . $rowIndex, $accession['volumes']);
            $sheet->setCellValue($columnIndex++ . $rowIndex, $accession['pages']);
            $sheet->setCellValue($columnIndex++ . $rowIndex, $accession['source_of_fund']);
            $sheet->setCellValue($columnIndex++ . $rowIndex, $accession['cost_price']);
            $sheet->setCellValue($columnIndex++ . $rowIndex, $accession['publisher']);
            $sheet->setCellValue($columnIndex++ . $rowIndex, $accession['dateaccession']);
            $sheet->setCellValue($columnIndex++ . $rowIndex, $accession['remarks']);
            $rowIndex++;
        }

        // Set column widths
        foreach (range('A', 'M') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Apply styles
        $headerStyleArray = [
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF4F81BD'],
            ],
        ];

        $sheet->getStyle('A7:M7')->applyFromArray($headerStyleArray);

        $writer = new Xlsx($spreadsheet);
        $filename = 'accession_report_' . date('Ymd') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $writer->save('php://output');
        exit();
    }
}
?>
