google.charts.load('current', { packages: ['corechart', 'timeline'] });
google.charts.setOnLoadCallback(drawCharts);

const urlParams = new URLSearchParams(window.location.search);
const projectId = urlParams.get("project_id");

function parseDate(dateString) {
  const datePart = dateString.split('|')[0].trim();
  return new Date(datePart);
}

function drawCharts() {
  fetch(`../../controller/fetch_task.php?project_id=${projectId}`)
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        const tasks = data.tasks;

        // Draw Gantt Chart
        drawGanttChart(tasks);

        // Draw 3D Pie Chart
        drawPieChart(tasks);
      } else {
        console.error('Failed to fetch tasks:', data.message);
      }
    })
    .catch(error => console.error('Error fetching tasks:', error));
}

function drawGanttChart(tasks) {
  const taskData = new google.visualization.DataTable();
  taskData.addColumn('string', 'Task');
  taskData.addColumn('date', 'Start');
  taskData.addColumn('date', 'End');

  tasks.forEach(task => {
    const startDate = parseDate(task.created_date);
    const endDate = parseDate(task.due_date);

    if (startDate && endDate) {
      taskData.addRow([task.task_title, startDate, endDate]);
    }
  });

  const options = {
    title: 'Gantt Chart',
    width: 900,
    height: 500,
    chartArea: {
      left: 100, // Adjust this value to move the chart to the right
      top: 20,
      width: '100%', // Adjust width to fit within the new chart area
      height: '100%',
    },
    timeline: {
      colorByRowLabel: true,
    },
  };

  const container = document.getElementById('timeline');
  const chart = new google.visualization.Timeline(container);
  chart.draw(taskData, options);
}

function drawPieChart(tasks) {
  const statusCounts = tasks.reduce((counts, task) => {
    const status = task.status || 'Unknown';
    counts[status] = (counts[status] || 0) + 1;
    return counts;
  }, {});

  const chartData = [['Status', 'Count']];
  for (const [status, count] of Object.entries(statusCounts)) {
    chartData.push([status, count]);
  }

  const data = google.visualization.arrayToDataTable(chartData);

  const options = {
    title: 'Task Status Distribution',
    is3D: true,
    width: 900,
    height: 500,
    chartArea: { width: '100%', height: '100%' },
    colors: ['#4CAF50', '#FFC107', '#F44336', '#2196F3'],
  };

  const container = document.getElementById('pie');
  const chart = new google.visualization.PieChart(container);
  chart.draw(data, options);
}
