
<?php
session_start();
include 'db.php';
?>
if (isset($_POST['download_pdf']))
{
    require '../fpdf/fpdf.php';

    $train_no = $_SESSION['train_no'];
    $journey_date = $_SESSION['journey_date'];

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 12);

    $pdf->Cell(0, 10, "Passenger Report for Train $train_no on $journey_date", 0, 1, 'C');
    $pdf->Ln(9);

    $class_query = "SELECT class, COUNT(class) as class_count FROM compartment WHERE train_no='$train_no' GROUP BY class;";
    $class_result = mysqli_query($con, $class_query);

    if ($class_result->num_rows > 0)
    {
        while ($class_row = $class_result->fetch_assoc()) 
        {
            $class_name = $class_row['class'];

            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 10, "Class: $class_name", 0, 1, 'C');
            $pdf->Ln(5); 

            $compartment_query = "SELECT compartment, COUNT(compartment) as comp_count FROM compartment WHERE train_no='$train_no' AND class='$class_name' GROUP BY compartment;";
            $compartment_result = mysqli_query($con, $compartment_query);

            if ($compartment_result->num_rows > 0) 
            {
                while ($compartment_row = $compartment_result->fetch_assoc()) 
                {
                    $compartment_name = $compartment_row['compartment'];

                    $pdf->SetFont('Arial', 'B', 10);
                    $pdf->Cell(0, 10, "Compartment: $compartment_name", 0, 1, 'C');

                    $passenger_query = "SELECT booking_id, name, gender, seat FROM booking WHERE train_no='$train_no' AND journey_date='$journey_date' AND class='$class_name' AND compartment='$compartment_name' AND booking_status='booked';";
                    $passenger_result = mysqli_query($con, $passenger_query);

                    if ($passenger_result->num_rows > 0) 
                    {
                        $pdf->SetFont('Arial', 'B', 9);
                        $pdf->SetXY(30, $pdf->GetY()); 
                        $pdf->Cell(40, 10, 'Passenger Name', 1, 0, 'C');
                        $pdf->Cell(20, 10, 'Gender', 1, 0, 'C');
                        $pdf->Cell(30, 10, 'PNR', 1, 0, 'C');
                        $pdf->Cell(30, 10, 'Seat Number', 1, 1, 'C');  

                        $pdf->SetFont('Arial', '', 9);
                        while ($passenger_row = $passenger_result->fetch_assoc()) {
                            $pdf->SetXY(30, $pdf->GetY()); 
                            $pdf->Cell(40, 10, $passenger_row['name'], 1, 0, 'C');
                            $pdf->Cell(20, 10, $passenger_row['gender'], 1, 0, 'C');
                            $pdf->Cell(30, 10, $passenger_row['booking_id'], 1, 0, 'C');
                            $pdf->Cell(30, 10, $passenger_row['seat'], 1, 1, 'C'); 
                        }        
                        
                    $pdf->Ln(5);             
                    $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
                    $pdf->Ln(5); 
                    } 
                    else 
                    {
                        $pdf->Cell(0, 10, "No passengers found for compartment: $compartment_name", 0, 1, 'C');

                    }

                    
                    $pdf->Ln(5);
                }
            }
        }
    } else {
        $pdf->Cell(0, 10, "No data found for this train number and journey date.", 0, 1, 'C');
    }

    
    mysqli_close($con);

    
    $pdf->Output('D', 'passenger_report.pdf');
}
?>
