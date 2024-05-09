
$(document).ready(function() {
  const $table = $('#mytable');
  const $rows = $table.find('tbody tr');

  // Function to filter rows based on the status
  function filterRows(status) {
    $rows.show(); // Show all rows by default

    if (status !== 'all') {
      $rows.each(function() {
        const rowStatus = $(this).find('td:eq(2) span').text().toLowerCase();
        if (rowStatus !== status) {
          $(this).hide();
        }
      });
    }
  }

  // Add click event handlers to the filter buttons
  $('.status-filter').on('click', function() {
    const status = $(this).data('status');
    filterRows(status);
    $('.status-filter').removeClass('active');
    $(this).addClass('active');
  });

  // Initialize with "All" filter
  filterRows('all');
  $('.status-filter[data-status="all"]').addClass('active');
});

