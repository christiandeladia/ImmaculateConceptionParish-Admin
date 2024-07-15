<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mass Schedule</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        #generatePdf {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
        }

        #cancel {
            position: fixed;
            bottom: 60px;
            right: 20px;
            z-index: 9999;
        }

        th,
        td {
            padding: 8px;
            text-align: center;
            border: 1px solid black;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        .flatpickr-disabled {
            display: none !important;
        }
        .flatpickr-calendar.inline {
    display: grid;
    position: relative;
    top: 2px;
    margin-bottom: 20px;

}

    div#birthdayTable,div#weddingTable,div#wishesTable,div#sicknessTable, div#blessingTable,div#soulTable {
        padding-top:30px
}
    th#thanksgivingTable,
    th#birthdayTable,
    th#weddingTable{
        background-color: #b99a0d;
    }
    th#wishesTable, th#sicknessTable, th#blessingTable {
    background-color: #57d953;
}
    th#soulTable {
    background-color: #b985e9;
}   
    </style>
</head>

<body>
<button id="reloadPage" title="Reload Page">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
            <path fill="none" d="M0 0h24v24H0V0z"/>
            <path d="M14.83 4l-1.2 1.2C12.39 4.45 10.79 4.5 9.5 5.09c-2.3 1.05-4 3.24-4.63 5.79h2.07c.51-1.81 1.87-3.24 3.56-3.83L6.7 12H2l3.54 3.54c1.2 1.19 2.79 1.88 4.46 1.88 3.25 0 5.97-2.39 6.41-5.5h-2.07c-.4 1.49-1.58 2.72-3.25 3.38L17.17 12 22 7.17 14.83 4zm-1.66 13.96c-.51 1.8-1.87 3.23-3.56 3.83l1.94-1.94c-.59-.55-1.06-1.2-1.38-1.91h-2.48c.44 1.85 1.83 3.42 3.67 3.81l-1.69 1.69C10.95 22.19 8.23 24 5 24c-1.77 0-3.39-.86-4.54-2.19l1.9-1.9c1.37.9 2.99 1.43 4.74 1.43 2.3 0 4.41-.99 5.88-2.56l-1.86-1.86c-.65.7-1.49 1.24-2.46 1.51v-2.48c1.5-.46 2.75-1.67 3.35-3.34l1.71 1.71c-1.26 2.21-3.6 3.75-6.41 3.75-1.97 0-3.73-.87-4.96-2.24l-1.83 1.83C5.52 20.93 7.17 22 9.5 22c2.69 0 4.97-1.64 5.91-3.96h-1.94z"/>
        </svg>
    </button>
<input type="text" id="datePicker" placeholder="Select Date">
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "icp_database";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

echo '<div id="tablecontent" style="margin: 0 100px;">';
echo '<p style ="text-align: center; font-size:20px;"> Dayoses ng Malolos</p>';
echo '<p style ="text-align: center; font-size:20px; font-weight:bold;"> Parokya ng Immaculada Conception</p>';
echo '<p style ="text-align: center; font-size:20px;"> Pandi, Bulacan</p>';
echo "<p style='text-align: center;font-size: 20px;border: 1px solid; background-color: #b99a0d;'>Magandang Hapon po sa ating lahat. Ngayon po ay <span id='selectedDate'></span></p>";

$purposes = [
  'For Thanks Giving' => "(1)Samahan po natin<br> sa pasasalamat sa diyos ang (mga) kapatid nating si/sina:",
  'For Birthdays' => "(2)lalo na ang mga may kaarawan na si/sina",
  'For Wedding Anniversary' => "(3) at mga nag diriwang ng anibersaryo ng pag iisang dibdib na si/sina:",
  'For Blessings' => "(4)Gayundin naman, isama rin natin sa ating mga panalangin ang mga natatanging hinihiling na biyaya sa Diyos na si/sina:",
  'For Fast Recovery' => "(5)lalo na ang humihiling ng agarang kagalingan na si/sina:",
  'For Safe Trip' => "(6)at mga humihiling ng biyaya para sa ligtas na paglalakbay na si/sina:",
  'For the Soul' => "(7)alalahanin natin ang mga kapatid na yumao na si/sina:"
];

