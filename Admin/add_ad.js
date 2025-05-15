document.addEventListener('DOMContentLoaded', () => {
  // Elements
  const formAddAd = document.getElementById('form-add-ad-admanager');
  const adsTableBody = document.getElementById('ads-table-body-admanager');
  const completedViewsEl = document.getElementById('completed-views-admanager');
  const totalRewardsEl = document.getElementById('total-rewards-admanager');
  const revenueEarnedEl = document.getElementById('revenue-earned-admanager');

  // Initial metrics state (you may want to fetch real data from backend later)
  let completedViews = 0;
  let totalRewards = 0;
  let revenueEarned = 0;

  // Utility: Format currency
  function formatCurrency(num) {
    return `$${num.toFixed(2)}`;
  }

  // Fetch and display all CPCV ads
  async function fetchAds() {
    try {
      const response = await fetch('add_ad.php', { method: 'GET' });
      if (!response.ok) throw new Error('Failed to fetch ads');
      const ads = await response.json();

      // Clear current table rows
      adsTableBody.innerHTML = '';

      ads.forEach(ad => {
        // For demo, views and earnings are dummy numbers (replace with real from DB)
        const views = ad.views || 0; 
        const earnings = ad.reward_points * views; 

        // Create table row
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td>${ad.name}</td>
          <td>${ad.status}</td>
          <td>${ad.reward_points}</td>
          <td>${views}</td>
          <td>${formatCurrency(earnings)}</td>
          <td>
            <button class="btn-edit" data-id="${ad.id}">Edit</button>
            <button class="btn-delete" data-id="${ad.id}">Delete</button>
          </td>
        `;
        adsTableBody.appendChild(tr);
      });

      // Update metrics - sum all views and earnings
      completedViews = ads.reduce((sum, ad) => sum + (ad.views || 0), 0);
      totalRewards = ads.reduce((sum, ad) => sum + ((ad.reward_points || 0) * (ad.views || 0)), 0);
      revenueEarned = totalRewards * 0.01; // assume $0.01 per reward point for demo

      updateMetrics();

    } catch (err) {
      console.error('Error fetching ads:', err);
    }
  }

  // Update the metrics display
  function updateMetrics() {
    completedViewsEl.textContent = completedViews;
    totalRewardsEl.textContent = totalRewards;
    revenueEarnedEl.textContent = formatCurrency(revenueEarned);
  }

  // Handle form submission to add new ad
  if (formAddAd) {
    formAddAd.addEventListener('submit', async e => {
      e.preventDefault();

      const payload = {
        name: document.getElementById('ad-name-admanager').value.trim(),
        google_ad_code: document.getElementById('google-adcode-admanager').value.trim(),
        placement: document.getElementById('placement-admanager').value.trim(),
        reward_points: parseInt(document.getElementById('reward-points-admanager').value, 10),
        status: document.getElementById('status-admanager').value.trim(),
        start_date: document.getElementById('start-date-admanager').value,
        end_date: document.getElementById('end-date-admanager').value,
        max_views_user: parseInt(document.getElementById('max-views-user-admanager').value, 10)
      };

      try {
        const response = await fetch('add_ad.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(payload)
        });

        if (!response.ok) throw new Error(`Server error: ${response.status}`);

        const result = await response.json();

        if (result.success) {
          alert('Ad successfully added!');
          formAddAd.reset();
          fetchAds(); // Refresh ads list
        } else {
          alert('Failed to add ad: ' + (result.error || 'Unknown error'));
        }
      } catch (error) {
        console.error('Error submitting form:', error);
        alert('Error occurred. Check console.');
      }
    });
  }

  // Initial fetch on page load
  fetchAds();

  // TODO: Add event delegation for Edit/Delete buttons on adsTableBody (optional)

  // You can expand this script to handle Reward Settings form, Reports export, Video player integration etc.
});
