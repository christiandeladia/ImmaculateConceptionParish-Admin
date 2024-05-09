$(document).ready(function() {
    const $table = $('#mytable');
    const $tbody = $table.find('tbody');
    const $rows = $tbody.find('tr');
    const pageSize = 5; // Set the number of rows to display per page.
    let currentPage = 1;

    function showPage(page) {
      $rows.hide();
      $rows.slice((page - 1) * pageSize, page * pageSize).show();
    }

    function updatePagination() {
      const totalPages = Math.ceil($rows.length / pageSize);
      const $pagination = $('.pagination');

      $pagination.empty();

      if (totalPages > 1) {
        $pagination.append('<li class="page-item" id="prev"><a class="page-link" href="#">Previous</a></li>');
        for (let i = 1; i <= totalPages; i++) {
          $pagination.append(`<li class="page-item${i === currentPage ? ' active' : ''}"><a class="page-link" href="#">${i}</a></li>`);
        }
        $pagination.append('<li class="page-item" id="next"><a class="page-link" href="#">Next</a></li>');

        $pagination.find('#prev').on('click', function() {
          if (currentPage > 1) {
            currentPage--;
            showPage(currentPage);
            updatePagination();
          }
        });

        $pagination.find('#next').on('click', function() {
          if (currentPage < totalPages) {
            currentPage++;
            showPage(currentPage);
            updatePagination();
          }
        });

        $pagination.find('li.page-item:not(#prev, #next) a').on('click', function() {
          currentPage = parseInt($(this).text());
          showPage(currentPage);
          updatePagination();
        });
      }
    }

    showPage(currentPage);
    updatePagination();
  });
