function addEventListeners() {
    initializeCountdown();

  document.addEventListener('DOMContentLoaded', function () {
    const filters = {
      'filter-id': 0,
      'filter-username': 1,
      'filter-email': 2,
      'filter-date' :3,
      'filter-status': 4,
      'filter-bid': 5,
      'filter-category' : 6,
      'filter-creator' : 7,
    };

    Object.keys(filters).forEach(function (filterId) {
      const filterInput = document.getElementById(filterId);
      if (filterInput) {
        filterInput.addEventListener('keyup', function () {
          const filterValue = this.value.toLowerCase();
          const columnIndex = filters[filterId];
          const rows = document.querySelectorAll('#user-table tbody tr');

          rows.forEach(function (row) {
            const cell = row.cells[columnIndex];
            if (cell) {
              const cellText = cell.textContent.toLowerCase();
              row.style.display = cellText.includes(filterValue) ? '' : 'none';
            }
          });
        });
      }
    });
  });

  document.addEventListener('DOMContentLoaded', function () {
    const entryPriceRange = document.getElementById('entry-price-range');
    const entryPriceValue = document.getElementById('entry-price-value');
    const currentBidRange = document.getElementById('current-bid-range');
    const currentBidValue = document.getElementById('current-bid-value');

    entryPriceRange.addEventListener('input', function () {
      entryPriceValue.textContent = entryPriceRange.value;
    });

    currentBidRange.addEventListener('input', function () {
      currentBidValue.textContent = currentBidRange.value;
    });
  });
  }

  function initializeCountdown() {
    let countdownElement = document.getElementById('countdown');
    if (countdownElement) {
      let endDateStr = countdownElement.getAttribute('data-end-date');
      console.log('End Date String:', endDateStr); // Debugging line
      let endDate = new Date(endDateStr).getTime();

      if (isNaN(endDate)) {
        console.error('Invalid end date:', endDateStr);
        countdownElement.innerHTML = "Invalid end date";
        return;
      }

      let countdownInterval = setInterval(function () {
        let now = new Date().getTime();
        let distance = endDate - now;

        if (distance < 0) {
          clearInterval(countdownInterval);
          countdownElement.innerHTML = "Auction Ended";
          return;
        }

        let days = Math.floor(distance / (1000 * 60 * 60 * 24));
        let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        let seconds = Math.floor((distance % (1000 * 60)) / 1000);

        countdownElement.innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";
      }, 1000);
    }
  }


  
  function encodeForAjax(data) {
    if (data == null) return null;
    return Object.keys(data).map(function(k){
      return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&');
  }
  
  function sendAjaxRequest(method, url, data, handler) {
    let request = new XMLHttpRequest();
  
    request.open(method, url, true);
    request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.addEventListener('load', handler);
    request.send(encodeForAjax(data));
  }
  
  addEventListeners();

function displayNotification(message) {

  const notification = document.createElement('div');
  notification.classList.add('notification');

  notification.innerHTML = `
      <div class="message">${message}</div>
      <span class="close-btn">&times;</span>
  `;

  notificationsContainer.appendChild(notification);

  notification.querySelector('.close-btn').addEventListener('click', () => {
      notification.remove();
  });

  setTimeout(() => {
      notification.remove();
  }, 5000);
}
  