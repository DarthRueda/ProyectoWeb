document.getElementById('verLogs').addEventListener('click', function() { //Funcion para mostrar los logs
    fetch('controllers/apiController.php?action=obtenerLogs')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.querySelector('#logsTable tbody'); //Se obtiene el tbody de la tabla
            tableBody.innerHTML = '';

            data.forEach(log => { //Se recorre el array de logs
                const row = document.createElement('tr');
                row.innerHTML = `<td>${log}</td>`;
                tableBody.appendChild(row);
            });

            document.getElementById('clearLogs').style.display = 'block';
            showTable('logsTable'); //Se muestra la tabla
        })
        .catch(error => console.error('Error:', error));
});

//Funcion para limpiar los logs
document.getElementById('clearLogs').addEventListener('click', function() { 
    fetch('controllers/apiController.php?action=clearLogs') //Se llama al controlador para limpiar los logs
        .then(response => response.json()) //Se convierte la respuesta a JSON
        .then(data => {
            alert(data.message);
            document.querySelector('#logsTable tbody').innerHTML = '';
        })
        .catch(error => console.error('Error:', error));
});
