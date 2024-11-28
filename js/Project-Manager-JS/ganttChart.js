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
    width: '100%',
    height: 200,
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
    backgroundColor: 'transparent',
    is3D: true,
    width: 500,
    height: 250,
    chartArea: { width: '90%', height: '80%' },
    colors: ['#92211F', '#92641F', '#1F4692', '#59921F'],
  };

  const container = document.getElementById('pie');
  const chart = new google.visualization.PieChart(container);
  chart.draw(data, options);
}
