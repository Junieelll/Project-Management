google.charts.load('current', {'packages':['timeline']});
google.charts.setOnLoadCallback(drawChart);

const urlParams = new URLSearchParams(window.location.search);
const projectId = urlParams.get("project_id");

function parseDate(dateString) {
  // Remove the time part and parse the date correctly
  const datePart = dateString.split('|')[0].trim();
  // Return a Date object from the date part
  return new Date(datePart);
}

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
          // Convert dates using the helper function
          const startDate = parseDate(task.created_date);
          const endDate = parseDate(task.due_date);

          // Check if dates are valid before adding
          if (startDate && endDate) {
            taskData.addRow([task.task_title, startDate, endDate]);
          } else {
            console.warn(`Invalid date for task: ${task.task_title}`);
          }
        });

        // Set chart options with custom styling
        const options = {
          width: 1200,
          height: 1000,
          timeline: {
            colorByRowLabel: true, // Color rows based on the task label
            rowLabelStyle: {
              fontName: 'Arial',
              fontSize: 14,
              color: '#1E2646', // Task label text color
              bold: true
            },
            barLabelStyle: {
              fontName: 'Arial',
              fontSize: 12,
              color: '#1E2646', // Task bar text color
            },
            task: {
              backgroundColor: '#3D437D', // Task bar color (hex of root)
              borderRadius: 5 // Rounded corners for task bars
            },
            gridlineColor: '#BBBBBB', // Lighter gridlines
            gridlineWidth: 1,
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
