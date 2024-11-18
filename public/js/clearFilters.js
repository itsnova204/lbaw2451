document.getElementById('clear-filters').addEventListener('click', function(event) {
    event.preventDefault();
    document.getElementById('category').value = '';
    document.getElementById('brand').value = '';
    document.getElementById('auctiontype').value = '';
    document.getElementById('entry-price-range').value = 0;
    document.getElementById('current-bid-range').value = 0;
});