<!DOCTYPE html>
<html lang="en">

<head>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>BAPTISM CERTIFICATE</title>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    </head>

    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    @font-face {
        font-family: 'GuyfordBlackletter';
        src: url('../GuyfordBlackletter.ttf') format('truetype');
    }

    @font-face {
        font-family: 'palatino';
        src: url('../palatino.ttf') format('truetype');
    }

    body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f4f4;
        display: flex;
        justify-content: center;
        align-items: center;
        max-height: 270mm;
    }

    .card {
        margin: 0;
        width: 270mm;
        height: 205mm;
        text-align: left;
        text-align: left;
        background-image: url('https://res.cloudinary.com/dyacodwnx/image/upload/v1702255581/loop4x3kv7n0vrsta2zw.jpg');
        background-size: cover;
        background-position: center;
        position: relative;
    }

    .containers {
        padding-top: 25px;
        margin-left: 25%;
        height: 205mm;
    }

    .editable {

        font-weight: bold;
    }

    .PunongBarangay {
        margin-left: 550px;
    }

    .lagda {
        margin-left: 550px;
    }

    .blg {

        margin-left: 480px;
    }

    .center {
        text-align: center;
    }

    #toggleFormButton {
        position: fixed;
        bottom: 200px;
        right: 20px;
        display: inline-block;
        padding: 10px 20px;
        background-color: #9ACD32;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;

    }

    #generatePdf {
        position: fixed;
        bottom: 80px;
        right: 20px;
        display: inline-block;
        padding: 10px 20px;
        background-color: #9ACD32;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }



    #saveToDatabase {
        position: fixed;
        bottom: 140px;
        right: 20px;
        display: inline-block;
        padding: 10px 20px;
        background-color: #9ACD32;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    #cancel {
        position: fixed;
        bottom: 20px;
        right: 20px;
        display: inline-block;
        padding: 10px 20px;
        background-color: #dd0d0d;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }


    #generatePdf:hover {
        background-color: #45a049;
        padding: 12px 22px;
    }

    #cancel:hover {
        background-color: #7a0202;
        padding: 12px 22px;
    }

    #saveToDatabase:hover {
        background-color: #45a049;
        padding: 12px 22px;
    }

    #toggleFormButton:hover {
        background-color: #45a049;
        padding: 12px 22px;

    }

    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
    }

    input[type="email"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 3px;
    }

    button[type="submit"] {
        padding: 10px 20px;
        background-color: #9ACD32;
        color: #ffffff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        margin-left: 130px;
    }

    button[type="submit"]:hover {
        background-color: #2779bd;
    }


    .content {
        font-size: 25px;
        display: grid;
        justify-items: center;
        font-weight: bold;
    }

    .center .text-xs {
        font-size: 20px;
        padding-left: 200px;
        font-weight: bold;
        font-family: 'URW Chancery L', cursive;
    }

    u {
        font-size: 30px;
    }
    .text-content {
    position: relative;
    z-index: 1;
}

.image-signature {
    position: absolute;
    top: 160px; /* Adjust this value to position the signature vertically */
    left: 100px; /* Adjust this value to position the signature horizontally */
    z-index: 0;
}

    </style>
</head>

