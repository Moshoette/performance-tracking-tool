<?php 
include('authentication.php');
include('dbcon.php');

$page_title = "Dashboard";
include('includes/header.php');
include('includes/navbar.php');
?>

<style>
    .chart-container {
        position: relative;
        margin: 20px auto; /* Adjust the top and bottom margin */
        height: 40vh; /* Responsive height */
        width: 80vw;   /* Responsive width */
    }

    .card {
        margin-bottom: 30px; /* Space between cards */
    }

    h3 {
        text-align: center; /* Center headers */
        color: #333; /* Dark color for headers */
    }

    /* Additional styling for charts */
    .chartjs-render-monitor {
        border-radius: 8px; /* Rounded corners */
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Soft shadow */
    }
</style>

<div class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php
                    if(isset($_SESSION['status'])) {
                ?>
                    <div class="alert alert-success">
                        <h5><?= $_SESSION['status']; ?></h5>
                    </div>
                <?php
                    }                 
                ?>

                <div class="card">
                    <div class="card-header">
                        <h4>SMME Performance Dashboard</h4>
                    </div>
                    <div class="card-body">
                        <main>
                            <section class="stats">
                                <h2>Username: <?= $_SESSION['auth_user']['username'];?></h2>
                                <h2>Email ID: <?= $_SESSION['auth_user']['email'];?></h2>
                                <h2>Phone No: <?= $_SESSION['auth_user']['phone'];?></h2>
                                
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Input Performance Data</h4>
                                    </div>
                                    <div class="card-body">
                                        <form method="POST" action="save_data.php">
                                            <div class="form-group">
                                                <label for="sales">Sales (R):</label>
                                                <input type="number" class="form-control" id="sales" name="sales" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="profit">Profit (R):</label>
                                                <input type="number" class="form-control" id="profit" name="profit" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="new_clients">New Clients:</label>
                                                <input type="number" class="form-control" id="new_clients" name="new_clients" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Save Data</button>
                                            <a href="download.php" class="btn btn-secondary">Download Data</a>
                                        </form>
                                    </div>
                                </div>
                            </section>
                        </main>

                        <?php
                        // Fetch the last 6 entries from the database
                        $sql = "SELECT * FROM performance_data ORDER BY created_at DESC LIMIT 6"; 
                        $result = $con->query($sql);

                        $salesData = [];
                        $profitData = [];
                        $newClientsData = [];

                        while ($row = $result->fetch_assoc()) {
                            $salesData[] = $row['sales'];
                            $profitData[] = $row['profit'];
                            $newClientsData[] = $row['new_clients'];
                        }
                        ?>

                        <!-- Include Chart.js -->
                        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                        <!-- Add canvas elements for the charts -->
                        <section class="stats">
                            <div class="chart-container">
                                <h3>Sales Chart</h3>
                                <canvas id="salesChart"></canvas>
                            </div>
                            <div class="chart-container">
                                <h3>Profit Chart</h3>
                                <canvas id="profitChart"></canvas>
                            </div>
                            <div class="chart-container">
                                <h3>New Clients Chart</h3>
                                <canvas id="newClientsChart"></canvas>
                            </div>
                        </section>

                        <script>
                            document.addEventListener('DOMContentLoaded', () => {
                                // Data fetched from PHP
                                const salesData = <?= json_encode($salesData) ?>;
                                const profitData = <?= json_encode($profitData) ?>;
                                const newClientsData = <?= json_encode($newClientsData) ?>;

                                // Create Sales Chart
                                const salesCtx = document.getElementById('salesChart').getContext('2d');
                                const salesChart = new Chart(salesCtx, {
                                    type: 'bar',
                                    data: {
                                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'June'], // Adjust as necessary
                                        datasets: [{
                                            label: 'Monthly Sales',
                                            data: salesData,
                                            backgroundColor: 'rgba(75, 192, 192, 0.6)',
                                            borderColor: 'rgba(75, 192, 192, 1)',
                                            borderWidth: 1
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        layout: {
                                            padding: {
                                                top: 10,
                                                bottom: 10,
                                                left: 10,
                                                right: 10
                                            }
                                        },
                                        plugins: {
                                            legend: {
                                                position: 'top',
                                            },
                                            tooltip: {
                                                backgroundColor: '#fff',
                                                titleColor: '#333',
                                                bodyColor: '#666',
                                                borderColor: '#ccc',
                                                borderWidth: 1
                                            }
                                        },
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                grid: {
                                                    color: 'rgba(0, 0, 0, 0.1)',
                                                }
                                            },
                                            x: {
                                                grid: {
                                                    display: false
                                                }
                                            }
                                        }
                                    }
                                });

                                // Create Profit Chart
                                const profitCtx = document.getElementById('profitChart').getContext('2d');
                                const profitChart = new Chart(profitCtx, {
                                    type: 'line',
                                    data: {
                                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'June'], // Adjust as necessary
                                        datasets: [{
                                            label: 'Monthly Profit',
                                            data: profitData,
                                            backgroundColor: 'rgba(153, 102, 255, 0.6)',
                                            borderColor: 'rgba(153, 102, 255, 1)',
                                            borderWidth: 2,
                                            fill: true
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        layout: {
                                            padding: {
                                                top: 10,
                                                bottom: 10,
                                                left: 10,
                                                right: 10
                                            }
                                        },
                                        plugins: {
                                            legend: {
                                                position: 'top',
                                            },
                                            tooltip: {
                                                backgroundColor: '#fff',
                                                titleColor: '#333',
                                                bodyColor: '#666',
                                                borderColor: '#ccc',
                                                borderWidth: 1
                                            }
                                        },
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                grid: {
                                                    color: 'rgba(0, 0, 0, 0.1)',
                                                }
                                            },
                                            x: {
                                                grid: {
                                                    display: false
                                                }
                                            }
                                        }
                                    }
                                });

                                // Create New Clients Chart
                                const newClientsCtx = document.getElementById('newClientsChart').getContext('2d');
                                const newClientsChart = new Chart(newClientsCtx, {
                                    type: 'pie',
                                    data: {
                                        labels: ['New Clients', 'Returning Clients'], // Adjust labels as necessary
                                        datasets: [{
                                            data: [...newClientsData], // Assuming 50 for Returning Clients as an example
                                            backgroundColor: ['rgba(255, 99, 132, 0.6)', 'rgba(54, 162, 235, 0.6)'],
                                            borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)'],
                                            borderWidth: 1
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        layout: {
                                            padding: {
                                                top: 10,
                                                bottom: 10,
                                                left: 10,
                                                right: 10
                                            }
                                        },
                                        plugins: {
                                            legend: {
                                                position: 'top',
                                            },
                                            title: {
                                                display: true,
                                                text: 'New Clients Overview'
                                            },
                                            tooltip: {
                                                backgroundColor: '#fff',
                                                titleColor: '#333',
                                                bodyColor: '#666',
                                                borderColor: '#ccc',
                                                borderWidth: 1
                                            }
                                        }
                                    }
                                });
                            });
                        </script>
                    </div>
                </div>       
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php');?>


