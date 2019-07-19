$(function () {
  var i = 0;
  var k = 1;

  while (i < 50) {
      k=i+1;
      while (k < 50) {
          var cur_cell = $('#time-content-' + i);
          var next_cell = $('#time-content-' + k);

          if (cur_cell.text() === next_cell.text()) {
              var cur_row = cur_cell.attr('rowspan');
              cur_row++;
              cur_cell.attr('rowspan', cur_row);
              next_cell.remove();
          }
          k++;
      }
      i++;
  }
});
