<?php
session_start();

// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once 'dp.php';

// Mock data for development plans (replace with actual database queries)
function getDevelopmentPlans() {
    return [
        [
            'id' => 1,
            'employee_name' => 'John Doe',
            'plan_title' => 'Leadership Development Program',
            'description' => 'Comprehensive leadership training including communication, team management, and strategic planning.',
            'start_date' => '2024-01-15',
            'end_date' => '2024-06-15',
            'status' => 'In Progress',
            'progress' => 65,
            'assigned_by' => 'HR Manager'
        ],
        [
            'id' => 2,
            'employee_name' => 'Jane Smith',
            'plan_title' => 'Technical Skills Enhancement',
            'description' => 'Advanced training in software development, cloud technologies, and project management.',
            'start_date' => '2024-02-01',
            'end_date' => '2024-08-01',
            'status' => 'Active',
            'progress' => 40,
            'assigned_by' => 'Department Head'
        ],
        [
            'id' => 3,
            'employee_name' => 'Mike Johnson',
            'plan_title' => 'Career Advancement Plan',
            'description' => 'Structured plan for promotion readiness including mentorship, skill development, and performance goals.',
            'start_date' => '2024-03-01',
            'end_date' => '2024-12-01',
            'status' => 'Planning',
            'progress' => 15,
            'assigned_by' => 'Supervisor'
        ]
    ];
}

function getDevelopmentPlanStats() {
    return [
        'total_plans' => 12,
        'active_plans' => 8,
        'completed_plans' => 4,
        'overdue_plans' => 2
    ];
}

