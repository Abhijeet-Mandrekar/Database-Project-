<?php
require 'vendor/autoload.php';
include './Partials/_dbconnect.php';
$cid=$_GET['cid'];
$dc=$_GET['dc'];
$cname="SELECT * from `customer` where `c_id`='$cid'";
$cnameres=mysqli_query($conn,$cname);
$cstmr=mysqli_fetch_assoc($cnameres);
$cstmrname=$cstmr['name'];
$cstmrmail=$cstmr['email'];
$k="SELECT max(`sale_no`) as 'max' from `sale` where `status`='cart'";
$kq=mysqli_query($conn,$k);
$kno=mysqli_fetch_assoc($kq);
$sno=$kno['max'];

$s="SELECT * from `sale` where `status`='cart'";
$sq=mysqli_query($conn,$s);
$sd=mysqli_fetch_assoc($sq);
$saledate=$sd['s_date'];
$filename=$sno."-".$cid."-".$saledate.".pdf";

ob_end_clean();
require('fpdf185/fpdf.php');
require_once 'FPDI-master/src/autoload.php';
require_once('FPDI-master/src/fpdi.php');


class PDF extends FPDF{
function Header(){
    //$this->Image('logo.png',10,-1,70);
    $this->SetFont('Arial','B',13);
    // Move to the right
    $this->Cell(80);
    $this->Ln(20);
}
// Page footer
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
}


$display_heading = array('brand'=>'Brand', 'model'=>'Model', 'qantity'=> 'Quantity', 'price'=>'Unit Price', 'amount'=> 'Amount',);
$result = mysqli_query($conn, "SELECT `brand`, `model`, `sale`.`quantity`, `Price`, `amount` FROM `item` join `sale` where `item`.`P_id`=`sale`.`p_id` and `status`='cart'") or die("database error:". mysqli_error($conn));
//$header = array('Brand','Quantity','Unit Price','Amount');

$pdf = new \setasign\Fpdi\Fpdi();
//header
$pdf->AddPage();
$pdf->setSourceFile('./Invoice.pdf');

// We import only page 1
$tpl = $pdf->importPage(1);
// Let's use it as a template from top-left corner to full width and height
$pdf->useTemplate($tpl, 0, 0, null, null);

//foter page
$pdf->AliasNbPages();
$pdf->SetFont('Arial','',12);
// foreach($header as $heading) {
//     $pdf->Cell(40,12,$display_heading[$heading['Field']],1);
// }

$pdf->SetXY(20,38);
$pdf->Cell(20,20,$cstmrname);

$pdf->SetFont('Arial', '', 15);
$pdf->SetXY(132,31.3);
$pdf->Cell(20,20,$saledate);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(7);
$pdf->SetXY(17,75);
foreach($display_heading as $column)
    {$pdf->Cell(35, 7, $column, 1);}

// $pdf->Ln(); // Set current position

// $pdf->SetLineWidth(1);
// $pdf->SetDrawColor(0,0,10);
// $pdf -> Line(10, 20, 200, 20);
$pdf->SetFont('Arial','',12);
$tc=0;
foreach($result as $row) {
    $pdf->Ln();
    $pdf->Cell(7);
    $i=0;
    foreach($row as $column){
        if($i==4){
            $tc=$tc+$column;
        }
        $pdf->Cell(35,12,$column,1);
        $i=$i+1;
    }
}

$pdf->Ln();
$pdf->Cell(110);
$pdf->Cell(20,10,'Total Amount:', 0);
$pdf->Cell(15);
$pdf->Cell(10,10,'Rs.'.$tc, 0);
$pdf->Ln();

if($dc==1){
    $tc-=$tc*0.05;
}
else if($dc==2){
    $tc-=$tc*0.1;
}
else if($dc==3){
    $tc-=$tc*0.15;
}
$pdf->Cell(110);
$pdf->Cell(20,10,'After discount:', 0);
$pdf->Cell(15);
$pdf->Cell(10,10,'Rs.'.$tc, 0);
$pdf->Ln();

// $pdf->SetFont('Arial','B',16);
// $pdf->SetXY(100,100);
$pdf->Cell(3);
$pdf->Cell(100,100,'Thank you for being our valued customer. We are so grateful and hope we met your expectations.');
// $pdf->Output();
// exit();
// $pdf->Output($filename, 'D');
$pdf->Output($filename, 'F');
// header('Location: sample.php?cid=$cid&fn=$filename');
// exit();
?>


<?php 
    // sleep(10);
    //Recipient 
    $to = $cstmrmail; 
    
    // Sender 
    $from = 'misaalraikar1@gmail.com'; 
    $fromName = 'InvenTrack'; 
    
    // Email subject 
    $subject = 'Invoice from Electronic Store';  
    
    // Attachment file 
    $file = $filename;
    
    // Email body content 
    $htmlContent = ' 
        <h3>Invoice from Electronic Store</h3> 
        <p>Thank you for believing in us for your products. Until next time!</p> 
    '; 
    
    // Header for sender info 
    $headers = "From: $fromName"." <".$from.">"; 
    
    // Boundary  
    $semi_rand = md5(time());  
    $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";  
    
    // Headers for attachment  
    $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; 
    
    // Multipart boundary  
    $message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" . 
    "Content-Transfer-Encoding: 7bit\n\n" . $htmlContent . "\n\n";  
    
    // Preparing attachment 
    if(!empty($file) > 0){ 
        if(is_file($file)){ 
            $message .= "--{$mime_boundary}\n"; 
            $fp =    @fopen($file,"rb"); 
            $data =  @fread($fp,filesize($file)); 
    
            @fclose($fp); 
            $data = chunk_split(base64_encode($data)); 
            $message .= "Content-Type: application/octet-stream; name=\"".basename($file)."\"\n" .  
            "Content-Description: ".basename($file)."\n" . 
            "Content-Disposition: attachment;\n" . " filename=\"".basename($file)."\"; size=".filesize($file).";\n" .  
            "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n"; 
        } 
    } 
    $message .= "--{$mime_boundary}--"; 
    $returnpath = "-f" . $from; 
    
    // Send email 
    $mail = @mail($to, $subject, $message, $headers, $returnpath);  
    
    // Email sending status 
    echo $mail?"<h1>Email Sent Successfully!</h1>":"<h1>Email sending failed.</h1>";

    $final="UPDATE `sale` set `status`='sale' where `status`='cart'";
    $finalq=mysqli_query($conn,$final);
?>