$i = 0;
foreach ($purposes as $purpose => $header) {
  $headerColor = '';
  switch ($i) {
    case 0:
    case 1:
    case 2:
      $headerColor = '#b99a0d'; 
      break;
    case 3:
    case 4:
    case 5:
      $headerColor = '#57d953'; 
      break;
    case 6:
      $headerColor = '#b985e9'; 
      break;
    default:
      $headerColor = ''; 
  }

  echo "<div id='" . strtolower(str_replace(' ', '', $purpose)) . "Table' style='padding-top: 20px;'>";
  echo "<table style='width: 100%;'>";
  echo "<tr style='background-color: $headerColor;'>"; 
  echo "<th colspan='2'>$header</th>";
  echo "<th style='display:none;'>Date</th>";
  echo "<th style='display:none;'>Date Added</th>";
  echo "</tr>";

  $sql = "SELECT name, date, date_added,time FROM mass WHERE status_id = 2 AND purpose = '$purpose' ORDER BY purpose ASC";
  $result = $conn->query($sql);

  if ($result !== false && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      echo "<tr>";
      echo "<td class='text-center align-middle'>" . $row['name'] . "</td>";
      echo "<td class='date-column' style='display:none;'>" . date('F j, Y', strtotime($row['date'])) . "</td>"; 
      echo "<td class='date-column' style='display:none;'>" . date('F j, Y', strtotime($row['date_added'])) . "</td>";
      echo "<td class='text-center align-middle time-column' style='width: 30%;'>" . date('h:i a', strtotime($row['time'])) . "</td>";
      echo "</tr>";
    }
  } else {
    // Handle no results case (optional)
  }

  echo "</table>";
  echo "</div>";
  
  $i++;
}

echo '<p style ="text-align: center; font-size:25px; font-weight:bold;"> Magsitayo po Tayo! </p>';
echo "</div>";

$conn->close();
?>

 <button id="generatePdf" title="Generate PDF">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
            <path fill="black" d="M24 24v4H8v-4H6v4a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-4Z" />
            <path fill="black"
                d="m21 21l-1.414-1.414L17 22.172V14h-2v8.172l-2.586-2.586L11 21l5 5l5-5zm7-17V2h-6v10h2V8h3V6h-3V4h4zm-11 8h-4V2h4a3.003 3.003 0 0 1 3 3v4a3.003 3.003 0 0 1-3 3zm-2-2h2a1.001 1.001 0 0 0 1-1V5a1.001 1.001 0 0 0-1-1h-2zM9 2H4v10h2V9h3a2.003 2.003 0 0 0 2-2V4a2.002 2.002 0 0 0-2-2zM6 7V4h3l.001 3z" />
        </svg>
    </button>
    <a href="../mass.php" id="cancel" title="Back" class="ml-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 48 48">
            <path fill="none" stroke="black" stroke-linejoin="round" stroke-width="4"
                d="M44 40.836c-4.893-5.973-9.278-9.362-13.036-10.168c-3.797-.805-7.412-.927-10.846-.365V41L4 27.545L20.118 7v10.167c6.349.05 11.746 2.328 16.192 6.833c4.445 4.505 7.009 10.117 7.69 16.836Z"
                clip-rule="evenodd" />
        </svg>
    </a>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            flatpickr("#datePicker", {
                dateFormat: "Y-m-d",
                inline: true,
                disable: <?php echo json_encode($disabledDates); ?>
            });
        });
    </script>

</body>

</html>
<script>
        document.addEventListener('DOMContentLoaded', function() {
            const datePicker = document.getElementById('datePicker');
            const tableRows = document.querySelectorAll('table tr:not(:first-child)');

            flatpickr(datePicker, {
                dateFormat: 'F d, Y',
                onChange: function(selectedDates, dateStr, instance) {
                    filterRowsByDate(dateStr);
                }
            });

            function filterRowsByDate(dateStr) {
                const selectedDate = new Date(dateStr);
                document.getElementById('selectedDate').textContent = selectedDate.toDateString();

                tableRows.forEach(row => {
                    const rowDateStr = row.cells[1].textContent.trim();
                    const rowDate = new Date(rowDateStr);
                    if (rowDate.getTime() === selectedDate.getTime()) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            document.getElementById('reloadPage').addEventListener('click', function() {
                location.reload();
            });

            document.getElementById('generatePdf').addEventListener('click', function() {
                const element = document.getElementById('tablecontent');
                const opt = {
                    margin: 1,
                    filename: 'mass_schedule.pdf',
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2 },
                    jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
                };
                html2pdf().set(opt).from(element).save();
            });

            document.getElementById('cancel').addEventListener('click', function() {
                window.location.href = 'certificate_baptismal.php';
            });
        });
    </script>