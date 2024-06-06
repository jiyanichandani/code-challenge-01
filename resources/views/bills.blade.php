<!DOCTYPE html>
<html>
<head>
    <title>Bills</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body>
<div class="container mt-5" id="billsData" style="display: none">
    <h1>Bills</h1>
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body" id="submitted_bills">
                    <h5 class="card-title">Total number submitted Bills</h5>
                    <p class="card-text" id="total-submitted">Loading...</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body" id="approved_bills">
                    <h5 class="card-title">Total number approved Bills</h5>
                    <p class="card-text" id="total-approved">Loading...</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body" id="hold_bills">
                    <h5 class="card-title">Total number onhold Bills</h5>
                    <p class="card-text" id="total-on-hold">Loading...</p>
                </div>
            </div>
        </div>
    </div>
    <h2 class="mt-5">Users</h2>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>User Name</th>
                <th>Total Bills</th>
                <th>Submitted Bills</th>
                <th>Approved Bills</th>
            </tr>
        </thead>
        <tbody id="user-stats">
            <tr>
                <td colspan="4">Loading...</td>
            </tr>
        </tbody>
    </table>
</div>
<div id="noData" class="error-container" style="display: none">
        <div class="error-icon">!</div>
        <div class="error-message">No data available.</div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        axios.get('/api/v1/Bills')
            .then(function (response) {

                // Check data avaliable or not in response
                if(response.data.user_stats.length == 0){
                    document.getElementById("noData").style.display = 'block';
                } else {
                    document.getElementById("billsData").style.display = 'block';
                    const data = response.data;

                    // Display Main count for Bills
                    document.getElementById('total-submitted').innerText = data['total_submitted'].count;
                    document.getElementById('total-approved').innerText = data['total_approved'].count;
                    document.getElementById('total-on-hold').innerText = data['total_on_hold'].count;

                    // Display Colour according bill's status
                    document.getElementById("submitted_bills").style.backgroundColor = data['total_submitted'].color_name;
                    document.getElementById("approved_bills").style.backgroundColor = data['total_approved'].color_name;
                    document.getElementById("hold_bills").style.backgroundColor = data['total_on_hold'].color_name;

                    // Update user and bills table
                    const userStats = document.getElementById('user-stats');
                    userStats.innerHTML = '';
                    data.user_stats.forEach(user => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${user.name}</td>
                            <td>${user.total_bills}</td>
                            <td>${user.submitted_bills}</td>
                            <td>${user.approved_bills}</td>
                        `;
                        userStats.appendChild(row);
                    });
                }
            })
            .catch(function (error) {
                console.error('Error fetching stats:', error);
                alert('Error fetching stats');
            });
    });
</script>
</body>
</html>
<style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background-color: #f5f5f5;
    }

    .error-container {
      text-align: center;
      padding: 20px;
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .error-icon {
      font-size: 60px;
      color: #f44336;
    }

    .error-message {
      font-size: 24px;
      color: #333;
      margin-top: 10px;
    }

    .refresh-button {
      margin-top: 20px;
      padding: 10px 20px;
      font-size: 18px;
      background-color: #4caf50;
      color: #fff;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .refresh-button:hover {
      background-color: #45a049;
    }
  </style>