<body>
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "icp_database";
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $idToFetch = isset($_GET['id']) ? $_GET['id'] : null;
    if ($idToFetch !== null) {
        $sql = "SELECT * FROM binyag_request_certificate WHERE id = $idToFetch";
        $result = $conn->query($sql);


        if ($result !== false && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $dateString = $row['birthdate'];
            $dateComponents = date_parse($dateString);
            $year = $dateComponents['year'];
            $month = $dateComponents['month'];
            $monthInWord = date('F', mktime(0, 0, 0, $month, 1));
            $baptizedString = $row['baptismal_date'];
            $baptizedComponents = date_parse($baptizedString);
            $baptizedyear = $baptizedComponents['year'];
            $baptizedmonth = $baptizedComponents['month'];            
            $baptizedmonthInWord = date('F', mktime(0, 0, 0, $baptizedmonth, 1));
            function addSuffixToDay($day) {
                if ($day >= 11 && $day <= 13) {
                    return $day . 'th';
                }

                switch ($day % 10) {
                    case 1:
                        return $day . 'st';
                    case 2:
                        return $day . 'nd';
                    case 3:
                        return $day . 'rd';
                    default:
                        return $day . 'th';
                }
            }
            $baptizedday = addSuffixToDay($baptizedComponents['day']);
            $day = addSuffixToDay($dateComponents['day']);
            $id = $row['id'];

            // CERTIFICATE 
            echo ' <div id="certificateContent">';
            echo '<div class="card h-3/4">';
            echo '<div class="containers">';
            echo '<div class="text-content">';
            echo '<p class=" center text-xs" style="font-family: palatino; font-size: 20px;">DIOCESE OF MALOLOS</p>';
            echo '<h1 class="center text-xs" style="font-family: GuyfordBlackletter, Old English Text MT, Cloister Black; font-size: 30px;">Immaculate Conception Parish</h1>';

            echo '<p class=" center text-xs" style="font-family: palatino; font-size: 16px;">J.P. RIZAL., ST. POBLACION, PANDI, BULACAN</p>';

            echo '<br><h2 class="center text-xs" style="font-family: GuyfordBlackletter, Old English Text MT, Cloister Black; font-size: 50px; color:#7d534c; ">Certificate of <span id="Certificate" class="editable" contenteditable="true"> Baptism </span></h2>';
            echo '<h3 class="center " style="font-family:GuyfordBlackletter, Old English Text MT, Cloister Black; font-size: 22px;">This is to Certify</h3>';

            echo '<div class="content">';
            echo '<p style="font-family:Brush Script MT, Brush Script Std, cursive; font-size: 27px;">that <u>' . $row['child_first_name'] . ' ' . $row['mother_maiden_lastname'] . ' ' . $row['father_lastname'] . '</u> child of <u>' . $row['father_fullname'] . '</u> and <u>' . $row['mother_maidenname'] . ' </u></p>';
            echo '<p class="ml-12" style="font-family:Brush Script MT, Brush Script Std, cursive; font-size: 27px;">born in <u>' . $row['birthplace'] . '</u> on the <u>' .  $day. '</u> day of  <u>' . $monthInWord . ', ' . $year . '</u> </p>';
           
            echo '<br><h3 class="center " style="font-family:GuyfordBlackletter, Old English Text MT, Cloister Black; font-size: 22px;">Was Solemnly Baptized</h3>';
            
            echo '<p class="ml-12 center" style="font-family:Brush Script MT, Brush Script Std, cursive; font-size: 27px;">on the <u>' . $baptizedday . '</u> day of <u>' . $baptizedmonthInWord . '</u>, <u>' . $baptizedyear . '</u></p>';            
            echo '<br>';
            echo '<h3 class="center " style="font-family:GuyfordBlackletter, Old English Text MT, Cloister Black; font-size: 22px;">According to the Rites of the Roman Catholic Church </h3>';
            echo '<p class="ml-12 " style="font-family:Brush Script MT, Brush Script Std, cursive; font-size: 27px;">By the Rev.<u>' . $row['baptized_by'] . '</u>, the Sponsors being <u>' .  $row['godfather'] . '</u> and <u>' .  $row['godmother'] . '</u></span>,';

            echo '<h3 class="center " style="font-family:GuyfordBlackletter, Old English Text MT, Cloister Black; font-size: 22px;">as it appears in the Confirmation Registry of this Church</h3>';

            echo '<br><p style="font-family:palatino; font-size: 17px;">BOOK NO. ' . $row['book_no'] . ' PAGE NO. ' . $row['page_no'] . ' LINE NO. ' . $row['line_no'] . ' </p>';
            echo '<p class="ml-12" style="font-family:palatino; font-size: 17px;">ISSUED: ' . $row['issued'] . ' FOR ' . $row['fors'] . ' </p>';
            
            echo '<img id= "signature" style="
            height: 70px;
        " src="https://res.cloudinary.com/dyacodwnx/image/upload/v1715650132/vbk2jobbepjtmwir6mm9.png" alt="Signature">';
            echo '<hr style=" width: 50%;">';
            echo '<p style="font-size: 18px;">REV. FR. JOSELIN SAN JOSE</p>';
        echo '<i style="font-size: 18px;">Parish Priest</i>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        
        } else {
            echo "No records found for the specified ID";
        }
    } else {
        echo "ID not provided";
    }
    $conn->close();
    ?>
    </form>

    <button id="generatePdf" title="Generate PDF">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
            <path fill="black" d="M24 24v4H8v-4H6v4a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-4Z" />
            <path fill="black"
                d="m21 21l-1.414-1.414L17 22.172V14h-2v8.172l-2.586-2.586L11 21l5 5l5-5zm7-17V2h-6v10h2V8h3V6h-3V4h4zm-11 8h-4V2h4a3.003 3.003 0 0 1 3 3v4a3.003 3.003 0 0 1-3 3zm-2-2h2a1.001 1.001 0 0 0 1-1V5a1.001 1.001 0 0 0-1-1h-2zM9 2H4v10h2V9h3a2.003 2.003 0 0 0 2-2V4a2.002 2.002 0 0 0-2-2zM6 7V4h3l.001 3z" />
        </svg>
    </button>
    <a href="../certificate_baptismal.php" id="cancel" title="Back" class="ml-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 48 48">
            <path fill="none" stroke="black" stroke-linejoin="round" stroke-width="4"
                d="M44 40.836c-4.893-5.973-9.278-9.362-13.036-10.168c-3.797-.805-7.412-.927-10.846-.365V41L4 27.545L20.118 7v10.167c6.349.05 11.746 2.328 16.192 6.833c4.445 4.505 7.009 10.117 7.69 16.836Z"
                clip-rule="evenodd" />
        </svg>
    </a>

    <?php require '_generator.php'; ?>
</body>

</html>

<script>
const toggleFormButton = document.getElementById('toggleFormButton');
toggleFormButton.addEventListener('click', () => {
    if (formContainer.style.display === 'none') {
        formContainer.style.display = 'block';
    } else {
        formContainer.style.display = 'none';
    }
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const generatePdfButton = document.getElementById('generatePdf');

    if (generatePdfButton) {
        generatePdfButton.addEventListener('click', function() {
            const content = document.getElementById('certificateContent');
            const childFirstName = "<?php echo $row['child_first_name']; ?>";
            const mothermaidenlastname = "<?php echo $row['mother_maiden_lastname']; ?>";
            const fatherlastname = "<?php echo $row['father_lastname']; ?>";
            const pdfOptions = {
                margin: 0,
                filename: `${childFirstName}_${mothermaidenlastname}_${fatherlastname}_baptism_certificate.pdf`,
                image: { type: 'jpeg', quality: 1 },
                html2canvas: { scale: 1, useCORS: true },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' }
            };

            // Generate PDF
            html2pdf().from(content).set(pdfOptions).save();
        });
    } else {
        console.error('Generate PDF button not found.');
    }
});

</script>
