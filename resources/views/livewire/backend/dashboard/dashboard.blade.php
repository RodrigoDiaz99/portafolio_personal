<div class="container-fluid mt-5">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0">Dashboard Administrativo</h2>
        </div>
    </div>

    <!-- Estadísticas Principales -->
    <div class="row">
        <!-- Estadísticas del Blog -->
        <div class="col-xl-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <h5 class="mb-0">Resumen del Blog</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-4 mb-md-0">
                            <div class="card border-0 bg-primary text-white shadow">
                                
                                <div class="card-body text-center py-4">
                                    <i class="bi bi-file-post" style="font-size: 2rem; color: white;"></i>
                                    <h3 class="mb-1">{{ $postsCount }}</h3>
                                    <p class="mb-0 font-weight-light text-white small">Publicaciones</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4 mb-md-0">
                            <div class="card border-0 bg-info text-white shadow">
                                <div class="card-body text-center py-4">
                                    <i class="bi bi-chat-text-fill" style="font-size: 2rem; color: white;"></i>
                                    <h3 class="mb-1">{{ $commentsCount }}</h3>
                                    <p class="mb-0 font-weight-light text-white small">Comentarios</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 bg-warning text-white shadow">
                                <div class="card-body text-center py-4">
                                    <i class="bi bi-eye" style="font-size: 2rem; color: white;"></i>
                                    <h3 class="mb-1">{{ $viewsCount }}</h3>
                                    <p class="mb-0 font-weight-light text-white small">Visitas</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 bg-danger text-white shadow">
                                <div class="card-body text-center py-4">
                                    <i class="bi bi-heart-fill" style="font-size: 2rem; color: white;"></i>
                                    <h3 class="mb-1">{{ $likesCount }}</h3>
                                    <p class="mb-0 font-weight-light text-white small">Me gusta</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas Rápidas -->
        <div class="col-xl-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <h5 class="mb-0">Actividad Reciente</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-light rounded p-2 mr-3">
                            <i class="fas fa-file-alt text-primary"></i>
                        </div>
                        <div>
                            <p class="mb-0 font-weight-bold">{{ $recentPostsCount }} nuevos posts</p>
                            <small class="text-muted">Últimos 30 días</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="bg-light rounded p-2 mr-3">
                            <i class="fas fa-comments text-success"></i>
                        </div>
                        <div>
                            <p class="mb-0 font-weight-bold">{{ $recentCommentsCount ?? 0 }} comentarios</p>
                            <small class="text-muted">Última semana</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="row mt-4">
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <h5 class="mb-0">Actividad del Blog</h5>
                </div>
                <div class="card-body">
                    <div id="blogActivityChart" style="min-height: 350px;"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <h5 class="mb-0">Posts Populares</h5>
                </div>
                <div class="card-body">
                    <div id="popularPostsChart" style="min-height: 350px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="{{ asset('panel/plugins/apex/apexcharts.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Esperar a que Livewire termine de cargar
    setTimeout(initCharts, 100);
});

function initCharts() {
    // Gráfico de actividad del blog
    var blogActivityOptions = {
        chart: {
            type: 'area',
            height: '100%',
            toolbar: {
                show: true
            },
            zoom: {
                enabled: true
            }
        },
        colors: ['#4e73df', '#1cc88a', '#f6c23e', '#e74a3b'],
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 2
        },
        series: [{
            name: 'Posts',
            data: @json($postsData ?? [])
        }, {
            name: 'Comentarios',
            data: @json($commentsData ?? [])
        }, {
            name: 'Visitas',
            data: @json($viewsData ?? [])
        }, {
            name: 'Likes',
            data: @json($likesData ?? [])
        }],
        xaxis: {
            categories: @json($dates ?? []),
            labels: {
                style: {
                    colors: '#858796'
                }
            }
        },
        yaxis: {
            labels: {
                style: {
                    colors: '#858796'
                }
            }
        },
        tooltip: {
            theme: 'light'
        },
        grid: {
            borderColor: '#e3e6f0',
            strokeDashArray: 3
        }
    };

        var blogActivityChart = new ApexCharts(
            document.querySelector("#blogActivityChart"),
            blogActivityOptions
        );
        blogActivityChart.render();

        // Gráfico de posts populares
        var popularPostsOptions = {
        chart: {
            type: 'bar',
            height: '100%',
            toolbar: {
                show: true
            }
        },
        colors: ['#36b9cc', '#1cc88a'],
        plotOptions: {
            bar: {
                borderRadius: 4,
                horizontal: true,
                columnWidth: '70%',
                distributed: true
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function(val) {
                return val;
            },
            style: {
                colors: ['#fff']
            }
        },
        series: [{
            name: 'Visitas',
            data: @json($popularPostsViews ?? [])
        }],
        xaxis: {
            categories: @json($popularPostsTitles ?? []),
            labels: {
                style: {
                    colors: '#858796'
                }
            }
        },
        yaxis: {
            labels: {
                style: {
                    colors: '#858796'
                }
            }
        },
        tooltip: {
            theme: 'light'
        },
        grid: {
            borderColor: '#e3e6f0',
            strokeDashArray: 3
        }
    };

        var popularPostsChart = new ApexCharts(
            document.querySelector("#popularPostsChart"),
            popularPostsOptions
        );
        popularPostsChart.render();
    }
</script>
