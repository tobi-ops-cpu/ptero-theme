<template>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <!-- CPU Usage Chart -->
    <div class="bg-card rounded-xl p-6 shadow-lg border border-gray h-56">
      <h3 class="text-lg font-semibold mb-2 text-accent glow">CPU Usage</h3>
      <canvas ref="cpuChart" class="w-full h-full"></canvas>
    </div>

    <!-- RAM Usage Chart -->
    <div class="bg-card rounded-xl p-6 shadow-lg border border-gray h-56">
      <h3 class="text-lg font-semibold mb-2 text-accent glow">Memory Usage</h3>
      <canvas ref="ramChart" class="w-full h-full"></canvas>
    </div>
  </div>
</template>

<script>
import Chart from "chart.js/auto";

export default {
  name: "ResourceGraphs",
  props: ["server"],
  data() {
    return {
      cpuChart: null,
      ramChart: null,
      cpuData: Array(30).fill(0),
      ramData: Array(30).fill(0),
      pollInterval: null,
    };
  },
  mounted() {
    this.initCharts();
    this.startPolling();
  },
  methods: {
    initCharts() {
      // CPU Chart
      this.cpuChart = new Chart(this.$refs.cpuChart, {
        type: "line",
        data: {
          labels: Array(30).fill(""),
          datasets: [
            {
              label: "CPU %",
              data: this.cpuData,
              borderColor: "#00d1ff",
              backgroundColor: "rgba(0, 209, 255, 0.1)",
              borderWidth: 2,
              fill: true,
              tension: 0.3,
            },
          ],
        },
        options: this.chartOptions(0, 100),
      });

      // RAM Chart
      this.ramChart = new Chart(this.$refs.ramChart, {
        type: "line",
        data: {
          labels: Array(30).fill(""),
          datasets: [
            {
              label: "RAM (GiB)",
              data: this.ramData,
              borderColor: "#00bfff",
              backgroundColor: "rgba(0, 191, 255, 0.1)",
              borderWidth: 2,
              fill: true,
              tension: 0.3,
            },
          ],
        },
        options: this.chartOptions(),
      });
    },
    chartOptions(min = null, max = null) {
      return {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
          y: {
            min,
            max,
            ticks: { color: "#a0a0a0" },
            grid: { color: "rgba(255, 255, 255, 0.1)" },
          },
          x: {
            ticks: { color: "#a0a0a0" },
            grid: { display: false },
          },
        },
      };
    },
    startPolling() {
      this.pollInterval = setInterval(async () => {
        try {
          const res = await fetch(`/api/servers/${this.server.id}/resources`);
          const data = await res.json();

          // Update CPU
          this.cpuData.shift();
          this.cpuData.push(Math.min(100, data.cpu_absolute || 0));
          this.cpuChart.update();

          // Update RAM
          const ramUsed = (data.memory_bytes / (1024 * 1024 * 1024)).toFixed(2);
          this.ramData.shift();
          this.ramData.push(ramUsed);
          this.ramChart.update();
        } catch (err) {
          console.warn("Failed to fetch server resources:", err);
        }
      }, 3000);
    },
  },
  beforeDestroy() {
    if (this.pollInterval) clearInterval(this.pollInterval);
    if (this.cpuChart) this.cpuChart.destroy();
    if (this.ramChart) this.ramChart.destroy();
  },
};
</script>
