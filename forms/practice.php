<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Generator</title>
    <script src="html2pdf.bundle.js"></script>
    <style>
        body {
            background-image: url('your-background-image.jpg');
            background-size: cover;
            font-family: Arial, sans-serif;
        }
        .card {
            margin: 50px;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8);
        }
    </style>
</head>
<body>

<div class="card" id="content">
    <h1>Your Text Goes Here</h1>
    <p>This is a sample text that will be converted to PDF with a background image.</p>
</div>

<button id="generatePdf">Generate PDF</button>

<script>
    document.getElementById('generatePdf').addEventListener('click', () => {
        const content = document.querySelector('.card');
        const Nameofchildvalue = document.getElementById('Nameofchild').textContent;  // Assuming you have an element with id 'Nameofchild'

        const pdfOptions = {
            margin: 0,
            filename: `${Nameofchildvalue}-report.pdf`,
            image: { type: 'jpeg', quality: 1 },
            html2canvas: { scale: 1 },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };

        html2pdf().from(content).set(pdfOptions).save();
    });
</script>

</body>
</html>
