function calculateRowProfits(liveSellPrice) {
  let totalRealtime = 0;

  var table = $('#myTable').DataTable();
  table.rows().every(function() {
    let row = $(this.node());
    const status = parseInt(row.attr('data-status'), 10); // 1=open,0=closed,2=pending
    console.log(status);
    let buyPrice   = parseFloat(row.find('.buy-price').data('buy'));
    let rtCell     = row.find('.rt-profit-cell');

    if (!isNaN(buyPrice)) {
      if (!isNaN(liveSellPrice)) {
        let rtProfit = liveSellPrice - buyPrice;
        totalRealtime += rtProfit;

        if (rtProfit > 0) {
          rtCell.html('<span style="color:blue;font-weight:bold;">+' + rtProfit.toFixed(2) + '</span>');
        } else if (rtProfit < 0) {
          rtCell.html('<span style="color:orange;font-weight:bold;">' + rtProfit.toFixed(2) + '</span>');
        } else {
          rtCell.text("0.00");
        }
      }
    }
  });

  $('#totalRtProfit').text(totalRealtime.toFixed(2));
}

function fetchGoldPrice() {
  var myHeaders = new Headers();
  myHeaders.append("x-access-token", "goldapi-5j959sme9liajq-io");
  myHeaders.append("Content-Type", "application/json");

  fetch("https://www.goldapi.io/api/XAU/USD", {
    method: 'GET',
    headers: myHeaders,
    redirect: 'follow'
  })
  .then(response => response.json())
  .then(data => {
    $('#CurrentTrade').html(data.price);
    $('#BuyTrade').html(data.ask);
    $('#SaleTrade').html(data.bid);
    $('#tradeamnt').val(data.ask);

    let sellPrice = parseFloat(data.bid); // realtime sell price
    calculateRowProfits(sellPrice);       // total from there
  })
  .catch(error => console.error('Error:', error));
}

fetchGoldPrice();
setInterval(fetchGoldPrice, 5000);
