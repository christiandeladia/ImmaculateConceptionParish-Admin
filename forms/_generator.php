
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> 
<script src='https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js'></script>
<script type="text/javascript" >
    function _initPDFScript(){
        const toggleFormButton = document.getElementById('toggleFormButton');

        if (toggleFormButton) {
            toggleFormButton.addEventListener('click', () => {
                if (formContainer && formContainer.style.display === 'none') {
                    formContainer.style.display = 'block';
                } else {
                    formContainer.style.display = 'none';
                }
            });
        }        
        document.getElementById('generatePdf').addEventListener('click', () => {
            const content = document.querySelector('.card');
            const Nameofchildvalue = document.getElementById('Nameofchild').textContent;

            const img = document.createElement('img');

            img.src = 'https://res.cloudinary.com/dyacodwnx/image/upload/v1702255581/loop4x3kv7n0vrsta2zw.jpg';

            img.alt = 'Certificate-Background';
            content.appendChild(img);

            const pdfOptions = {
                margin: 0,
                filename: `${Nameofchildvalue}-baptismal_certificate.pdf`,
                image: { type: 'jpeg', quality: 1 },
                html2canvas: { scale: 1, useCORS: true },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' }
            };
            html2pdf().from(content).set(pdfOptions).save();
        });
    }

    _initPDFScript();

    function generatePdf() {
        const pdfContent = `
            Nameofthechild: ${document.getElementById('Nameofthechild').textContent}
            Nameoffather: ${document.getElementById('Nameoffather').textContent}
            Nameofmother: ${document.getElementById('Nameofmother').textContent} 
            Residentof: ${document.getElementById('Residentof').textContent}
            baptismdate: ${document.getElementById('baptismdate').textContent}
            Rev: ${document.getElementById('Rev').textContent}
            sponsor: ${document.getElementById('sponsor').textContent}
            Booknum: ${document.getElementById('Booknum').textContent}
            Pagenum: ${document.getElementById('Pagenum').textContent}
            linenum: ${document.getElementById('linenum').textContent}
            issued: ${document.getElementById('issued').textContent}
            purpose: ${document.getElementById('purpose').textContent}
        `;

        return pdfContent;
    }

    function extractFormData() {
        return {
            Nameofthechild: document.getElementById('Nameofthechild').textContent,
            Nameoffather: document.getElementById('Nameoffather').textContent,
            Nameofmother: document.getElementById('Nameofmother').textContent,
            Residentof: document.getElementById('Residentof').textContent,
            baptismdate: document.getElementById('baptismdate').textContent,
            Rev: document.getElementById('Rev').textContent,
            sponsor: document.getElementById('sponsor').textContent,
            Booknum: document.getElementById('Booknum').textContent,
            Pagenum: document.getElementById('Pagenum').textContent,
            linenum: document.getElementById('linenum').textContent,
            issued: document.getElementById('issued').textContent,
            purpose: document.getElementById('purpose').textContent
        };
    }
</script>