document.addEventListener("DOMContentLoaded", function () {
  fetch('get_wallet.php')
    .then(res => res.json())
    .then(data => {
      if (data.error) {
        console.error(data.error);
        return;
      }
      const pointsElem = document.querySelector('.points-wallet');
      const cashElem = document.querySelector('.cash-wallet');
      if (pointsElem) pointsElem.textContent = `${data.points} Points`;
      if (cashElem) cashElem.textContent = `${data.pkr} PKR`;
    })
    .catch(err => {
      console.error('Error fetching wallet data:', err);
    });
});
