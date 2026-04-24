import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Alpine.start() moved to end of file
// Location autocomplete logic
const debounce = (fn, delay) => {
  let timeoutId;
  return (...args) => {
    clearTimeout(timeoutId);
    timeoutId = setTimeout(() => fn.apply(this, args), delay);
  };
};

const fetchLocationSuggestions = async (query) => {
  if (!query) return [];
  const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&addressdetails=1&limit=5`;
  try {
    const response = await fetch(url, { headers: { 'Accept-Language': 'id' } });
    const data = await response.json();
    return data;
  } catch (e) {
    console.error('Location fetch error', e);
    return [];
  }
};

const renderSuggestions = (suggestions) => {
  const ul = document.getElementById('location-suggestions');
  ul.innerHTML = '';
  if (suggestions.length === 0) {
    ul.classList.add('hidden');
    return;
  }
  suggestions.forEach(item => {
    const li = document.createElement('li');
    li.textContent = item.display_name;
    li.className = 'px-3 py-2 cursor-pointer hover:bg-gray-100';
    li.addEventListener('click', () => {
      document.getElementById('location-search').value = item.display_name;
      ul.classList.add('hidden');
    });
    ul.appendChild(li);
  });
  ul.classList.remove('hidden');
};

document.addEventListener('DOMContentLoaded', () => {
  const input = document.getElementById('location-search');
  if (!input) return;
  const debouncedFetch = debounce(async (e) => {
    const query = e.target.value.trim();
    const suggestions = await fetchLocationSuggestions(query);
    renderSuggestions(suggestions);
  }, 300);
  input.addEventListener('input', debouncedFetch);
  // Hide suggestions when clicking outside
  document.addEventListener('click', (e) => {
    if (!e.target.closest('#location-search') && !e.target.closest('#location-suggestions')) {
      document.getElementById('location-suggestions').classList.add('hidden');
    }
  });
});

Alpine.start();

// ---------- Pickup Location Autocomplete ----------
const debouncePickup = (fn, delay) => {
  let timeoutId;
  return (...args) => {
    clearTimeout(timeoutId);
    timeoutId = setTimeout(() => fn.apply(this, args), delay);
  };
};

const fetchPickupSuggestions = async (query) => {
  if (!query) return [];
  const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&addressdetails=1&limit=5`;
  try {
    const res = await fetch(url, { headers: { 'Accept-Language': 'id' } });
    return await res.json();
  } catch (e) {
    console.error('Pickup fetch error', e);
    return [];
  }
};

const renderPickupSuggestions = (suggestions) => {
  const ul = document.getElementById('suggestions-ambil');
  ul.innerHTML = '';
  if (!suggestions.length) { ul.classList.add('hidden'); return; }
  suggestions.forEach(item => {
    const li = document.createElement('li');
    li.textContent = item.display_name;
    li.className = 'px-3 py-2 cursor-pointer hover:bg-gray-100';
    li.addEventListener('click', () => {
      document.getElementById('search-input-ambil').value = item.display_name;
      document.getElementById('alamat_pengambilan_val').value = item.display_name;
      ul.classList.add('hidden');
    });
    ul.appendChild(li);
  });
  ul.classList.remove('hidden');
};

document.addEventListener('DOMContentLoaded', () => {
  const pickupInput = document.getElementById('search-input-ambil');
  if (pickupInput) {
    const debounced = debouncePickup(async (e) => {
      const suggestions = await fetchPickupSuggestions(e.target.value.trim());
      renderPickupSuggestions(suggestions);
    }, 300);
    pickupInput.addEventListener('input', debounced);
  }
});

// ---------- Return Location Autocomplete ----------
const debounceReturn = (fn, delay) => {
  let timeoutId;
  return (...args) => {
    clearTimeout(timeoutId);
    timeoutId = setTimeout(() => fn.apply(this, args), delay);
  };
};

const fetchReturnSuggestions = async (query) => {
  if (!query) return [];
  const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&addressdetails=1&limit=5`;
  try {
    const res = await fetch(url, { headers: { 'Accept-Language': 'id' } });
    return await res.json();
  } catch (e) {
    console.error('Return fetch error', e);
    return [];
  }
};

const renderReturnSuggestions = (suggestions) => {
  const ul = document.getElementById('suggestions-kembali');
  ul.innerHTML = '';
  if (!suggestions.length) { ul.classList.add('hidden'); return; }
  suggestions.forEach(item => {
    const li = document.createElement('li');
    li.textContent = item.display_name;
    li.className = 'px-3 py-2 cursor-pointer hover:bg-gray-100';
    li.addEventListener('click', () => {
      document.getElementById('search-input-kembali').value = item.display_name;
      document.getElementById('alamat_pengembalian_val').value = item.display_name;
      ul.classList.add('hidden');
    });
    ul.appendChild(li);
  });
  ul.classList.remove('hidden');
};

document.addEventListener('DOMContentLoaded', () => {
  const returnInput = document.getElementById('search-input-kembali');
  if (returnInput) {
    const debounced = debounceReturn(async (e) => {
      const suggestions = await fetchReturnSuggestions(e.target.value.trim());
      renderReturnSuggestions(suggestions);
    }, 300);
    returnInput.addEventListener('input', debounced);
  }
});

// Hide suggestion boxes when clicking outside
document.addEventListener('click', (e) => {
  if (!e.target.closest('#search-input-ambil') && !e.target.closest('#suggestions-ambil')) {
    document.getElementById('suggestions-ambil')?.classList.add('hidden');
  }
  if (!e.target.closest('#search-input-kembali') && !e.target.closest('#suggestions-kembali')) {
    document.getElementById('suggestions-kembali')?.classList.add('hidden');
  }
});
