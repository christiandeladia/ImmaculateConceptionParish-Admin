document.getElementById('openModalBtn').addEventListener('click', function() {
    // Fetch the content of file2.html
    fetch('file2.html')
      .then(response => response.text())
      .then(html => {
        // Inject the content into the modalContainer
        document.getElementById('modalContainer').innerHTML = html;
  
        // Trigger the modal to show
        var myModal = new bootstrap.Modal(document.getElementById('myModal'));
        myModal.show();
      })
      .catch(error => console.error('Error fetching file2.html:', error));
  });
  