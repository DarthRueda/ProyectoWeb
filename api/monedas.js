const apiKey = 'fca_live_y1OJ8shzvaB7Bm0r8w6DalLIodXOyZFLBN6sHA6f';
let currencyRates = {};
let selectedCurrency = 'EUR';
const currencySymbols = {
    'EUR': '€',
    'USD': '$',
    'GBP': '£',
    'JPY': '¥',
    'AUD': 'A$',
    'CAD': 'C$',
    'CHF': 'CHF',
    'CNY': '¥',
    'SEK': 'kr',
    'NZD': 'NZ$',
};

// Fetch para obtener la informacion de las monedas
function fetchCurrencyRates() {
    fetch(`https://api.freecurrencyapi.com/v1/latest?apikey=${apiKey}`)
        .then(response => response.json())
        .then(data => {
            currencyRates = data.data;
            populateCurrencySelector();
        })
        .catch(error => console.error('Error fetching currency rates:', error));
}

// Funcion para llenar el selector de monedas
function populateCurrencySelector() {
    const currencySelector = document.getElementById('currencySelector');
    for (const currency in currencyRates) {
        const option = document.createElement('option');
        option.value = currency;
        option.text = currency;
        currencySelector.appendChild(option);
    }
}

// Funcion para convertir el precio a la moneda seleccionada
function convertPrice(price, currency) {
    if (currency === 'EUR') return `${price} €`;
    const rate = currencyRates[currency];
    const symbol = currencySymbols[currency] || currency;
    return `${(price * rate).toFixed(2)} ${symbol}`;
}

document.getElementById('currencySelector').addEventListener('change', function() {
    selectedCurrency = this.value;
    updatePrices();
});

// Funcion para actualizar los precios
function updatePrices() {
    // Actualizar precios en la tabla de pedidos
    document.querySelectorAll('#pedidosTable tbody tr').forEach(row => {
        const totalCell = row.querySelector('td:nth-child(4)');
        const total = parseFloat(totalCell.dataset.originalPrice);
        totalCell.textContent = convertPrice(total, selectedCurrency);
    });

    // Actualizar precios en la tabla de productos
    document.querySelectorAll('#productosTable tbody tr').forEach(row => {
        const priceCell = row.querySelector('td:nth-child(4)');
        const price = parseFloat(priceCell.dataset.originalPrice);
        priceCell.textContent = convertPrice(price, selectedCurrency);
    });
}

fetchCurrencyRates();
