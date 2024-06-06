<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\RichText\RichText;

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
     * Get all accession records and return as JSON
     *
     * @return string
     */
    public static function fetchAllAccessions()
    {
        $accessions = self::rows();
        return json_encode($accessions);
    }

    /***************************************************************************
     * Generate an Excel report of all accession records
     *
     * @param string|null $type
     * @param string|null $department
     * @param string|null $start_date
     * @param string|null $end_date
     * @return void
     */
    public static function generateReport($type = null, $department = null, $start_date = null, $end_date = null)
{
        $accessions = json_decode(self::fetchAllAccessions(), true);

        // Filter accessions based on type, department, and date range
        $filteredAccessions = array_filter($accessions, function($accession) use ($type, $department, $start_date, $end_date) {
            $dateaccession = strtotime($accession['dateaccession']);
            $startDate = $start_date ? strtotime($start_date) : null;
            $endDate = $end_date ? strtotime($end_date) : null;

            $typeMatch = $type ? $accession['type'] === $type : true;
            $departmentMatch = $department ? $accession['department'] === $department : true;
            $dateMatch = true;

            if ($startDate && $endDate) {
                $dateMatch = $dateaccession >= $startDate && $dateaccession <= $endDate;
            } elseif ($startDate) {
                $dateMatch = $dateaccession >= $startDate;
            } elseif ($endDate) {
                $dateMatch = $dateaccession <= $endDate;
            }

            return $typeMatch && $departmentMatch && $dateMatch;
        });

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Add logo with padding
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo');
        $drawing->setPath('../assets/cspc_logo.png');
        $drawing->setHeight(100);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(30);
        $drawing->setOffsetY(10);
        $drawing->setWorksheet($sheet);

        // Add text box simulation by merging cells and applying styles
        $sheet->mergeCells('B1:D3');
        $richText = new RichText();

        $text1 = $richText->createTextRun('Republic of the Philippines' . "\n");
        $text1->getFont()->setSize(12);

        $boldText = $richText->createTextRun('CAMARINES SUR POLYTECHNIC COLLEGES');
        $boldText->getFont()->setBold(true)->setSize(14);

        $text2 = $richText->createTextRun("\nNabua, Camarines Sur");
        $text2->getFont()->setSize(12);

        $sheet->setCellValue('B1', $richText);
        $sheet->getStyle('B1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('B1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('B1')->getAlignment()->setWrapText(true);

        // Set title and header
        $sheet->mergeCells('A4:P4');
        $sheet->setCellValue('A4', 'ACCESSION RECORD');
        $sheet->getStyle('A4')->getFont()->setBold(true);
        $sheet->getStyle('A4')->getFont()->setSize(18);
        $sheet->getStyle('A4:P4')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK)->getColor()->setARGB('FF0070C0');

        // Set specific row heights for rows 1 to 3 and row 4
        $sheet->getRowDimension(1)->setRowHeight(30);
        $sheet->getRowDimension(2)->setRowHeight(30);
        $sheet->getRowDimension(3)->setRowHeight(30);
        $sheet->getRowDimension(4)->setRowHeight(40);

        // Headers
        $headers = [
            'ACCESSION NUMBER', 'DATE ACCESSIONED', 'VOLUME', 'ISBN', 'AUTHOR', 'TITLE', 'EDITION', 'PAGES', 'COPYRIGHT',
            'PUBLISHER', 'PLACE OF PUBLICATION', 'DEPARTMENT', 'TYPE', 'COST PRICE', 'SOURCE OF FUND', 'REMARKS'
        ];

        // Set headers in the fifth row
        $columnIndex = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($columnIndex . '5', $header);
            $sheet->getStyle($columnIndex . '5')->getFont()->setBold(true);
            $sheet->getStyle($columnIndex . '5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($columnIndex . '5')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $sheet->getStyle($columnIndex . '5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF00B0F0');
            $columnIndex++;
        }

        $rowIndex = 6;
        foreach ($filteredAccessions as $accession) {
            $columnIndex = 'A';
            $sheet->setCellValue($columnIndex++ . $rowIndex, $accession['accession_number']);
            $sheet->setCellValue($columnIndex++ . $rowIndex, $accession['dateaccession']);
            $sheet->setCellValue($columnIndex++ . $rowIndex, $accession['volumes']);
            $sheet->setCellValueExplicit($columnIndex++ . $rowIndex, $accession['isbn'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue($columnIndex++ . $rowIndex, $accession['author']);
            $sheet->setCellValue($columnIndex++ . $rowIndex, $accession['title']);
            $sheet->setCellValue($columnIndex++ . $rowIndex, $accession['edition']);
            $sheet->setCellValue($columnIndex++ . $rowIndex, $accession['pages']);
            $sheet->setCellValue($columnIndex++ . $rowIndex, $accession['copyright']);
            $sheet->setCellValue($columnIndex++ . $rowIndex, $accession['publisher']);
            $sheet->setCellValue($columnIndex++ . $rowIndex, $accession['publicationPlace']);
            $sheet->setCellValue($columnIndex++ . $rowIndex, $accession['department']);
            $sheet->setCellValue($columnIndex++ . $rowIndex, $accession['type']);
            $sheet->setCellValue($columnIndex++ . $rowIndex, $accession['cost_price']);
            $sheet->setCellValue($columnIndex++ . $rowIndex, $accession['source_of_fund']);
            $sheet->setCellValue($columnIndex++ . $rowIndex, $accession['remarks']);

            // Adjust row height based on title length
            $titleLength = strlen($accession['title']);
            $rowHeight = 45; // Default row height
            if ($titleLength > 100) {
                $rowHeight = 85;
            } else if ($titleLength > 50) {
                $rowHeight = 70;
            } else if ($titleLength > 30) {
                $rowHeight = 50;
            }
            $sheet->getRowDimension($rowIndex)->setRowHeight($rowHeight);

            $rowIndex++;
        }

        // Set column widths to the same size
        foreach (range('A', 'P') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(20);
        }

        // Set border styles
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('A5:P' . ($rowIndex - 1))->applyFromArray($styleArray);

        // Set alignment and padding for all cells
        $sheet->getStyle('A4:P' . ($rowIndex - 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A4:P' . ($rowIndex - 1))->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A4:P' . ($rowIndex - 1))->getAlignment()->setWrapText(true);

        // Set zoom scale to 85%
        $sheet->getSheetView()->setZoomScale(85);

        $writer = new Xlsx($spreadsheet);
        $filename = 'Accession_Report_' . date('Ymd') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $writer->save('php://output');
    }
}
?>
