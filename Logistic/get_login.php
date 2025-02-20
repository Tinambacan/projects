<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<table id="user_list">
 <tr>
    <th>Username</th>
    <!-- <th>Password</th> -->
    <th>Rider Name</th>
    <th>Vehicle</th>
 </tr>
 <tr id="getElement">
    
 </tr>
</table>    
</body>
<script>
    const table = document.querySelector( "#user_list");
    fetch("./login_api.php" )
    .then((response) => response.json())
    .then((userList) => {
    for (const user of userList) {
        const row = document.querySelector( "#getElement");
        row.innerHTML = `
            <td>${user.username }</td>
            <td>${user.rider_name}</td>
            <td>${user.vehicle}</td>
            `;
            table.append(row);
        }
    });
</script>
</html>