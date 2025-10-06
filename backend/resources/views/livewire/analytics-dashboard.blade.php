<div class="space-y-10 text-slate-900 dark:text-slate-100">
    {{-- KPIs --}}
    <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-6 gap-6">
      @php($k = $kpis)
      @foreach ([
        ['Usuarios hoy', $k['nuevos_hoy']],
        ['Usuarios 7d', $k['nuevos_7d']],
        ['Usuarios 30d', $k['nuevos_30d']],
        ['DAU', $k['dau']],
        ['Ejercicios hoy', $k['ejercicios_hoy']],
        ['Precisión 7d', $k['accuracy_7d'].'%'],
      ] as $card)
        <div class="rounded-3xl border border-slate-200/70 bg-white/90 p-5 shadow-lg shadow-slate-200/60 backdrop-blur transition-colors dark:border-slate-800/60 dark:bg-slate-900/70 dark:shadow-black/30">
          <div class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $card[0] }}</div>
          <div class="mt-3 text-3xl font-semibold text-slate-900 dark:text-white">{{ $card[1] }}</div>
        </div>
      @endforeach
    </div>
  
    {{-- Top curso --}}
    <div class="rounded-3xl border border-slate-200/70 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur transition-colors dark:border-slate-800/60 dark:bg-slate-900/70 dark:shadow-black/30">
      <div class="text-sm font-medium text-slate-500 dark:text-slate-400">Top curso (7d)</div>
      <div class="mt-2 text-xl font-semibold text-slate-900 dark:text-white">{{ $kpis['top_curso_7d'] }}</div>
    </div>
    
    {{-- Charts --}}
    <div class="space-y-6">
      <div class="rounded-3xl border border-slate-200/70 bg-white/95 p-6 shadow-xl shadow-slate-200/60 ring-1 ring-white/40 backdrop-blur dark:border-slate-800/60 dark:bg-slate-900/80 dark:shadow-black/30 dark:ring-white/5">
        <h3 class="mb-4 text-lg font-semibold text-slate-800 dark:text-slate-100">Altas por día (30d)</h3>
        <div class="h-64">
          <canvas id="signups30" class="h-full w-full"></canvas>
        </div>
      </div>
      
  
      <div class="rounded-3xl border border-slate-200/70 bg-white/95 p-6 shadow-xl shadow-slate-200/60 ring-1 ring-white/40 backdrop-blur dark:border-slate-800/60 dark:bg-slate-900/80 dark:shadow-black/30 dark:ring-white/5">
        <h3 class="mb-4 text-lg font-semibold text-slate-800 dark:text-slate-100">Usuarios activos por día (30d)</h3>
        <div class="h-64">
          <canvas id="dau30" class="h-full w-full"></canvas>
        </div>
      </div>
  
      <div class="rounded-3xl border border-slate-200/70 bg-white/95 p-6 shadow-xl shadow-slate-200/60 ring-1 ring-white/40 backdrop-blur dark:border-slate-800/60 dark:bg-slate-900/80 dark:shadow-black/30 dark:ring-white/5">
        <h3 class="mb-4 text-lg font-semibold text-slate-800 dark:text-slate-100">Ejercicios por día (30d) + Precisión</h3>
        <div class="h-64">
          <canvas id="results30" class="h-full w-full"></canvas>
        </div>
      </div>
  
      <div class="rounded-3xl border border-slate-200/70 bg-white/95 p-6 shadow-xl shadow-slate-200/60 ring-1 ring-white/40 backdrop-blur dark:border-slate-800/60 dark:bg-slate-900/80 dark:shadow-black/30 dark:ring-white/5">
        <h3 class="mb-4 text-lg font-semibold text-slate-800 dark:text-slate-100">Top cursos por actividad (7d)</h3>
        <div class="h-72">
          <canvas id="course7" class="h-full w-full"></canvas>
        </div>
      </div>
  
      <div class="rounded-3xl border border-slate-200/70 bg-white/95 p-6 shadow-xl shadow-slate-200/60 ring-1 ring-white/40 backdrop-blur dark:border-slate-800/60 dark:bg-slate-900/80 dark:shadow-black/30 dark:ring-white/5">
        <h3 class="mb-4 text-lg font-semibold text-slate-800 dark:text-slate-100">Tipos de ejercicio (7d)</h3>
        <div class="h-64">
          <canvas id="types7" class="h-full w-full"></canvas>
        </div>
      </div>
    </div>
  </div>
  
  @push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
  (function(){
    const signups = @json($signups30);
    const dau = @json($dau30);
    const res = @json($results30);
    const course = @json($course7);
    const types = @json($types7);
  
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const palette = prefersDark ? {
      text: '#f8fafc',
      grid: 'rgba(148, 163, 184, 0.25)',
      primary: '#38bdf8',
      secondary: '#a855f7',
      success: '#4ade80',
      warning: '#facc15',
      muted: '#64748b'
    } : {
      text: '#0f172a',
      grid: 'rgba(148, 163, 184, 0.2)',
      primary: '#2563eb',
      secondary: '#9333ea',
      success: '#16a34a',
      warning: '#f59e0b',
      muted: '#64748b'
    };
  
    const hexToRgb = (hex) => {
      const clean = hex.replace('#', '');
      const bigint = parseInt(clean, 16);
      return [
        (bigint >> 16) & 255,
        (bigint >> 8) & 255,
        bigint & 255,
      ];
    };
  
    const withAlpha = (hex, alpha) => {
      const [r, g, b] = hexToRgb(hex);
      return `rgba(${r}, ${g}, ${b}, ${alpha})`;
    };
  
    Chart.defaults.color = palette.text;
    Chart.defaults.borderColor = palette.grid;
    Chart.defaults.font.family = 'Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont';
  
    const day = arr => arr.map(i => i.d);
    const val = (arr, k) => arr.map(i => Number(i[k] || 0));
  
    // Formateo reutilizable para fechas (solo día + mes)
    const formatDateLabel = function(value) {
      const date = new Date(this.getLabelForValue(value));
      return date.toLocaleDateString('es-AR', { day: '2-digit', month: 'short' });
    };
  
    // --- Altas por día (30d) ---
    new Chart(document.getElementById('signups30'), {
      type: 'line',
      data: {
        labels: day(signups),
        datasets: [{
          label: 'Altas',
          data: val(signups,'c'),
          borderColor: palette.primary,
          backgroundColor: withAlpha(palette.primary, 0.15),
          fill: true,
          tension: 0.35,
          pointRadius: 3,
          pointBackgroundColor: palette.primary,
        }]
      },
      options: { 
        responsive: true, 
        maintainAspectRatio: false,
        scales: {
          x: {
            ticks: {
              autoSkip: true,
              maxTicksLimit: 7,
              callback: formatDateLabel,
              maxRotation: 45,
              minRotation: 45,
            },
            grid: { display: false },
          },
          y: { beginAtZero: true },
        }
      }
    });
  
    // --- Usuarios activos por día (30d) ---
    new Chart(document.getElementById('dau30'), {
      type: 'line',
      data: {
        labels: day(dau),
        datasets: [{
          label: 'DAU',
          data: val(dau,'dau'),
          borderColor: palette.secondary,
          backgroundColor: withAlpha(palette.secondary, 0.15),
          fill: true,
          tension: 0.35,
          pointRadius: 3,
          pointBackgroundColor: palette.secondary,
        }]
      },
      options: { 
        responsive: true, 
        maintainAspectRatio: false,
        scales: {
          x: {
            ticks: {
              autoSkip: true,
              maxTicksLimit: 7,
              callback: formatDateLabel,
              maxRotation: 45,
              minRotation: 45,
            },
            grid: { display: false },
          },
          y: { beginAtZero: true },
        }
      }
    });
  
    // --- Ejercicios por día (30d) + Precisión ---
    const rLabels = day(res);
    const rTotal = val(res,'total');
    const rAcc   = val(res,'accuracy');
  
    new Chart(document.getElementById('results30'), {
      data: {
        labels: rLabels,
        datasets: [
          {
            type:'bar',
            label:'Ejercicios',
            data: rTotal,
            backgroundColor: withAlpha(palette.primary, 0.25),
            borderRadius: 6,
            borderSkipped: false,
            yAxisID:'y'
          },
          {
            type:'line',
            label:'Precisión %',
            data: rAcc,
            borderColor: palette.success,
            backgroundColor: withAlpha(palette.success, 0.1),
            tension: 0.35,
            yAxisID:'y1',
            pointRadius: 3,
            pointBackgroundColor: palette.success
          },
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          x: {
            ticks: {
              autoSkip: true,
              maxTicksLimit: 7,
              callback: formatDateLabel,
              maxRotation: 45,
              minRotation: 45,
            },
            grid: { display: false },
          },
          y:  { beginAtZero: true },
          y1: { position:'right', min:0, max:100, grid: { drawOnChartArea: false } }
        }
      }
    });
  
    // --- Top cursos (7d) ---
    new Chart(document.getElementById('course7'), {
      type: 'bar',
      data: {
        labels: course.map(i => i.course),
        datasets: [
          {
            label: 'Correctos',
            data: course.map(i => Number(i.correctos)),
            backgroundColor: withAlpha(palette.primary, 0.8),
            borderRadius: 6,
            borderSkipped: false,
          },
          {
            label: 'Incorrectos',
            data: course.map(i => Number(i.incorrectos)),
            backgroundColor: withAlpha(palette.warning, 0.7),
            borderRadius: 6,
            borderSkipped: false,
          },
        ]
      },
      options: { responsive: true, maintainAspectRatio: false }
    });
  
    // --- Tipos de ejercicio (7d) ---
    const typeColors = [palette.primary, palette.secondary, palette.success, palette.warning, '#f97316', '#ec4899'];
  
    new Chart(document.getElementById('types7'), {
      type: 'doughnut',
      data: {
        labels: types.map(i => i.label ?? i.type),
        datasets: [{
          data: types.map(i => Number(i.total)),
          backgroundColor: types.map((_, idx) => withAlpha(typeColors[idx % typeColors.length], 0.85)),
          borderColor: types.map((_, idx) => withAlpha(typeColors[idx % typeColors.length], prefersDark ? 0.9 : 1)),
          borderWidth: 1,
        }]
      },
      options: { responsive: true, maintainAspectRatio: false }
    });
  })();
  </script>
  @endpush
  