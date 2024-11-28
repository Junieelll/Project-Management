// const urlParams = new URLSearchParams(window.location.search);
// const projectId = urlParams.get("project_id");

google.charts.load('current', {'packages':['corechart']});

// Set a callback to run when the Google Visualization API is loaded.
google.charts.setOnLoadCallback(drawChart);

// Function to fetch tasks from the PHP file and draw the chart
function drawChart() {
    // Fetch the tasks data from fetch_task.php
    fetch(`../../controller/fetch_task.php?project_id=${projectId}`) // Replace with the actual project_id you want to pass
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Process the data to get the count of "Finished" tasks
            let finishedCount = 0;
            let totalCount = data.tasks.length;

            // Count how many tasks are "Finished"
            data.tasks.forEach(task => {
                if (task.status === 'Finished') {
                    finishedCount++;
                }
            });

            // Calculate the percentage of "Finished" tasks
            let finishedPercentage = (finishedCount / totalCount) * 100;

            // Create the data table for Google Charts
            var taskData = google.visualization.arrayToDataTable([
                ['Task', 'Percentage'],
                ['Progress', finishedPercentage],
                ['', 100 - finishedPercentage] // Empty slice to create the cut effect
            ]);
            document.get
            // Set chart options
            var options = {
                pieSliceText: 'none',
                pieHole: 0.6, // Create the hole in the center
                is3D: false,
                legend: 'none',
                backgroundColor: 'transparent',
                slices: {
                    0: {color: 'blue'},
                    1: {color: 'transparent'}
                },
            };

            // Instantiate and draw the chart
            var chart = new google.visualization.PieChart(document.getElementById('piechart'));
            chart.draw(taskData, options);
        } else {
            console.error('Failed to fetch tasks:', data.message);
        }
    })
    .catch(error => console.error('Error fetching data:', error));
}