$plans = getDevelopmentPlans();
$stats = getDevelopmentPlanStats();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Development Plans - HR Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="styles.css?v=rose">
    <style>
        .progress-bar-custom {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-active {
            background: rgba(40, 167, 69, 0.1);
            color: var(--success);
        }

        .status-in-progress {
            background: rgba(255, 193, 7, 0.1);
            color: var(--warning);
        }

        .status-planning {
            background: rgba(23, 162, 184, 0.1);
            color: var(--info);
        }

        .status-completed {
            background: rgba(40, 167, 69, 0.1);
            color: var(--success);
        }

        .plan-card {
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 4px 15px rgba(233, 30, 99, 0.1);
        }

        .plan-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(233, 30, 99, 0.2);
        }

        .stats-gradient {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        }

        .timeline-item {
            position: relative;
            padding-left: 30px;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--primary-color);
        }

        .timeline-item::after {
            content: '';
            position: absolute;
            left: 5px;
            top: 12px;
            width: 2px;
            height: calc(100% - 12px);
            background: var(--border-light);
        }

        .timeline-item:last-child::after {
            display: none;
        }

        .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(233, 30, 99, 0.25);
        }

        .btn-add-plan {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-add-plan:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(233, 30, 99, 0.3);
        }

        .filter-section {
            background: var(--bg-card);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <?php include 'navigation.php'; ?>
        <div class="row">
            <?php include 'sidebar.php'; ?>
            <div class="main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="section-title">
                        <i class="fas fa-route mr-2"></i>
                        Development Plans
                    </h2>
                    <button class="btn btn-add-plan" data-toggle="modal" data-target="#addPlanModal">
                        <i class="fas fa-plus mr-2"></i>
                        Create New Plan
                    </button>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card stats-card">
                            <div class="card-body text-center">
                                <i class="fas fa-clipboard-list text-primary"></i>
                                <h6 class="text-muted mt-2">Total Plans</h6>
                                <h3 class="card-title"><?php echo $stats['total_plans']; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stats-card">
                            <div class="card-body text-center">
                                <i class="fas fa-play-circle text-success"></i>
                                <h6 class="text-muted mt-2">Active Plans</h6>
                                <h3 class="card-title"><?php echo $stats['active_plans']; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stats-card">
                            <div class="card-body text-center">
                                <i class="fas fa-check-circle text-info"></i>
                                <h6 class="text-muted mt-2">Completed</h6>
                                <h3 class="card-title"><?php echo $stats['completed_plans']; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stats-card">
                            <div class="card-body text-center">
                                <i class="fas fa-exclamation-triangle text-warning"></i>
                                <h6 class="text-muted mt-2">Overdue</h6>
                                <h3 class="card-title"><?php echo $stats['overdue_plans']; ?></h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="filter-section">
                    <div class="row">
                        <div class="col-md-3">
                            <select class="form-control">
                                <option>All Status</option>
                                <option>Active</option>
                                <option>In Progress</option>
                                <option>Planning</option>
                                <option>Completed</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control">
                                <option>All Employees</option>
                                <option>John Doe</option>
                                <option>Jane Smith</option>
                                <option>Mike Johnson</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control" placeholder="Start Date">
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary btn-block">
                                <i class="fas fa-search mr-2"></i>
                                Filter
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Development Plans Table -->
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-list mr-2"></i>
                        Development Plans Overview
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Plan Title</th>
                                        <th>Status</th>
                                        <th>Progress</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($plans as $plan): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="activity-icon mr-3">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <div>
                                                    <strong><?php echo htmlspecialchars($plan['employee_name']); ?></strong>
                                                    <br>
                                                    <small class="text-muted">Assigned by: <?php echo htmlspecialchars($plan['assigned_by']); ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($plan['plan_title']); ?></strong>
                                            <br>
                                            <small class="text-muted"><?php echo htmlspecialchars(substr($plan['description'], 0, 50)) . '...'; ?></small>
                                        </td>
                                        <td>
                                            <span class="status-badge status-<?php echo strtolower(str_replace(' ', '-', $plan['status'])); ?>">
                                                <?php echo htmlspecialchars($plan['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar progress-bar-custom" role="progressbar"
                                                     style="width: <?php echo $plan['progress']; ?>%"
                                                     aria-valuenow="<?php echo $plan['progress']; ?>" aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                            <small class="text-muted"><?php echo $plan['progress']; ?>%</small>
                                        </td>
                                        <td><?php echo date('M d, Y', strtotime($plan['start_date'])); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($plan['end_date'])); ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-primary" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-secondary" title="Edit Plan">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-success" title="Mark Complete">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Recent Activities Timeline -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-history mr-2"></i>
                                Recent Activities
                            </div>
                            <div class="card-body">
                                <div class="timeline-item mb-3">
                                    <h6 class="mb-1">New development plan created</h6>
                                    <small class="text-muted">John Doe's Leadership Program - 2 hours ago</small>
                                </div>
                                <div class="timeline-item mb-3">
                                    <h6 class="mb-1">Plan milestone achieved</h6>
                                    <small class="text-muted">Jane Smith completed Module 3 - 1 day ago</small>
                                </div>
                                <div class="timeline-item mb-3">
                                    <h6 class="mb-1">Plan review scheduled</h6>
                                    <small class="text-muted">Mike Johnson's quarterly review - 3 days ago</small>
                                </div>
                                <div class="timeline-item">
                                    <h6 class="mb-1">Training session completed</h6>
                                    <small class="text-muted">Team leadership workshop - 1 week ago</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-chart-pie mr-2"></i>
                                Plan Distribution
                            </div>
                            <div class="card-body">
                                <canvas id="plansChart" width="100%" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add New Plan Modal -->
    <div class="modal fade" id="addPlanModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle mr-2"></i>
                        Create New Development Plan
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addPlanForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="employeeSelect">Select Employee</label>
                                    <select class="form-control" id="employeeSelect" required>
                                        <option value="">Choose employee...</option>
                                        <option>John Doe</option>
                                        <option>Jane Smith</option>
                                        <option>Mike Johnson</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="planTitle">Plan Title</label>
                                    <input type="text" class="form-control" id="planTitle" placeholder="Enter plan title" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="planDescription">Description</label>
                            <textarea class="form-control" id="planDescription" rows="3" placeholder="Describe the development plan objectives..." required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="startDate">Start Date</label>
                                    <input type="date" class="form-control" id="startDate" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="endDate">End Date</label>
                                    <input type="date" class="form-control" id="endDate" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="planObjectives">Key Objectives</label>
                            <textarea class="form-control" id="planObjectives" rows="2" placeholder="List the main objectives of this plan..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="createPlan()">
                        <i class="fas fa-save mr-2"></i>
                        Create Plan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Initialize Chart.js for plan distribution
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('plansChart').getContext('2d');
            const plansChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Active', 'In Progress', 'Planning', 'Completed'],
                    datasets: [{
                        data: [8, 3, 2, 4],
                        backgroundColor: [
                            '#E91E63',
                            '#FFC107',
                            '#17A2B8',
                            '#28A745'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        position: 'bottom'
                    }
                }
            });
        });

        // Form validation and submission
        function createPlan() {
            const form = document.getElementById('addPlanForm');
            if (form.checkValidity()) {
                // Here you would typically send the data to the server
                alert('Development plan created successfully!');
                $('#addPlanModal').modal('hide');
                form.reset();
            } else {
                form.reportValidity();
            }
        }

        // Add some interactive features
        document.querySelectorAll('.btn-outline-primary').forEach(btn => {
            btn.addEventListener('click', function() {
                // View plan details
                alert('Viewing plan details...');
            });
        });

        document.querySelectorAll('.btn-outline-secondary').forEach(btn => {
            btn.addEventListener('click', function() {
                // Edit plan
                alert('Opening edit form...');
            });
        });

        document.querySelectorAll('.btn-outline-success').forEach(btn => {
            btn.addEventListener('click', function() {
                // Mark as complete
                if (confirm('Mark this plan as completed?')) {
                    alert('Plan marked as completed!');
                    location.reload();
                }
            });
        });
    </script>
</body>
</html>
