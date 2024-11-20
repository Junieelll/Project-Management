google.charts.load('current', {'packages':['timeline']});
google.charts.setOnLoadCallback(drawChart);

const urlParams = new URLSearchParams(window.location.search);
const projectId = urlParams.get("project_id");

function drawChart() {
  fetch(`../../controller/fetch_task.php?project_id=${projectId}`)  
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        const tasks = data.tasks;

        // Prepare the data for the Gantt chart
        const taskData = new google.visualization.DataTable();
        taskData.addColumn('string', 'Task');
        taskData.addColumn('date', 'Start');
        taskData.addColumn('date', 'End');
        
        tasks.forEach(task => {
          // Convert dates from the format 'Month Day, Year | Hour:Minute AM/PM' to JavaScript Date objects
          const startDate = new Date(task.created_date.split('|')[0].trim());
          const endDate = new Date(task.due_date.split('|')[0].trim());

          taskData.addRow([task.task_title, startDate, endDate]);
        });

        // Set chart options with custom styling
        const options = {
          height: 400,
          timeline: {
            colorByRowLabel: true, // Color rows based on the task label
            rowLabelStyle: {
              fontName: 'Arial',
              fontSize: 14,
              color: '#1E2646', // White text for task labels
              bold: true
            },
            barLabelStyle: {
              fontName: 'Arial',
              fontSize: 12,
              color: '#1E2646', // White text for task bars
            },
            // Customizing task bar color and appearance
            task: {
              backgroundColor: '#3D437D', // Task bar color (hex of root)
              borderRadius: 5 // Rounded corners for task bars
            },
            // Customizing gridline appearance
            gridlineColor: '#BBBBBB', // Lighter gridlines
            gridlineWidth: 1,
            // Header styling
            headerHeight: 40,
            headerStyle: {
              backgroundColor: '#3D437D', // Header color (hex of root)
              color: '#1E2646',
              fontSize: 16
            }
          }
        };

        // Draw the chart with the specified container and options
        const container = document.getElementById('timeline');
        const chart = new google.visualization.Timeline(container);
        chart.draw(taskData, options);
      } else {
        console.error('Failed to fetch tasks:', data.message);
      }
    })
    .catch(error => console.error('Error fetching tasks:', error));
}
