
<?php
require('../fpdf186/fpdf.php');
require '../Models/RequestModel.php';
$requestModel = new RequestModel();
$request = $requestModel->getRequestById($_GET['request_id']);
$pdf = new FPDF('P', 'mm', 'Letter');

$pdf->AddPage();



// Set font
$pdf->SetFont('Arial', 'B', 25);
$pdf->SetTextColor(255, 0, 0);

// Title
$pdf->Image('../assets/img/logo01.png', 1, 10, 28, 28, 'PNG');
$pdf->Image('../assets/img/logo02.png', 180, 10, 33, 33, 'PNG');

$pdf->Cell(193, 10, 'PANABO CITY DIAGNOSTIC CENTER', 0, 1, 'C');
$pdf->SetFont('Arial', 'B', 15);
$pdf->SetTextColor(0, 0, 0);

$pdf->Cell(190, 3, 'PARTNERSHIP, COMMITMENT, DEVOTION, AND CARE', 0, 1, 'C');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(190, 5, 'panabo.diagnostic@yahoo/gmail.com', 0, 1, 'C');
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(190, 5, 'PLDT Landline:(084) 217-3824', 0, 1, 'C');
$pdf->Cell(190, 5, '_____________________________________________________', 0, 1, 'C');
$pdf->SetFont('Arial', 'B', 15);
$pdf->Cell(190, 8, 'MEDICAL LABORATORY', 0, 1, 'C', $pdf->SetTextColor(135, 206, 235));

// Line break


// Invoice details

$pdf->Ln(0);

// Table header


// Table rows
$pdf->SetFont('Arial', '', 12);

$pdf->Cell(40, 10, 'Firstname', 1);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(75, 10, $request->patient->first_name, 1);
$pdf->SetTextColor(135, 206, 235);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(30, 10, 'Family Name', 1);
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(50, 10, $request->patient->last_name, 1);
$pdf->Ln(10);
$pdf->SetTextColor(135, 206, 235);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(40, 10, 'Address', 1);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 14);
$pdf->Cell(75, 10, $request->patient->city, 1);
$pdf->SetTextColor(135, 206, 235);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(30, 10, 'Age/Gender', 1);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 14);
$age = $request->patient->age;
$gender = $request->patient->gender;
$pdf->Cell(50, 10, "$age/$gender", 1);
$pdf->Ln(10);
$pdf->SetTextColor(135, 206, 235);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(40, 10, 'Date Performed', 1);
$pdf->SetTextColor(0, 0, 0);


$result_date = date("F d, Y", strtotime($request->result_date));
$pdf->SetFont('Arial', '', 14);
$pdf->Cell(75, 10, $result_date, 1);
$pdf->SetTextColor(135, 206, 235);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(30, 10, 'Physician', 1);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 14);
$pdf->Cell(50, 10, 'MD', 1);
$pdf->Ln(10);
//$pdf->SetTextColor(135, 206, 235);
//$pdf->SetFont('Arial', '', 12);
//$pdf->Cell(40, 10, 'Mode of Test', 1);
//$pdf->SetTextColor(0,0,0);
//$pdf->SetFont('Arial', '', 14);
//$pdf->Cell(75, 10, 'Quantitative', 1);
//$pdf->SetTextColor(135, 206, 235);
//$pdf->SetFont('Arial', '', 12);
//$pdf->Cell(30, 10, 'Time', 1);
//$pdf->SetTextColor(0,0,0);
//$pdf->SetFont('Arial', '', 14);
//$pdf->Cell(50, 10, '07:30 am', 1);
//$pdf->Ln(10);
//$pdf->SetTextColor(135, 206, 235);
//$pdf->SetFont('Arial', '', 12);

$pdf->Ln(8);


// Total
$pdf->SetTextColor(135, 206, 235);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(70, 8, 'Service', 1, 0, 'C');

$pdf->Cell(55, 8, 'Result', 1, 0, 'C');
$pdf->Cell(70, 8, 'Normal Value', 1, 0, 'C');
$pdf->Ln(8);
$pdf->SetTextColor(0, 0, 0);
foreach ($request->services as $service) {

  $pdf->SetTextColor(50, 200, 50);
  $pdf->Cell(70, 8, $service->name, 1, 0, 'C');

  $pdf->SetTextColor(0, 0, 0);
  $pdf->SetFont('Arial', 'B', 14);
  $pdf->Cell(55, 8, $service->result, 1, 0, 'C');

  $pdf->SetFont('Arial', '', 14);
  $pdf->SetTextColor(50, 200, 50);
  $pdf->Cell(70, 8, $service->normal_value, 1, 0, 'C');
  $pdf->SetFont('Arial', '', 14);
  $pdf->Ln(8);
  $pdf->SetTextColor(0, 0, 0);
}
$pdf->Ln(15);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(90, 3, 'Alejandro L Domingo Jr., MD, FPSP, APCP', 0, 0);
$pdf->Cell(55, 3, 'Marie Mhar M. Turado', 0, 0);
$pdf->Cell(190, 3, 'Elpidio Carcallas-Nuyad, RMT', 0, 1);
$pdf->Cell(90, -2, '____________________________________', 0, 0);
$pdf->Cell(55, -2, '___________________', 0, 0);
$pdf->Cell(55, -2, '__________________________', 0, 1);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(20, 10, '', 0, 0);
$pdf->Cell(80, 10, 'P.R.C Number:0066658', 0, 0);
$pdf->Cell(55, 10, 'PCDC Staff', 0, 0);
$pdf->Cell(150, 10, 'P.R.C Number:0030677', 0, 1);
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetTextColor(135, 206, 235);
$pdf->Cell(25, 10, '', 0, 0);
$pdf->Cell(71, -2, 'Pathologist', 0, 0);
$pdf->Cell(55, -2, 'Checked by:', 0, 0);
$pdf->Cell(150, -2, 'Medical Technologist', 0, 0);
// Output the PDF
$pdf->Output('invoice.pdf', 'I');

?>